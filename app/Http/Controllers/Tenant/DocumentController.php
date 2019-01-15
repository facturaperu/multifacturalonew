<?php
namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Documents\VoidedBuilder;
use App\CoreFacturalo\Facturalo\FacturaloDocument;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DocumentEmailRequest;
use App\Http\Requests\Tenant\DocumentVoidedRequest;
use App\Http\Resources\Tenant\DocumentCollection;
use App\Http\Resources\Tenant\DocumentResource;
use App\Mail\Tenant\DocumentEmail;
use App\Models\Tenant\Catalogs\Code;
use App\Models\Tenant\Company;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Document;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\Person;
use App\Models\Tenant\Series;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    use StorageDocument;

    public function __construct()
    {
        $this->middleware('transform.web:document', ['only' => ['store']]);
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
        $customers = $this->table('customers');
        $establishments = Establishment::all();
        $series = Series::all();
        $document_types_invoice = Code::whereCatalog('01')->whereCodes(['01', '03'])->get();
        $document_types_note = Code::whereCatalog('01')->whereCodes(['07', '08'])->get();
        $note_credit_types = Code::whereCatalog('09')->whereActive()->orderByDescription()->get();
        $note_debit_types = Code::whereCatalog('10')->whereActive()->orderByDescription()->get();
        $currency_types = Code::whereCatalog('02')->whereActive()->get();
        $operation_types = Code::whereCatalog('51')->whereActive()->get();
        $discounts = Code::whereCatalog('53')->whereType('discount')->whereLevel('global')->get();
        $charges = Code::whereCatalog('53')->whereType('charge')->whereLevel('global')->get();
        $attributes = Code::whereCatalog('55')->get();
        

        return compact('customers', 'establishments', 'series', 'document_types_invoice', 'document_types_note',
                       'note_credit_types', 'note_debit_types', 'currency_types', 'operation_types',
                       'discounts', 'charges', 'attributes');
    }

    public function item_tables()
    {
        $items = $this->table('items');
        $categories = [];//Category::cascade();
        $affectation_igv_types = Code::whereCatalog('07')->whereActive()->get();
        $system_isc_types = Code::whereCatalog('08')->whereActive()->get();
        $price_types = Code::whereCatalog('16')->whereActive()->get();
        $operation_types = Code::whereCatalog('51')->whereActive()->get();
        $discounts = Code::whereCatalog('53')->whereType('discount')->whereLevel('item')->get();
        $charges = Code::whereCatalog('53')->whereType('charge')->whereLevel('item')->get();
        $attributes = Code::whereCatalog('55')->get();

        return compact('items', 'categories', 'affectation_igv_types', 'system_isc_types', 'price_types',
                       'operation_types', 'discounts', 'charges', 'attributes');
    }

    public function table($table)
    {
        if ($table === 'customers') {
            $customers = Person::whereType('customer')->orderBy('name')->get()->transform(function($row) {
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