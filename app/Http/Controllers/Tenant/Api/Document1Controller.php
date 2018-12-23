<?php
namespace App\Http\Controllers\Tenant\Api;

use App\CoreBuilder\Documents\InvoiceBuilder;
use App\CoreBuilder\Documents\NoteCreditBuilder;
use App\CoreBuilder\Documents\NoteDebitBuilder;
use App\CoreBuilder\Helpers\HashXml;
use App\CoreBuilder\Util;
use App\Http\Controllers\Controller;
use App\Mail\Tenant\DocumentEmail;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Document1Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('transform.input', ['only' => ['store']]);
    }

    public function store(Request $request)
    {
        if(!$request->input('success')) {
            return $request->all();
        }

        $document_type_id = ($request->has('document'))?$request->input('document.document_type_id'):
                                                        $request->input('document_type_id');

        DB::connection('tenant')->beginTransaction();
        try {
            if (in_array($document_type_id, ['01', '03'])) {
                $document_builder = new InvoiceBuilder();
            } elseif ($document_type_id === '07') {
                $document_builder = new NoteCreditBuilder();
            } else {
                $document_builder = new NoteDebitBuilder();
            }
            $document_builder->save($request->all());

            $util = new Util();
            $cpeUtil = $util->getCpeBuilder();
            $xmlSigned = $cpeUtil->getXmlSigned($document_builder);
            $res = $cpeUtil->sendXml(get_class($document_builder), $document_builder->getDocument()->filename, $xmlSigned);

            if(!$res->isSuccess()) {
                throw new Exception("Code: {$res->getError()->getCode()}; Description: {$res->getError()->getMessage()}");
            } else {
                $document = $document_builder->getDocument();
                $hashXml = new HashXml();
                $document->hash = $hashXml->getHashSign($xmlSigned);
                $document->qr = $util->getQr($document->id);
                $document->save();

                $util->createPdf($document->id);

                $actions = $request->input('actions');
                $send_email = false;
                if($actions['send_email']) {
                    $send_email = $this->email($document->id);
                }
                DB::connection('tenant')->commit();
                return [
                    'success' => true,
                    'data' => [
                        'id' => $document->id,
                        'number' => $document->number_full,
                        'hash' => $document->hash,
                        'qr' => $document->qr,
                        'filename' => $document->filename,
                        'external_id' => $document->external_id,
                        'number_to_letter' => $document->number_to_letter,
                        'link_xml' => $document->download_external_xml,
                        'link_pdf' => $document->download_external_pdf,
                        'link_cdr' => $document->download_external_cdr,
                    ],
                    'send_email' => $send_email,
                ];
            }
        } catch (Exception $e) {
            DB::connection('tenant')->rollback();
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => "File: {$e->getFile()}, Line: {$e->getLine()}"
            ];
        }
    }

    public function email($document_id)
    {
        $company = Company::first();
        $document = Document::find($document_id);
        $customer_email = $document->customer->email;
        if($customer_email) {
            try {
                Mail::to($customer_email)->send(new DocumentEmail($company, $document));
                return true;
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }

        return false;
    }


}