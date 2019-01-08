<?php
namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Documents\VoidedBuilder;
use App\CoreFacturalo\Facturalo;
use App\CoreFacturalo\FacturaloDocument;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DocumentEmailRequest;
use App\Http\Requests\Tenant\DocumentRequest;
use App\Http\Requests\Tenant\DocumentVoidedRequest;
use App\Http\Resources\Tenant\DocumentCollection;
use App\Http\Resources\Tenant\DocumentResource;
use App\Mail\Tenant\DocumentEmail;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\ChargeDiscountType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Catalogs\NoteCreditType;
use App\Models\Tenant\Catalogs\NoteDebitType;
use App\Models\Tenant\Catalogs\OperationType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Company;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Document;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\Series;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Throwable;

class DocumentController extends Controller
{
    use StorageDocument;

    public function __construct()
    {
        $this->middleware('transform.input:document,true', ['only' => ['store']]);
    }

    public function index()
    {
        return view('tenant.documents.index');
    }

    public function columns()
    {
        return [
            'number' => 'Número'
        ];
    }

    public function records(Request $request)
    {
        $records = Document::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new DocumentCollection($records->paginate(env('ITEMS_PER_PAGE', 20)));
    }

    public function create()
    {
        return view('tenant.documents.form');
    }

    public function tables()
    {
        $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();
        $document_types_note = DocumentType::whereIn('id', ['07', '08'])->get();
        $note_credit_types = NoteCreditType::whereActive()->orderByDescription()->get();
        $note_debit_types = NoteDebitType::whereActive()->orderByDescription()->get();
        $currency_types = CurrencyType::whereActive()->orderByDescription()->get();
        $operation_types = OperationType::whereActive()->orderById()->get();
        $establishments = Establishment::all();
        $series = Series::all();
        $customers = $this->table('customers');
        $discounts = ChargeDiscountType::whereType('discount')->whereLevel('global')->get();
        $charges = ChargeDiscountType::whereType('charge')->whereLevel('global')->get();

        return compact('document_types_invoice', 'document_types_note', 'note_credit_types', 'note_debit_types',
                       'currency_types', 'operation_types', 'establishments', 'series', 'customers',
                       'discounts', 'charges');
    }

    public function item_tables()
    {
        $items = $this->table('items');
        $operation_types = OperationType::whereActive()->orderById()->get();
        $affectation_igv_types = AffectationIgvType::whereActive()->orderById()->get();
        $system_isc_types = SystemIscType::whereActive()->orderByDescription()->get();
        $price_types = PriceType::whereActive()->orderByDescription()->get();
        $categories = [];//Category::cascade();
        $discounts = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charges = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();

        return compact('items', 'categories', 'operation_types', 'affectation_igv_types', 'system_isc_types', 'price_types',
                       'discounts', 'charges');
    }

    public function table($table)
    {
        if ($table === 'customers') {
            $customers = Customer::orderBy('name')->get()->transform(function($row) {
                return [
                    'id' => $row->id,
                    'description' => $row->number.' - '.$row->name,
                    'name' => $row->name,
                    'number' => $row->number,
                    'identity_document_type_id' => $row->identity_document_type_id,
                    'identity_document_type_code' => $row->identity_document_type->code
                ];
            });
            return $customers;
        }
        if ($table === 'items') {
            $items = Item::orderBy('description')->get()->transform(function($row) {
                $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->description:$row->description;
                return [
                    'id' => $row->id,
                    'full_description' => $full_description,
                    'description' => $row->description,
                    'currency_type_id' => $row->currency_type_id,
                    'currency_type_symbol' => $row->currency_type->symbol,
                    'unit_price' => $row->unit_price
                ];
            });
            return $items;
        }

        return [];
    }

    public function record($id)
    {
        $record = new DocumentResource(Document::findOrFail($id));

        return $record;
    }

    public function store(Request $request)
    {
        $facturalo = new FacturaloDocument();
        $facturalo->setInputs($request->all());

        DB::connection('tenant')->transaction(function () use($facturalo) {
            $facturalo->save();
            $facturalo->createXmlAndSign();
            $facturalo->createPdf();
        });
        $document = $facturalo->getDocument();

        $send = ($document->group_id === '01')?true:false;

        $configuration = Configuration::first();

        $send = $send && (bool)$configuration->send_auto;
        $res = ($send)?$facturalo->sendXml():[];

        return [
            'success' => true,
            'data' => [
                'id' => $document->id,
                'number' => $document->number_full,
            ],
            'links' => [
                'xml' => $document->download_external_xml,
                'pdf' => $document->download_external_pdf,
                'cdr' => ($send)?$document->download_external_cdr:'',
            ],
            'response' => $res
        ];
    }

    public function downloadExternal($type, $external_id)
    {
        $document = Document::where('external_id', $external_id)->first();
        if(!$document) {
            throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");
        }
        return $this->download($type, $document);
    }

    public function download($type, Document $document)
    {
        switch ($type) {
            case 'pdf':
                $folder = 'pdf';
                break;
            case 'xml':
                $folder = 'signed';
                break;
            case 'cdr':
                $folder = 'cdr';
                break;
            default:
                throw new Exception('Tipo de archivo a descargar es inválido');
        }

        return $this->downloadStorage($document->filename, $folder);
    }

    public function to_print($id)
    {
        $document = Document::find($id);
        $temp = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($temp, $this->getStorage($document->filename, 'pdf'));

        return response()->file($temp);
    }

    public function voided(DocumentVoidedRequest $request)
    {
        DB::connection('tenant')->transaction(function () use($request) {
            $document = Document::find($request->input('id'));
            $document->state_type_id = '13';
            //$document->voided_description = $request->input('voided_description');
            $document->save();

            if ($document->group_id === '01') {
                $builder = new VoidedBuilder();
                $builder->save($document);
                $xmlBuilder = new XmlBuilder();
                $xmlBuilder->createXMLSigned($builder);
            } else {
                $builder = new SummaryBuilder();
                $builder->voided($document);
                $xmlBuilder = new XmlBuilder();
                $xmlBuilder->createXMLSigned($builder);
            }
        });

        return [
            'success' => true,
            'message' => 'Se registró correctamente la anulación, por favor consulte el ticket.'
        ];
    }

    public function email(DocumentEmailRequest $request)
    {
        $company = Company::active();
        $document = Document::find($request->input('id'));
        $customer_email = $request->input('customer_email');

        Mail::to($customer_email)->send(new DocumentEmail($company, $document));

        return [
            'success' => true
        ];
    }

    public function send_xml($document_id)
    {
        $facturalo = new FacturaloDocument();
        $document = Document::find($document_id);
        $facturalo->setDocument($document);
        $facturalo->loadXmlSigned();
        $res = $facturalo->sendXml();

        return [
            'success' => true,
            'message' => $res['description'],
        ];
    }
}