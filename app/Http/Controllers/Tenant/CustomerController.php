<?php
namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Catalogs\Province;
use App\Models\Tenant\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CustomerRequest;
use App\Http\Resources\Tenant\CustomerCollection;
use App\Http\Resources\Tenant\CustomerResource;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('tenant.customers.index');
    }

    public function columns()
    {
        return [
            'id' => 'Código',
            'name' => 'Nombre',
            'number' => 'Número'
        ];
    }

    public function records(Request $request)
    {
        $records = Customer::where($request->column, 'like', "%{$request->value}%")
                            ->orderBy('name');

        return new CustomerCollection($records->paginate(env('ITEMS_PER_PAGE',20)));
    }

    public function create()
    {
        return view('tenant.customers.form');
    }

    public function tables()
    {
        $countries = Country::listActivesAndOrderByDescription();
        $departments = Department::listActivesAndOrderByDescription();
        $provinces = Province::listActivesAndOrderByDescription();
        $districts = District::listActivesAndOrderByDescription();
        $identity_document_types = IdentityDocumentType::listActivesAndOrderByDescription();

        return compact('countries', 'departments', 'provinces', 'districts', 'identity_document_types');
    }

    public function record($id)
    {
        $record = new CustomerResource(Customer::findOrFail($id));

        return $record;
    }

    public function store(CustomerRequest $request)
    {
        $id = $request->input('id');
        $customer = Customer::firstOrNew(['id' => $id]);
        $customer->fill($request->all());
        $customer->save();

        return [
            'success' => true,
            'message' => ($id)?'Cliente editado con éxito':'Cliente registrado con éxito',
            'id' => $customer->id
        ];
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return [
            'success' => true,
            'message' => 'Cliente eliminado con éxito'
        ];
    }
}