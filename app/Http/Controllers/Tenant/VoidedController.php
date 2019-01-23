<?php

namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Facturalo;
use App\CoreFacturalo\Facturalo\FacturaloVoided;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\VoidedCollection;
use App\Models\Tenant\Voided;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoidedController extends Controller
{
    use StorageDocument;

    public function __construct()
    {
        $this->middleware('transform.web:voided', ['only' => ['store']]);
    }

    public function index()
    {
        return view('tenant.voided.index');
    }

    public function columns()
    {
        return [
            'number' => 'Número'
        ];
    }

    public function records(Request $request)
    {
        $voided = DB::connection('tenant')
                    ->table('voided')
                    ->select(DB::raw("id, external_id, date_of_reference, date_of_issue, ticket, identifier, state_type_id, 'voided' AS 'type'"));

        $summaries = DB::connection('tenant')
                        ->table('summaries')
                        ->select(DB::raw("id, external_id, date_of_reference, date_of_issue, ticket, identifier, state_type_id, 'summaries' AS 'type'"))
                        ->where('summary_status_type_id', '3');

        return new VoidedCollection($voided->union($summaries)->paginate(env('ITEMS_PER_PAGE', 20)));
    }

    public function store(Request $request)
    {
        $facturalo = new FacturaloVoided();
        $facturalo->setInputs($request->all());

        DB::connection('tenant')->transaction(function () use($facturalo) {
            $facturalo->save();
            $facturalo->createXmlAndSign();
            $facturalo->sendXml();
        });

        $voided = $facturalo->getDocument();

        return [
            'success' => true,
            'message' => "La anulación {$voided->identifier} fue creado correctamente",
        ];
    }

    public function ticket($voided_id)
    {
        $document = Voided::find($voided_id);

        $fact = DB::connection('tenant')->transaction(function () use($document) {
            $facturalo = new Facturalo();
            $facturalo->setDocument($document);
            $facturalo->statusSummary($document->ticket);
            return $facturalo;
        });

        $response = $fact->getResponse();

        return [
            'success' => true,
            'message' => $response['description'],
        ];
    }
}