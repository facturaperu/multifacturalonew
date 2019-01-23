<?php
namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Facturalo;
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
        $this->middleware('input.request:summary,web', ['only' => ['store']]);
    }

    public function index()
    {
        return view('tenant.summaries.index');
    }

    public function records()
    {
        $records = Summary::where('summary_status_type_id', '1')
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
        $fact = DB::connection('tenant')->transaction(function () use($request) {
            $facturalo = new Facturalo();
            $facturalo->save($request->all());
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            return $facturalo;
        });

        $fact->senderXmlSignedSummary();
        $document = $fact->getDocument();
        //$response = $fact->getResponse();

        return [
            'success' => true,
            'message' => "El resumen {$document->identifier} fue creado correctamente",
        ];
    }

    public function status($summary_id)
    {
        $document = Summary::find($summary_id);

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