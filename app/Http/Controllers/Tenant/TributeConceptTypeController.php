<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Catalogs\TributeConceptType;
use App\Http\Resources\Tenant\TributeConceptTypeResource;
use App\Http\Requests\Tenant\TributeConceptTypeRequest;

class TributeConceptTypeController extends Controller
{
    public function records()
    { 
        $records = TributeConceptType::get();

        return $records;
    }

    public function record($id)
    {
        $record = new TributeConceptTypeResource(TributeConceptType::findOrFail($id));

        return $record;
    }

    public function store(TributeConceptTypeRequest $request)
    {  
        
        $tribute_concept_type = TributeConceptType::create($request->all()); 

        return [
            'success' => true,
            'message' =>'Atributo registrado con éxito'
        ];
    }

    public function update(TributeConceptTypeRequest $request)
    {

        $tribute_concept_type = TributeConceptType::findOrFail($request->input('id'));
        $tribute_concept_type->fill($request->all());       
        $tribute_concept_type->save();

        return [
            'success' => true,
            'message' => 'Atributo editado con éxito'
        ];
    }


    public function destroy($id)
    {
        $record = TributeConceptType::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Atributo eliminado con éxito'
        ];
    }
}
