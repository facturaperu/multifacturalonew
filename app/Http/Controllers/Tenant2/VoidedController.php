<?php

namespace App\Http\Controllers\Tenant;

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
        $this->middleware('transform.input:voided,web', ['only' => ['store']]);
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
                        ->where('process_type_id', '3');

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
        $voided = Voided::find($voided_id);
        $facturalo = new FacturaloVoided();
        $facturalo->setType('voided');
        $facturalo->setDocument($voided);
        $res = $facturalo->statusTicket();

        return [
            'success' => true,
            'message' => $res['description']
        ];
    }

    public function downloadExternal($type, $external_id)
    {
        $voided = Voided::where('external_id', $external_id)->first();
        if(!$voided) {
            throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");
        }

        switch ($type) {
            case 'pdf':
                $folder = 'pdf';
                break;
            case 'xml':
                $folder = 'signed';
                break;
            case 'cdr':
                $folder = 'cdr';
                break;
            default:
                throw new Exception('Tipo de archivo a descargar es inválido');
        }

        return $this->downloadStorage($voided->filename, $folder);
    }
}