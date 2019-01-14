<?php
namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Facturalo\FacturaloSummary;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SummaryDocumentsRequest;
use App\Http\Requests\Tenant\SummaryRequest;
use App\Http\Resources\Tenant\DocumentCollection;
use App\Http\Resources\Tenant\SummaryCollection;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use App\Models\Tenant\Summary;
use Exception;
use Illuminate\Support\Facades\DB;

class SummaryController extends Controller
{
    use StorageDocument;

    public function __construct()
    {
        $this->middleware('transform.input:summary,web', ['only' => ['store']]);
    }

    public function index()
    {
        return view('tenant.summaries.index');
    }

    public function records()
    {
        $records = Summary::where('process_type_id', '1')
                            ->latest()
                            ->get();

        return new SummaryCollection($records);
    }

    public function documents(SummaryDocumentsRequest $request)
    {
        $company = Company::active();
        $date_of_reference = $request->input('date_of_reference');
        $documents = Document::where('date_of_issue', $request->input('date_of_reference'))
                                ->where('soap_type_id', $company->soap_type_id)
                                ->where('group_id', '02')
                                ->where('state_type_id', '01')
                                ->get();

        if(count($documents) === 0) {
            throw new Exception("No se encontraron documentos con la fecha {$date_of_reference}");
        }

        return new DocumentCollection($documents);
    }

    public function store(SummaryRequest $request)
    {
        $facturalo = new FacturaloSummary();
        $facturalo->setInputs($request->all());

        DB::connection('tenant')->transaction(function () use($facturalo) {
            $facturalo->save();
            $facturalo->createXmlAndSign();
            $facturalo->sendXml();
        });

        $summary = $facturalo->getDocument();

        return [
            'success' => true,
            'message' => "El resumen {$summary->identifier} fue creado correctamente",
        ];
    }

    public function ticket($summary_id)
    {
        $summary = Summary::find($summary_id);
        $facturalo = new FacturaloSummary();
        $facturalo->setType('summary');
        $facturalo->setDocument($summary);
        $res = $facturalo->statusTicket();

        return [
            'success' => true,
            'message' => $res['description']
        ];
    }

    public function downloadExternal($type, $external_id)
    {
        $summary = Summary::where('external_id', $external_id)->first();
        if(!$summary) {
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

        return $this->downloadStorage($summary->filename, $folder);
    }
}