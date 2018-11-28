<?php
namespace App\Http\Controllers\Tenant\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Catalogs\CodeRequest;
use App\Http\Resources\Tenant\Catalogs\CodeCollection;
use App\Http\Resources\Tenant\Catalogs\CodeResource;
use App\Models\Tenant\Catalogs\Code;

class CodeController extends Controller
{
    public function records()
    {
        $records = Code::byCatalogAll('03');

        return new CodeCollection($records);
    }

    public function tables()
    {
        $options = [
            ['id' => 1, 'description' => 'Si'],
            ['id' => 0, 'description' => 'No']
        ];

        return compact('options');
    }

    public function record($id)
    {
        $record = new CodeResource(Code::findOrFail($id));

        return $record;
    }

    public function store(CodeRequest $request)
    {
        $id = $request->input('id');
        $record = Code::firstOrNew(['id' => $id]);
        $parameters = $request->all();
        $parameters['id'] = $parameters['catalog_id'].str_pad($parameters['code'], 6, "0", STR_PAD_LEFT);
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