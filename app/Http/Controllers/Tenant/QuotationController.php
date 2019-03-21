<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Person;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\ChargeDiscountType;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Quotation;
use App\CoreFacturalo\Requests\Inputs\Common\LegendInput;
use App\Models\Tenant\Item;
use App\Models\Tenant\Series;
use App\Http\Resources\Tenant\QuotationCollection;
use App\Http\Resources\Tenant\QuotationResource;
use App\Models\Tenant\Catalogs\AffectationIgvType;  
use App\Models\Tenant\Catalogs\DocumentType;  
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\AttributeType;
use App\Models\Tenant\Company;
use App\Http\Requests\Tenant\QuotationRequest;
use Illuminate\Support\Str;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\CoreFacturalo\Requests\Inputs\Common\EstablishmentInput;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\Template;
use Mpdf\Mpdf;

class QuotationController extends Controller
{

    use StorageDocument;

    protected $quotation;
    protected $company;
    
    public function index()
    {
        return view('tenant.quotations.index');
    }


    public function create()
    {
        return view('tenant.quotations.form');
    }

    public function columns()
    {
        return [
            'date_of_issue' => 'Fecha de emisión'
        ];
    }

    public function records(Request $request)
    {
        $records = Quotation::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new QuotationCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function searchCustomers(Request $request)
    {    
         
        $customers = Person::where('number','like', "%{$request->input}%")
                            ->orWhere('name','like', "%{$request->input}%")
                            ->whereType('customers')->orderBy('name') 
                            ->get()->transform(function($row) {
                                return [
                                    'id' => $row->id,
                                    'description' => $row->number.' - '.$row->name,
                                    'name' => $row->name,
                                    'number' => $row->number,
                                    'identity_document_type_id' => $row->identity_document_type_id,
                                    'identity_document_type_code' => $row->identity_document_type->code
                                ];
                            }); 

        return compact('customers');
    }

    public function tables()
    {
        $customers = $this->table('customers');
        $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();   
        $currency_types = CurrencyType::whereActive()->get();
        // $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();        
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $company = Company::active();
        $document_type_03_filter = config('tenant.document_type_03_filter');

        return compact('customers', 'establishments','currency_types', 'discount_types', 'charge_types','company',
                        'document_type_03_filter');
    }

    public function option_tables()
    {
        $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();   
        $series = Series::where('establishment_id',$establishment->id)->get();
        $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();

        return compact('series', 'document_types_invoice');
    }

    public function item_tables()
    {
        $items = $this->table('items');
        $categories = []; 
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $system_isc_types = SystemIscType::whereActive()->get();
        $price_types = PriceType::whereActive()->get(); 
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();

        return compact('items', 'categories', 'affectation_igv_types', 'system_isc_types', 'price_types',
                        'discount_types', 'charge_types', 'attribute_types');
    }

    public function record($id)
    {
        $record = new QuotationResource(Quotation::findOrFail($id));

        return $record;
    }

    public function store(QuotationRequest $request)
    {

        DB::connection('tenant')->transaction(function () use ($request) {

            $data = $this->mergeData($request);
            $this->quotation =  Quotation::create($data);
            foreach ($data['items'] as $row)
            {
                $this->quotation->items()->create($row);
            }        
            
            $this->setFilename();
            $this->createPdf();

        }); 

        return [
            'success' => true,
            'data' => [
                'id' => $this->quotation->id,
            ],
        ];
    }

    public function mergeData($inputs)
    {

        $this->company = Company::active();

        $values = [
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'customer' => PersonInput::set($inputs['customer_id']),
            'establishment' => EstablishmentInput::set($inputs['establishment_id']),
            'soap_type_id' => $this->company->soap_type_id, 
            'state_type_id' => '01'
        ]; 

        $inputs->merge($values);

        return $inputs->all();
    }

    private function setFilename(){
        
        $name = [$this->quotation->prefix,$this->quotation->id,date('Ymd')];
        $this->quotation->filename = join('-', $name);
        $this->quotation->save(); 

    }

    public function createPdf() {

        $template = new Template();
        $pdf = new Mpdf();   
        $document = $this->quotation;
        
        $html = $template->pdf("quotation", $this->company, $document,"a4");
        $pdf->WriteHTML($html); 
       

        $this->uploadFile($pdf->output('', 'S'), 'quotation');
    }

    public function uploadFile($file_content, $file_type)
    {
        $this->uploadStorage($this->quotation->filename, $file_content, $file_type);
    }


    public function table($table)
    {
        switch ($table) {
            case 'customers':

                $customers = Person::whereType('customers')->orderBy('name')->take(20)->get()->transform(function($row) {
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

                break;
            
            case 'items':

                $items = Item::orderBy('description')->get()->transform(function($row) {
                    $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->description:$row->description;
                    return [
                        'id' => $row->id,
                        'full_description' => $full_description,
                        'description' => $row->description,
                        'currency_type_id' => $row->currency_type_id,
                        'currency_type_symbol' => $row->currency_type->symbol,
                        'sale_unit_price' => $row->sale_unit_price,
                        'purchase_unit_price' => $row->purchase_unit_price,
                        'unit_type_id' => $row->unit_type_id,
                        'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                        'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id
                    ];
                });
                return $items;

                break;
            default:

                return [];

                break;
        } 
    }

    public function searchCustomerById($id)
    {        
   
        $customers = Person::whereType('customers')
                    ->where('id',$id) 
                    ->get()->transform(function($row) {
                        return [
                            'id' => $row->id,
                            'description' => $row->number.' - '.$row->name,
                            'name' => $row->name,
                            'number' => $row->number,
                            'identity_document_type_id' => $row->identity_document_type_id,
                            'identity_document_type_code' => $row->identity_document_type->code
                        ];
                    }); 

        return compact('customers');
    }
 
}