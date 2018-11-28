<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SeriesRequest;
use App\Http\Resources\Tenant\SeriesCollection;
use App\Models\Tenant\Catalogs\Code;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Series;

class SeriesController extends Controller
{
    public function create()
    {
        return view('tenant.series.form');
    }

    public function records()
    {
        $records = Series::all();

        return new SeriesCollection($records);
    }

    public function tables()
    {
        $establishment = Establishment::first();
        $document_types = Code::byCatalogOnlyCodes('01', ['01', '03', '07', '08']);

        return compact('document_types', 'establishment');
    }

    public function store(SeriesRequest $request)
    {
        $id = $request->input('id');
        $series = Series::firstOrNew(['id' => $id]);
        $series->fill($request->all());
        $series->save();

        return [
            'success' => true,
            'message' => ($id)?'Serie editada con éxito':'Serie registrada con éxito'
        ];
    }

    public function destroy($id)
    {
        $item = Series::findOrFail($id);
        $item->delete();

        return [
            'success' => true,
            'message' => 'Serie eliminada con éxito'
        ];
    }
}