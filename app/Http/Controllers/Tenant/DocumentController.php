<?php
namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Documents\VoidedBuilder;
use App\CoreFacturalo\Facturalo;
use App\CoreFacturalo\Facturalo\FacturaloDocument;
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
use App\Models\Tenant\Catalogs\TributeConceptType;
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
use Nexmo\Account\Price;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('input.transform:document,web', ['only' => ['store']]);
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
        $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();
        $document_types_note = DocumentType::whereIn('id', ['07', '08'])->get();
        $note_credit_types = NoteCreditType::whereActive()->orderByDescription()->get();
        $note_debit_types = NoteDebitType::whereActive()->orderByDescription()->get();
        $currency_types = CurrencyType::whereActive()->get();
        $operation_types = OperationType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();

        return compact('customers', 'establishments', 'series', 'document_types_invoice', 'document_types_note',
                       'note_credit_types', 'note_debit_types', 'currency_types', 'operation_types',
                       'discount_types', 'charge_types');
    }

    public function item_tables()
    {
        $items = $this->table('items');
        $categories = [];//Category::cascade();
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $system_isc_types = SystemIscType::whereActive()->get();
        $price_types = PriceType::whereActive()->get();
        $operation_types = OperationType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $attribute_types = TributeConceptType::whereActive()->orderByDescription()->get();

        return compact('items', 'categories', 'affectation_igv_types', 'system_isc_types', 'price_types',
                       'operation_types', 'discount_types', 'charge_types', 'attribute_types');
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
                    'unit_price' => $row->unit_price,
                    'unit_type_id' => $row->unit_type_id
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
        $fact = $document = DB::transaction(function () use($request) {
            $facturalo = new Facturalo();
            $facturalo->save($request->all());
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            $facturalo->updateHash();
            $facturalo->updateQr();
            $facturalo->createPdf();
            $facturalo->senderXmlSignedBill();
            return $facturalo;
        });

        $document = $fact->getDocument();
        $response = $fact->getResponse();

        return [
            'success' => true,
            'data' => [
                'id' => $document->id,
//                'number' => $document->number_full,
//                'filename' => $document->filename,
//                'external_id' => $document->external_id,
//                'number_to_letter' => $document->number_to_letter,
//                'hash' => $document->hash,
//                'qr' => $document->qr,
            ],
//            'links' => [
//                'xml' => $document->download_external_xml,
//                'pdf' => $document->download_external_pdf,
//                'cdr' => ($response['sent'])?$document->download_external_cdr:'',
//            ],
//            'response' => ($response['sent'])?array_except($response, 'sent'):[]
        ];

//        $facturalo = new FacturaloDocument();
//        $facturalo->setInputs($request->all());
//
//        DB::connection('tenant')->transaction(function () use($facturalo) {
//            $facturalo->save();
//            $facturalo->createXmlAndSign();
//            $facturalo->createPdf();
//        });
//        $document = $facturalo->getDocument();
//
//        $send = ($document->group_id === '01')?true:false;
//
//        $configuration = Configuration::first();
//
//        $send = $send && (bool)$configuration->send_auto;
//        $res = ($send)?$facturalo->sendXml():[];
//
//        return [
//            'success' => true,
//            'data' => [
//                'id' => $document->id,
//                'number' => $document->number_full,
//            ],
//            'links' => [
//                'xml' => $document->download_external_xml,
//                'pdf' => $document->download_external_pdf,
//                'cdr' => ($send)?$document->download_external_cdr:'',
//            ],
//            'response' => $res
//        ];
    }

    public function downloadExternal($type, $external_id)
    {
        $document = Document::where('external_id', $external_id)->first();
        if(!$document) {
            throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");
        }
        return StorageDocument::download($document->filename, $type);
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

        return StorageDocument::download($document->filename, $folder);
    }

    public function to_print($id)
    {
        $document = Document::find($id);
        $temp = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($temp, StorageDocument::get($document->filename, 'pdf'));

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