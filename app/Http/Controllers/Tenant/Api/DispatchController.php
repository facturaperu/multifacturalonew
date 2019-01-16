<?php
namespace App\Http\Controllers\Tenant\Api;

use App\CoreFacturalo\Facturalo\FacturaloDispatch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('transform.api:dispatch', ['only' => ['store']]);
    }

    public function store(Request $request)
    {
        $facturalo = new FacturaloDispatch();
        $facturalo->setInputs($request->all());

        DB::connection('tenant')->transaction(function () use($facturalo) {
            $facturalo->save();
            $facturalo->createXmlAndSign();
            $facturalo->createPdf();
            $facturalo->sendXml();
        });
        $document = $facturalo->getDocument();
        $res = $facturalo->getResponse();
//        $send = ($document->group_id === '01')?true:false;
//
//        $configuration = Configuration::first();
//
//        $send = $send && (bool)$configuration->send_auto;
//        $res = ($send)?$facturalo->sendXml():[];
        $send = true;


        return [
            'success' => true,
            'data' => [
                'id' => $document->id,
                'number' => $document->number_full,
            ],
            'links' => [
                'xml' => $document->download_external_xml,
                'pdf' => $document->download_external_pdf,
                'cdr' => ($send)?$document->download_external_cdr:'',
            ],
            'response' => $res
        ];
//
//
//        if(!$request->input('success')) {
//            return [
//                'success' => false,
//                'message' => $request->input('message'),
//                'code' => $request->input('code')
//            ];
//        }
//
//        $facturalo = new Facturalo(Company::active());
//        $facturalo->setInputs($request->all());
//
//        DB::connection('tenant')->transaction(function () use($facturalo) {
//            $facturalo->save();
//            $facturalo->createXmlAndSign();
//            $facturalo->createPdf();
//        });
//
//        $send = ($request->input('document.group_id') === '01')?true:false;
//        $send = $send && $request->input('actions.send_xml_signed');
//        $res = ($send)?$facturalo->sendXml($facturalo->getXmlSigned()):[];
//
//        $document = $facturalo->getDocument();
//        return [
//            'success' => true,
//            'data' => [
//                'number' => $document->number_full,
//                'filename' => $document->filename,
//                'external_id' => $document->external_id,
//                'number_to_letter' => $document->number_to_letter,
//                'hash' => $document->hash,
//                'qr' => $document->qr,
//            ],
//            'links' => [
//                'xml' => $document->download_external_xml,
//                'pdf' => $document->download_external_pdf,
//                'cdr' => ($send)?$document->download_external_cdr:'',
//            ],
//            'response' => $res
//        ];
    }
}