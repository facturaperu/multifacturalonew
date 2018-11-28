<?php
namespace App\Http\Controllers\Tenant\Api;

use App\Core\Builder\Documents\InvoiceBuilder;
use App\Core\Builder\Documents\NoteCreditBuilder;
use App\Core\Builder\Documents\NoteDebitBuilder;
use App\Core\Builder\XmlBuilder;
use App\Http\Controllers\Controller;
use App\Mail\Tenant\DocumentEmail;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('transform.input', ['only' => ['store']]);
    }

    public function store(Request $request)
    {
        $document_type_code = ($request->has('document'))?$request->input('document.document_type_code'):
                                                          $request->input('document_type_code');

        $document = DB::connection('tenant')->transaction(function () use($request, $document_type_code) {
            switch ($document_type_code) {
                case '01':
                case '03':
                    $builder = new InvoiceBuilder();
                    break;
                case '07':
                    $builder = new NoteCreditBuilder();
                    break;
                case '08':
                    $builder = new NoteDebitBuilder();
                    break;
                default:
                    throw new Exception('Tipo de documento ingresado es inválido');
            }

            $builder->save($request->all());
            $xmlBuilder = new XmlBuilder();
            $xmlBuilder->createXMLSigned($builder);
            $document = $builder->getDocument();

            return $document;
        });

        $send_email = $request->input('send_email');

        if($send_email) {
            $send_email = $this->email($document->id);
        }

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