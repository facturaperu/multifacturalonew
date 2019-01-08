<?php

namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\FacturaloSummary;
use App\CoreFacturalo\FacturaloVoided;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\VoidedCollection;
use App\Models\Tenant\Document;
use App\Models\Tenant\Summary;
use App\Models\Tenant\Voided;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoidedController extends Controller
{
    use StorageDocument;

    public function __construct()
    {
        $this->middleware('transform.input:voided,true', ['only' => ['store']]);
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
                        ->select(DB::raw("id, external_id, date_of_reference, date_of_issue, ticket, identifier, state_type_id, 'summary' AS 'type'"))
                        ->where('process_type_id', '3');

//        $voided = Voided::all();
//        $summaries = Summary::all();

//        return $voided->union($summaries)->paginate(20);

//        Voided::latest()->get()->transform(function($row) {
//            return $this->toArrayRow($row, 'voided');
//        })->toArray();

//        $summaries = Summary::where('process_type_id', '3')->latest()->get()->transform(function($row) {
//            return $this->toArrayRow($row, 'summaries');
//        })->toArray();
//
//        $all = array_merge($voided, $summaries);
//
//        return collect($all)->sortBy('date_of_issue');
//
//        $records = Document::where($request->column, 'like', "%{$request->value}%")
//                            ->whereIn('state_type_id', ['11', '13'])
//                            ->latest();
//
        return new VoidedCollection($voided->union($summaries)->paginate(env('ITEMS_PER_PAGE', 20)));
//
////        $records = Document::whereIn('state_type_id', ['11', '13'])
////                            ->latest()
////                            ->get();
//
////        return new VoidedCollection($records);
    }
//    public function records(Request $request)
//    {
//        $voided = Voided::latest()->get()->transform(function($row) {
//            return $this->toArrayRow($row, 'voided');
//        })->toArray();
//
//        $summaries = Summary::where('process_type_id', '3')->latest()->get()->transform(function($row) {
//            return $this->toArrayRow($row, 'summaries');
//        })->toArray();
//
//        $all = array_merge($voided, $summaries);
//
//        return collect($all)->sortBy('date_of_issue');
////
////        $records = Document::where($request->column, 'like', "%{$request->value}%")
////                            ->whereIn('state_type_id', ['11', '13'])
////                            ->latest();
////
////        return new VoidedCollection($records->paginate(env('ITEMS_PER_PAGE', 20)));
////
//////        $records = Document::whereIn('state_type_id', ['11', '13'])
//////                            ->latest()
//////                            ->get();
////
//////        return new VoidedCollection($records);
//    }

    private function toArrayRow($row, $type)
    {
        $btn_ticket = true;
        $has_xml = true;
        $has_pdf = true;
        $has_cdr = false;

        if($row->state_type_id === '11') {
            $btn_ticket = false;
            $has_cdr = true;
        }

        return [
            'type' => $type,
            'id' => $row->id,
            'ticket' => $row->ticket,
            'identifier' => $row->identifier,
            'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
            'date_of_reference' => $row->date_of_reference->format('Y-m-d'),
            'state_type_id' => $row->state_type_id,
            'state_type_description' => $row->state_type->description,
            'has_xml' => $has_xml,
            'has_pdf' => $has_pdf,
            'has_cdr' => $has_cdr,
            'download_xml' => $row->download_external_xml,
            'download_pdf' => $row->download_external_pdf,
            'download_cdr' => $row->download_external_cdr,
            'btn_ticket' => $btn_ticket,
            'created_at' => $row->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
        ];
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

    public function download($type, Voided $voided)
    {
        switch ($type) {
            case 'xml':
                $folder = 'signed';
                $extension = 'xml';
                $filename = $voided->filename;
                break;
            case 'cdr':
                $folder = 'cdr';
                $extension = 'xml';
                $filename = 'R-'.$voided->filename;
                break;
            default:
                throw new Exception('Tipo de archivo a descargar es inválido');
        }

        return $this->downloadStorage($folder, $filename, $extension);
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
//
//    public function ticket($voided_id, $group_id)
//    {
//        $voided = ($group_id === '01')?Voided::find($voided_id):Summary::find($voided_id);
//        DB::connection('tenant')->transaction(function () use($voided) {
//
//            $cpeBuilder = new CpeBuilder($voided);
//            $res = $cpeBuilder->checkTicket($voided->ticket);
//
//            if($res['success']) {
//                $document_state_type_id = null;
//                $code = $res['code'];
//                if ($code === '0') {
//                    $voided->update(['state_type_id' => '05']);
//                    $document_state_type_id = '11';
//                }
//                if ($code === '99') {
//                    $voided->update(['state_type_id' => '09']);
//                    $document_state_type_id = '05';
//                }
//                if (in_array($code, ['0', '99'])) {
//                    if ($res['cdrXml']) {
//                        $this->uploadStorage('cdr', $res['cdrXml'], 'R-'.$voided->filename);
//                        $voided->update(['has_cdr' => true]);
//                    }
//                }
//                if ($document_state_type_id) {
//                    foreach($voided->documents as $doc)
//                    {
//                        $doc->document()->update([
//                            'state_type_id' => $document_state_type_id
//                        ]);
//                    }
//                }
//            }
//        });
//
//        return [
//            'success' => true,
//            'message' => 'Consulta realizada con éxito, la anulación fue aceptada'
//        ];
//    }

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