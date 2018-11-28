<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\UnitTypeRequest;
use App\Http\Resources\Tenant\UnitTypeCollection;
use App\Http\Resources\Tenant\UnitTypeResource;
use App\Models\Tenant\Catalogs\Code;

class UnitTypeController extends Controller
{
    public function records()
    {
        $records = Code::byCatalogAll('03');

        return new UnitTypeCollection($records);
    }

    public function record($id)
    {
        $record = new UnitTypeResource(Code::findOrFail($id));

        return $record;
    }

    public function store(UnitTypeRequest $request)
    {
        $id = $request->input('id');
        $record = Code::firstOrNew(['id' => $id]);
        $parameters = $request->all();
        $parameters['id'] = $parameters['catalog_id'].str_pad($parameters['code'], 6, "0", STR_PAD_LEFT);
        $parameters['active'] = (int)$parameters['active'];
        $record->fill($parameters);
        $record->save();

        return [
            'success' => true,
            'message' => ($id)?'Unidad editada con éxito':'Unidad registrada con éxito'
        ];
    }

    public function destroy($id)
    {
        $record = Code::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Unidad eliminada con éxito'
        ];
    }
}