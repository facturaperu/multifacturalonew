<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\RetentionRequest;
use App\Http\Resources\Tenant\RetentionCollection;
use App\Http\Resources\Tenant\RetentionResource;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Company;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Series;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Retention;
use App\Models\Tenant\RetentionDetail;
use App\Models\Tenant\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RetentionController extends Controller
{
    public function index()
    {
        return view('tenant.retentions.index');
    }

    public function columns()
    {
        return [
            'number' => 'Número'
        ];
    }

    public function records(Request $request)
    {
        $records = Retention::where($request->column, 'like', "%{$request->value}%")
                            ->orderBy('series_id')
                            ->orderBy('number', 'desc');

        return new RetentionCollection($records->paginate(env('ITEMS_PER_PAGE', 5)));
    }

    public function create()
    {
        return view('tenant.retentions.form');
    }

    public function tables()
    {
        $user_id = Auth::id();
        $establishments = Establishment::all();
//        $currency_types = CurrencyType::all();
        $suppliers = $this->table('suppliers');
//        $items = $this->table('items');
        $document_types = DocumentType::whereIn('id', ['20'])->get();
        $series = Series::all();

        return compact('user_id', 'establishments', 'suppliers', 'document_types', 'series');
    }

//    public function item_tables()
//    {
//        $items = $this->table('items');
//        $currency_types = CurrencyType::all();
//        $document_types = DocumentType::all();
//
//        return compact('items', 'currency_types', 'document_types');
//    }

    public function table($table)
    {
        if ($table === 'suppliers') {
            $suppliers = Supplier::orderBy('name')->get()->transform(function($row) {
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
        }
//        if ($table === 'items') {
//            return RetentionDetail::all();
//        }

        return [];
    }

    public function record($id)
    {
        $record = new RetentionResource(Retention::findOrFail($id));

        return $record;
    }

//    public function setNumber($data)
//    {
//        $number = $data['number'];
//        $series_id = $data['series_id'];
//        $document_type_id = $data['document_type_id'];
//        $soap_type_id = $data['soap_type_id'];
//        if ($data['number'] === '#') {
//            $document = Retention::select('number')
//                                    ->where('series_id', $series_id)
//                                    ->where('document_type_id', $document_type_id)
//                                    ->where('soap_type_id', $soap_type_id)
//                                    ->orderBy('number', 'desc')
//                                    ->first();
//             $number = ($document)?(int)$document->number+1:1;
//        }
//        return $number;
//    }

    public function store(RetentionRequest $request)
    {
        $id = $request->input('id');
        $record = Retention::firstOrNew(['id' => $id]);
        $attributes = $request->all();
        $attributes['number'] = $this->setNumber($attributes);
        $record->fill($attributes);
        $record->save();
        foreach ($attributes['items'] as $detail) {
            $record->details()->create($detail);
        }
        return [
            'success' => true,
            'message' => ($id)?'Retención editada con éxito':'Retención registrada con éxito'
        ];
    }

//    public function destroy($id)
//    {
//        $record = Retention::findOrFail($id);
//        $record->delete();
//
//        return [
//            'success' => true,
//            'message' => 'Retención eliminada con éxito'
//        ];
//    }
}