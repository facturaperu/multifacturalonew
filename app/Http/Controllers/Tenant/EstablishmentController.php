<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\EstablishmentRequest;
use App\Http\Resources\Tenant\EstablishmentResource;
use App\Models\Tenant\Catalogs\Code;
use App\Models\Tenant\Department;
use App\Models\Tenant\District;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Province;
use App\Http\Resources\Tenant\EstablishmentCollection;

class EstablishmentController extends Controller
{
    public function create()
    {
        return view('tenant.establishments.form');
    }

    public function tables()
    {
        $countries = Code::byCatalog('04');
        $departments = Department::orderBy('description')->get();
        $provinces = Province::orderBy('description')->get();
        $districts = District::orderBy('description')->get();

        return compact('countries', 'departments', 'provinces', 'districts');
    }

    public function record($id)
    {
        $record = new EstablishmentResource(Establishment::findOrFail($id));

        return $record;
    }

    public function store(EstablishmentRequest $request)
    {
        $id = $request->input('id');
        $establishment = Establishment::firstOrNew(['id' => $id]);
        $establishment->fill($request->all());
        $establishment->save();

        return [
            'success' => true,
            'message' => ($id)?'Establecimiento actualizado':'Establecimiento registrado'
        ];
    }

    public function records()
    {
        $records = Establishment::all();

        return new EstablishmentCollection($records);
    }

    public function destroy($id)
    {
        $establishment = Establishment::findOrFail($id);
        $establishment->delete();

        return [
            'success' => true,
            'message' => 'Establecimiento eliminado con éxito'
        ];
    }
}