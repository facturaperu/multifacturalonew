<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CustomerRequest;
use App\Http\Resources\Tenant\CustomerCollection;
use App\Http\Resources\Tenant\CustomerResource;
use App\Models\Tenant\Catalogs\Code;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Department;
use App\Models\Tenant\District;
use App\Models\Tenant\Province;
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

        return new CustomerCollection($records->paginate(env('ITEMS_PER_PAGE', 5)));
    }

    public function create()
    {
        return view('tenant.customers.form');
    }

    public function tables()
    {
        $countries = Code::byCatalog('04');
        $departments = Department::orderBy('description')->get();
        $provinces = Province::orderBy('description')->get();
        $districts = District::orderBy('description')->get();
        $identity_document_types = Code::byCatalog('06');

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
            'message' => ($id)?'Cliente editado con éxito':'Cliente registrado con éxito'
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