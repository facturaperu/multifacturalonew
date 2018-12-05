<?php
namespace App\Http\Controllers\Tenant;

use App\Core\Builder\Documents\InvoiceBuilder;
use App\Core\Builder\Documents\NoteCreditBuilder;
use App\Core\Builder\Documents\NoteDebitBuilder;
use App\Core\Builder\Documents\SummaryBuilder;
use App\Core\Builder\Documents\VoidedBuilder;
use App\Core\Builder\XmlBuilder;
use App\Core\Helpers\StorageDocument;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DocumentEmailRequest;
use App\Http\Requests\Tenant\DocumentRequest;
use App\Http\Requests\Tenant\DocumentVoidedRequest;
use App\Http\Resources\Tenant\DocumentCollection;
use App\Http\Resources\Tenant\DocumentResource;
use App\Mail\Tenant\DocumentEmail;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\Code;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\ChargeDiscount;
use App\Models\Tenant\Company;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Document;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\Series;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    use StorageDocument;

    public function index()
    {
        return view('tenant.documents.index');
    }

    public function columns()
    {
        return [
            'id' => 'Código',
            'number' => 'Número'
        ];
    }

    public function records(Request $request)
    {
        $records = Document::where($request->column, 'like', "%{$request->value}%")
                            ->orderBy('series')
                            ->orderBy('number', 'desc');

        return new DocumentCollection($records->paginate(env('ITEMS_PER_PAGE', 5)));
    }

    public function create()
    {
        return view('tenant.documents.form');
    }

    public function tables()
    {
        $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();
        $document_types_note = DocumentType::whereIn('id', ['07', '08'])->get();
        $note_credit_types = Code::byCatalog('09');
        $note_debit_types = Code::byCatalog('10');
        $currency_types = CurrencyType::all();
//        $affectation_igv_types = AffectationType::all();
        $customers = $this->table('customers');
        $items = $this->table('items');
        $company = Company::with(['identity_document_type'])->first();
//        $establishment = Establishment::first();
        $establishments = Establishment::all();
        $series = Series::all();

        return compact('document_types_invoice', 'document_types_note', 'note_credit_types', 'note_debit_types',
                       'currency_types', 'customers', 'items', 'company', 'establishments',
                       'series');
    }

    public function item_tables()
    {
        $items = $this->table('items');
        $affectation_igv_types = AffectationIgvType::all();
        $system_isc_types = SystemIscType::all();
        $price_types = PriceType::all();
        $unit_types = [];//Code::byCatalog('03');
        $categories = [];//Category::cascade();
        $discounts = ChargeDiscount::whereIn('level', ['item', 'both'])
                                    ->where('type', 'discount')
                                    ->get();
        $charges = ChargeDiscount::whereIn('level', ['item', 'both'])
                                ->where('type', 'charge')
                                ->get();

        return compact('items', 'unit_types', 'categories', 'affectation_igv_types', 'system_isc_types', 'price_types', 'discounts', 'charges');
    }

    public function table($table)
    {
        if ($table === 'customers') {
            $customers = Customer::with(['identity_document_type'])->orderBy('name')->get()->transform(function($row) {
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
            return Item::with(['unit_type'])->orderBy('description')->get();
        }

        return [];
    }

    public function record($id)
    {
        $record = new DocumentResource(Document::with(['customer'])->findOrFail($id));

        return $record;
    }

    public function store(DocumentRequest $request)
    {
        $document = DB::connection('tenant')->transaction(function () use($request) {
            $document_type_code = ($request->has('document'))?$request->input('document.document_type_code'):
                                                              $request->input('document_type_code');
            switch ($document_type_code) {
                case '01':
                case '03':
                    $builder = new InvoiceBuilder();
                    break;
                case '07':
                    $builder = new NoteCreditBuilder();
                    break;
                case '08':
                    $builder = new NoteDebitBuilder();
                    break;
                default:
                    throw new Exception('Tipo de documento ingresado es inválido');
            }

            $builder->save($request->all());
            $xmlBuilder = new XmlBuilder();
            $xmlBuilder->createXMLSigned($builder);
            $document = $builder->getDocument();

            return $document;
        });

        return [
            'success' => true,
            'data' => [
                'id' => $document->id,
                'number' => $document->number_full,
                'hash' => $document->hash,
                'qr' => $document->qr,
                'filename' => $document->filename,
                'external_id' => $document->external_id,
                'number_to_letter' => $document->number_to_letter,
                'link_xml' => $document->download_xml,
                'link_pdf' => $document->download_pdf,
                'link_cdr' => $document->download_cdr,
            ]
        ];
    }

    public function downloadExternal($type, $external_id)
    {
        $document = Document::where('external_id', $external_id)->first();

        return $this->download($type, $document);
    }

    public function download($type, Document $document)
    {
        switch ($type) {
            case 'pdf':
                $folder = 'pdf';
                $extension = 'pdf';
                $filename = $document->filename;
                break;
            case 'xml':
                $folder = 'signed';
                $extension = 'xml';
                $filename = $document->filename;
                break;
            case 'cdr':
                $folder = 'cdr';
                $extension = 'xml';
                $filename = 'R-'.$document->filename;
                break;
            default:
                throw new Exception('Tipo de archivo a descargar es inválido');
        }

        return $this->downloadStorage($folder, $filename, $extension);
    }

    public function to_print($id)
    {
        $document = Document::find($id);
        $pathToFile = public_path('downloads'.DIRECTORY_SEPARATOR.$document->filename.'.pdf');
        file_put_contents($pathToFile, $this->getStorage('pdf', $document->filename, 'pdf'));

        return response()->file($pathToFile);
    }

    public function voided(DocumentVoidedRequest $request)
    {
        DB::connection('tenant')->transaction(function () use($request) {
            $document = Document::find($request->input('id'));
            $document->state_type_id = '13';
            $document->voided_description = $request->input('voided_description');
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
        $company = Company::first();
        $document = Document::with(['customer'])->find($request->input('id'));
        $customer_email = $request->input('customer_email');

        Mail::to($customer_email)->send(new DocumentEmail($company, $document));

        return [
            'success' => true
        ];
    }

    public function send_xml($document_id)
    {
        $document = Document::find($document_id);

        $xmlBuilder = new XmlBuilder();
        $res = $xmlBuilder->sendXmlCdr($document);

        return [
            'success' => $res
        ];
    }
}