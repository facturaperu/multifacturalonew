<?php
namespace App\Http\Controllers\Tenant\Api;

use App\CoreFacturalo\Core\FacturaloDocument;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('transform.api:document', ['only' => ['store']]);
    }

    public function store(Request $request)
    {
        $facturalo = new FacturaloDocument();
        $facturalo->setInputs($request->all());

        DB::connection('tenant')->transaction(function () use($facturalo) {
//            $facturalo->save();
            $facturalo->createXmlAndSign();
//            $facturalo->createPdf();
            $facturalo->save();
        });

        $inputs = $facturalo->getInputs();

//        $send = ($document->group_id === '01')?true:false;
//
//        $configuration = Configuration::first();
//
//        $send = $send && (bool)$configuration->send_auto;
//        $res = ($send)?$facturalo->sendXml():[];

        return [
            'success' => true,
            'data' => [
                'number' => $inputs['series'].'-'.$inputs['number'],
                'filename' => $inputs['filename'],
                'external_id' => $inputs['external_id'],
                'number_to_letter' => '',//$document->number_to_letter,
                'hash' => $inputs['hash'],
                'qr' => $inputs['qr'],
            ],
            'links' => [
                'xml' => '',//$document->download_external_xml,
                'pdf' => '',//$document->download_external_pdf,
                'cdr' => '',//($send)?$document->download_external_cdr:'',
            ],
            'response' => ''//$res
        ];
    }
}