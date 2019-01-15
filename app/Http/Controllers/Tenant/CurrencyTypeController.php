<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CurrencyTypeRequest;
use App\Http\Resources\Tenant\CurrencyTypeCollection;
use App\Http\Resources\Tenant\CurrencyTypeResource;
use App\Models\Tenant\Catalogs\Code;

class CurrencyTypeController extends Controller
{
    protected $catalog_id = '02';

    public function records()
    {
        $records = Code::whereCatalog($this->catalog_id)->get();

        return new CurrencyTypeCollection($records);
    }

    public function record($id)
    {
        $record = new CurrencyTypeResource(Code::findOrFail($id));

        return $record;
    }

    public function store(CurrencyTypeRequest $request)
    {
        $id = $request->input('id');
        $currency_type = Code::firstOrNew(['id' => $id]);
        $currency_type->fill($request->all());
        if(!$id) {
            $currency_type->catalog_id = $this->catalog_id;
            $currency_type->id = $this->catalog_id.$currency_type->code;
        }
        $currency_type->save();

        return [
            'success' => true,
            'message' => ($id)?'Moneda editada con éxito':'Moneda registrada con éxito'
        ];
    }

    public function destroy($id)
    {
        $currency_type = Code::findOrFail($id);
        $currency_type->delete();

        return [
            'success' => true,
            'message' => 'Moneda eliminada con éxito'
        ];
    }
}