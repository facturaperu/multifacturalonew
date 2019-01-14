<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Resources\Tenant\Catalogs\CodeResource;
use App\Models\Tenant\Catalogs\UnitType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\UnitTypeRequest;
use App\Http\Resources\Tenant\UnitTypeCollection;
use App\Http\Resources\Tenant\UnitTypeResource;
use App\Models\Tenant\Code;

class UnitTypeController extends Controller
{
    protected $catalog_id = '03';

    public function records()
    {
        $records = Code::whereCatalog($this->catalog_id)->get();

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
        $unit_type = Code::firstOrNew(['id' => $id]);
        $unit_type->fill($request->all());
        if(!$id) {
            $unit_type->catalog_id = $this->catalog_id;
            $unit_type->id = $this->catalog_id.$unit_type->code;
        }
        $unit_type->save();

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