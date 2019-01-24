<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Person;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\ChargeDiscountType;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Purchase;
use App\CoreFacturalo\Requests\Inputs\Common\LegendInput;
use App\Models\Tenant\Item;
use App\Http\Resources\Tenant\PurchaseCollection;
use App\Http\Resources\Tenant\PurchaseResource;
use App\Models\Tenant\Catalogs\AffectationIgvType;  
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Catalogs\NoteCreditType;
use App\Models\Tenant\Catalogs\NoteDebitType;
use App\Models\Tenant\Catalogs\OperationType;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\TributeConceptType;
use App\Models\Tenant\Company;
use Illuminate\Support\Str;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;

class PurchaseController extends Controller
{
    
    public function index()
    {
        return view('tenant.purchases.index');
    }


    public function create()
    {
        return view('tenant.purchases.form');
    }

    public function columns()
    {
        return [
            'number' => 'Número'
        ];
    }

    public function records(Request $request)
    {
        $records = Purchase::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new PurchaseCollection($records->paginate(env('ITEMS_PER_PAGE', 20)));
    }

    public function tables()
    {
        $suppliers = $this->table('suppliers');
        $establishment = Establishment::first();              
        $currency_types = CurrencyType::whereActive()->get();
        
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();

        return compact('suppliers', 'establishment','currency_types', 'discount_types', 'charge_types');
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
        $attribute_types = TributeConceptType::whereActive()->orderByDescription()->get();

        return compact('items', 'categories', 'affectation_igv_types', 'system_isc_types', 'price_types',
                        'discount_types', 'charge_types', 'attribute_types');
    }

    public function record($id)
    {
        $record = new PurchaseResource(Purchase::findOrFail($id));

        return $record;
    }

    public function store(Request $request)
    {

        $data = self::convert($request);

        $purchase = DB::connection('tenant')->transaction(function () use ($data) {
            
            $doc = Purchase::create($data);
        
            foreach ($data['items'] as $row) {
                $doc->items()->create($row);
            }     

            return $doc;
        });       
 
        return [
            'success' => true,
            'data' => [
                'id' => $purchase->id,
            ],
        ];
    }

    public static function convert($inputs){
        
        $company = Company::active();
        $soap_type_id = $company->soap_type_id;

        $inputs['user_id'] = auth()->id(); 
        $inputs['external_id'] = Str::uuid()->toString();
        $inputs['supplier'] = PersonInput::set($inputs['supplier_id']);
        $inputs['soap_type_id'] = $soap_type_id;
        $inputs['group_id'] = '01';
        $inputs['state_type_id'] = '01'; 

        return $inputs->all();
    }

    public function table($table)
    {
        switch ($table) {
            case 'suppliers':

                $suppliers = Person::whereType('supplier')->orderBy('name')->get()->transform(function($row) {
                    return [
                        'id' => $row->id,
                        'description' => $row->number.' - '.$row->name,
                        'name' => $row->name,
                        'number' => $row->number,
                        'identity_document_type_id' => $row->identity_document_type_id,
                        'identity_document_type_code' => $row->identity_document_type->code
                    ];
                });
                return $suppliers;

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
                        'unit_price' => $row->unit_price,
                        'unit_type_id' => $row->unit_type_id
                    ];
                });
                return $items;

                break;
            default:

                return [];

                break;
        } 

    }

}
