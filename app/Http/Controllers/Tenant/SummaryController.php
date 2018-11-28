<?php
namespace App\Http\Controllers\Tenant;

use App\Core\Builder\CpeBuilder;
use App\Core\Builder\Documents\SummaryBuilder;
use App\Core\Builder\XmlBuilder;
use App\Core\Helpers\StorageDocument;
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

    public function index()
    {
        return view('tenant.summaries.index');
    }

    public function records()
    {
        $records = Summary::with(['state_type'])
            ->where('process_type_id', '01')
            ->latest()
            ->get();

        return new SummaryCollection($records);
    }

    public function documents(SummaryDocumentsRequest $request)
    {
        $company = Company::first();
        $documents = Document::where('date_of_issue', $request->input('date_of_reference'))
                                ->where('soap_type_id', $company->soap_type_id)
                                ->where('group_id', '02')
                                ->where('state_type_id', '01')
                                ->get();

        return new DocumentCollection($documents);
    }

    public function store(SummaryRequest $request)
    {
        DB::connection('tenant')->transaction(function () use($request) {

            foreach ($request->input('documents') as $doc) {
                $document = Document::find($doc['id']);
                $document->state_type_id = '03';
                $document->save();
            }

            $builder = new SummaryBuilder();
            $builder->register($request->all());
            $xmlBuilder = new XmlBuilder();
            $xmlBuilder->createXMLSigned($builder);
        });

        return [
            'success' => true,
            'message' => 'Se registró correctamente el resumen, por favor consulte el ticket.'
        ];
    }

    public function ticket(Summary $summary)
    {
        DB::connection('tenant')->transaction(function () use($summary) {

            $cpeBuilder = new CpeBuilder($summary);
            $res = $cpeBuilder->checkTicket($summary->ticket);

            if($res['success']) {
                $document_state_type_id = null;
                $code = $res['code'];
                if ($code === '0') {
                    $summary->update(['state_type_id' => '05']);
                    $document_state_type_id = '05';
                }
                if ($code === '99') {
                    $summary->update(['state_type_id' => '09']);
                    $document_state_type_id = '01';
                }
                if (in_array($code, ['0', '99'])) {
                    if ($res['cdrXml']) {
                        $this->uploadStorage('cdr', $res['cdrXml'], 'R-'.$summary->filename);
                        $summary->update(['has_cdr' => true]);
                    }
                }
                if ($document_state_type_id) {
                    foreach($summary->documents as $doc)
                    {
                        $doc->document()->update([
                            'state_type_id' => $document_state_type_id
                        ]);
                    }
                }
            }
        });

        return [
            'success' => true,
            'message' => 'Consulta realizada con éxito, el resúmen fue aceptado'
        ];
    }

    public function download($type, Summary $summary)
    {
        switch ($type) {
            case 'xml':
                $folder = 'signed';
                $extension = 'xml';
                $filename = $summary->filename;
                break;
            case 'cdr':
                $folder = 'cdr';
                $extension = 'xml';
                $filename = 'R-'.$summary->filename;
                break;
            default:
                throw new Exception('Tipo de archivo a descargar es inválido');
        }

        return $this->downloadStorage($folder, $filename, $extension);
    }
//
//    public function store(Request $request)
//    {
//        // set config db
//        $dbclient = new DatabaseClient;
//        $setConfigDb = $dbclient->setConfigDb();
//
//        $res = DB::transaction(function () use($request) {
//            $builder = new SummaryBuilder();
//            $builder->save($request->all());
//
//            $xmlBuilder = new XmlBuilder();
//            $xml = $xmlBuilder->createXMLSigned($builder);
//
//            $storageDocument = new StorageDocument($builder->getDocument());
//            $storageDocument->uploadXml($xml);
//            return [
//                'success' => true,
//                'message' => __('app.actions.create.success')
//            ];
//        });
//
//        return $res;
//    }
//
//    public function sendXml($id)
//    {
//        // set config db
//        $dbclient = new DatabaseClient;
//        $setConfigDb = $dbclient->setConfigDb();
//
//        $res = DB::transaction(function () use($id) {
//            $summary = Summary::find($id);
//            $storageDocument = new StorageDocument($summary);
//
//            $cpeBuilder = new CpeBuilder($summary);
//            $res = $cpeBuilder->SummarySender($summary->filename, $storageDocument->getXml());
//
//            if ($res['success']) {
//                $this->updateStateSummary($id, '03', $res['ticket']);
//                return [
//                    'success' => true,
//                    'message' => __('app.actions.create.success')
//                ];
//            }
//            $this->updateStateSummary($id, '09');
//            return $res;
//        });
//
//        return $res;
//    }
//
//    public function checkTicket($id)
//    {
//        // set config db
//        $dbclient = new DatabaseClient;
//        $setConfigDb = $dbclient->setConfigDb();
//
//        $res = DB::transaction(function () use($id) {
//            $summary = Summary::with(['documents'])->find($id);
//            $storageDocument = new StorageDocument($summary);
//
//            $cpeBuilder = new CpeBuilder($summary);
//            $res = $cpeBuilder->checkTicket($summary->ticket);
//
//            if($res['success']) {
//                $storageDocument->uploadCdr($res['cdrXml']);
//                $this->updateStateSummary($id, '05');
//
//                foreach($summary->documents as $doc)
//                {
//                    $document = Document::find($doc->document_id);
//                    $document->state_type_id = ($summary->process_type_id === 1)?'05':'11';
//                    $document->save();
//                }
//
//                return [
//                    'success' => true,
//                    'message' => __('app.actions.create.success')
//                ];
//            }
//            $this->updateStateSummary($id, '09');
//            return $res;
//        });
//
//        return $res;
//    }
//
//    private function updateStateSummary($summary_id, $state_type_id, $ticket = null)
//    {
//        // set config db
//        $dbclient = new DatabaseClient;
//        $setConfigDb = $dbclient->setConfigDb();
//
//        $summary = Summary::find($summary_id);
//        $summary->state_type_id = $state_type_id;
//        if ($ticket) $summary->ticket = $ticket;
//        $summary->save();
//    }
//
//    public function download($type, $id)
//    {
//        // set config db
//        $dbclient = new DatabaseClient;
//        $setConfigDb = $dbclient->setConfigDb();
//
//        $summary = Summary::find($id);
//        $storageDocument = new StorageDocument($summary);
//        if ($type === 'xml') {
//            return $storageDocument->downloadXml();
//        }
//        if ($type === 'cdr') {
//            return $storageDocument->downloadCdr();
//        }
//        return false;
//    }
}