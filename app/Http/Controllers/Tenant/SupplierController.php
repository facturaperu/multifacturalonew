<?php
namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Catalogs\Province;
use App\Models\Tenant\Supplier;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SupplierRequest;
use App\Http\Resources\Tenant\SupplierCollection;
use App\Http\Resources\Tenant\SupplierResource;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return view('tenant.suppliers.index');
    }

    public function columns()
    {
        return [
            'name' => 'Nombre',
            'number' => 'Número'
        ];
    }

    public function records(Request $request)
    {
        $records = Supplier::where($request->column, 'like', "%{$request->value}%")
                            ->orderBy('name');

        return new SupplierCollection($records->paginate(env('ITEMS_PER_PAGE',20)));
    }

    public function create()
    {
        return view('tenant.suppliers.form');
    }

    public function tables()
    {
        $countries = Country::whereActive()->orderByDescription()->get();
        $departments = Department::whereActive()->orderByDescription()->get();
        $provinces = Province::whereActive()->orderByDescription()->get();
        $districts = District::whereActive()->orderByDescription()->get();
        $identity_document_types = IdentityDocumentType::whereActive()->orderByDescription()->get();

        return compact('countries', 'departments', 'provinces', 'districts', 'identity_document_types');
    }

    public function record($id)
    {
        $record = new SupplierResource(Supplier::findOrFail($id));

        return $record;
    }

    public function store(SupplierRequest $request)
    {
        $id = $request->input('id');
        $supplier = Supplier::firstOrNew(['id' => $id]);
        $supplier->fill($request->all());
        $supplier->save();

        return [
            'success' => true,
            'message' => ($id)?'Proveedor editado con éxito':'Cliente registrado con éxito',
            'id' => $supplier->id
        ];
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return [
            'success' => true,
            'message' => 'Proveedor eliminado con éxito'
        ];
    }
}