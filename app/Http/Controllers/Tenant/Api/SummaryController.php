<?php
namespace App\Http\Controllers\Tenant\Api;

use App\CoreFacturalo\Facturalo;
use App\Models\Tenant\Company;
use App\Models\Tenant\Summary;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SummaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('transform.input:summary', ['only' => ['store']]);
    }

    public function store(Request $request)
    {
        $facturalo = new Facturalo(Company::active());
        $facturalo->setInputs($request->all());

        DB::transaction(function () use($facturalo) {
            $facturalo->save();
            $facturalo->createXmlAndSign();
        });

        $facturalo->sendXml($facturalo->getXmlSigned());
        $summary = $facturalo->getDocument();

        return [
            'success' => true,
            'external_id' => $summary->external_id,
            'ticket' => $summary->ticket,
        ];
    }

    public function status(Request $request)
    {
        if($request->has('external_id')) {
            $external_id = $request->input('external_id');
            $summary = Summary::where('external_id', $external_id)
                                ->where('user_id', auth()->id())
                                ->first();
            if(!$summary) {
                throw new Exception("El código {$external_id} es inválido, no se encontró resumen relacionado");
            }
        } elseif ($request->has('ticket')) {
            $ticket = $request->input('ticket');
            $summary = Summary::where('ticket', $ticket)
                                ->where('user_id', auth()->id())
                                ->first();
            if(!$summary) {
                throw new Exception("El ticket {$ticket} es inválido, no se encontró resumen relacionado");
            }
        } else {
            throw new Exception('Es requerido el código externo o ticket');
        }

        $facturalo = new Facturalo($summary->user->company);
        $facturalo->setDocument($summary);
        $res = $facturalo->statusSummary($summary->ticket);

        return [
            'success' => true,
            'data' => [
                'filename' => $summary->filename,
                'external_id' => $summary->external_id
            ],
            'links' => [
                'xml' => $summary->download_external_xml,
                'pdf' => $summary->download_external_pdf,
                'cdr' => $summary->download_external_cdr,
            ],
            'response' => $res
        ];
    }
}