<?php

namespace App\Core\Builder;

use App\Core\Helpers\StorageDocument;
use App\Core\WS\Signed\HashXml;
use App\Core\WS\Signed\SignedXml;
use App\Core\WS\Validator\SchemaValidator;
use App\Models\Tenant\Company;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Milon\Barcode\DNS2D;

class XmlBuilder
{
    use StorageDocument;

    protected $company;
    protected $document;
    protected $signedXML;
    protected $hash;
    protected $isDocument = false;

    public function __construct()
    {
        $this->company = Company::first();
    }

    public function createXMLSigned($builder)
    {
        $this->document = $builder->getDocument();
        $unsignedXML = $this->format_xml($this->viewXML($builder));
        $this->uploadStorage('unsigned', $unsignedXML, $this->document->filename);
        $signer = new SignedXml();
        $signer->setCertificateFromFile($this->pathFilenameCertificate());

        $this->signedXML = $signer->signXml($unsignedXML);
        $this->validate();
        $this->uploadStorage('signed', $this->signedXML, $this->document->filename);

        if ($this->isDocument) {
            $this->updateDocument([
                'hash' => $this->getHash(),
                'qr' => $this->getQr(),
                'has_xml' => true,
            ]);
            $this->createPdf();
            $this->updateDocument(['has_pdf' => true]);
            if ($this->sendXml()) {
                $this->sendXmlCdr($this->document, $this->signedXML);
//                $cpeBuilder = new CpeBuilder($this->document);
//                $res = $cpeBuilder->BillSender($this->document->filename, $this->signedXML);
//                if ($res['success']) {
//                    $code = $res['cdrResponse']['code'];
//                    if ($code === '0') {
//                        $this->updateDocument(['state_type_id' => '05']);
//                    }
//                    if ($code === '98') {
//                        $this->updateDocument(['state_type_id' => '03']);
//                    }
//                    if ($code === '99') {
//                        $this->updateDocument(['state_type_id' => '09']);
//                    }
//                    if (in_array($code, ['0', '99'])) {
//                        if ($res['cdrXml']) {
//                            $this->uploadStorage('cdr', $res['cdrXml'], 'R-'.$this->document->filename);
//                            $this->updateDocument(['has_cdr' => true]);
//                        }
//                    }
//                }
            }
        } else {
            $cpeBuilder = new CpeBuilder($this->document);
            $res = $cpeBuilder->SummarySender($this->document->filename, $this->signedXML);
            if ($res['success']) {
                $this->updateDocument([
                    'state_type_id' => '03',
                    'has_ticket' => true,
                    'ticket' => $res['ticket'],
                ]);
            }
        }

        return true;
    }

    public function sendXmlCdr($document, $signedXML = null)
    {
        $this->document = $document;

        if (is_null($signedXML)) {
          $signedXML = $this->getStorage('signed', $this->document->filename);
        }

        $this->signedXML = $signedXML;
        $cpeBuilder = new CpeBuilder($this->document);
        $res = $cpeBuilder->BillSender($this->document->filename, $this->signedXML);
        if ($res['success']) {
            $code = $res['cdrResponse']['code'];
            if ($code === '0') {
                $this->updateDocument(['state_type_id' => '05']);
            }
            if ($code === '98') {
                $this->updateDocument(['state_type_id' => '03']);
            }
            if ($code === '99') {
                $this->updateDocument(['state_type_id' => '09']);
            }
            if ((int)$code > 2000 && (int)$code < 4000) {
                $this->updateDocument(['state_type_id' => '09']);
            }
//            if ($code === '99') {
//                $this->updateDocument(['state_type_id' => '09']);
//            }
            //if (in_array($code, ['0', '99'])) {
            if ($code !== '98') {
                if ($res['cdrXml']) {
                    $this->uploadStorage('cdr', $res['cdrXml'], 'R-'.$this->document->filename);
                    $this->updateDocument(['has_cdr' => true]);
                }
            }
            return true;
        }
        return false;
    }

    private function pathFilenameCertificate()
    {
        if ($this->document->soap_type_id === '01') {
            return app_path('Core'.DIRECTORY_SEPARATOR.'Certificates'.DIRECTORY_SEPARATOR.'demo.pem');
        }

        return storage_path('app'.DIRECTORY_SEPARATOR.'certificates'.DIRECTORY_SEPARATOR.$this->company->certificate);
    }

    private function viewXML($builder)
    {
        $classBuilder = get_class($builder);
        $view = null;
        switch (class_basename($classBuilder)) {
            case "InvoiceBuilder":
                $this->isDocument = true;
                $view = 'invoice';
                break;
            case "NoteCreditBuilder":
                $this->isDocument = true;
                $view = 'note_credit';
                break;
            case "NoteDebitBuilder":
                $this->isDocument = true;
                $view = 'note_debit';
                break;
            case "SummaryBuilder":
                $this->isDocument = false;
                $view = 'summary';
                break;
            case "VoidedBuilder":
                $this->isDocument = false;
                $view = 'voided';
                break;
        }

        return view('tenant.templates.xml.'.$this->document->ubl_version.'.'.$view,
            ['document' => $this->document,
                'company' => $this->company])->render();
    }

    private function format_xml($xml, $formatOutput = TRUE, $declaration = TRUE)
    {
        $sxe = ($xml instanceof \SimpleXMLElement) ? $xml : simplexml_load_string($xml);
        $domElement = dom_import_simplexml($sxe);
        $domDocument = $domElement->ownerDocument;
        $domDocument->preserveWhiteSpace = false;
        $domDocument->formatOutput = (bool)$formatOutput;
        $domDocument->loadXML($sxe->asXML(), LIBXML_NOBLANKS);

        return (bool)$declaration ? $domDocument->saveXML() : $domDocument->saveXML($domDocument->documentElement);
    }

    private function validate()
    {
        $ubl_version = ($this->document->ubl_version === 'v20')?'2.0':'2.1';
        $validator = new SchemaValidator();
        $validator->setVersion($ubl_version);

        if (!$validator->validate($this->signedXML)) {
            throw new Exception($validator->getMessage());
        }

        return true;
    }

    public function getHash()
    {
        $helper = new HashXml();

        return $helper->getHashSign($this->signedXML);
    }

    public function getQr()
    {
        $customer = $this->document->customer;
        $arr = join('|', [
            $this->company->number,
            $this->document->document_type_code,
            $this->document->series,
            $this->document->number,
            $this->document->total_igv,
            $this->document->total,
            $this->document->date_of_issue->format('Y-m-d'),
            $customer->identity_document_type_code,
            $customer->number,
            $this->getHash()
        ]);

        return DNS2D::getBarcodePNG($arr, "QRCODE", 3 , 3);
    }

    public function updateDocument($data)
    {
        $this->document->update($data);

        return $this->document;
    }

    public function createPdf()
    {
        if (in_array($this->document->document_type_code, ['01', '03'])) {
            $document_base = $this->document->invoice;
        } else {
            $document_base = $this->document->note;
        }

        //$format_pdf = isset(request()->input('document')['optional']['format_pdf']) ? request()->input('document')['optional']['format_pdf']:request()->input('format_pdf');

        $format_pdf = $this->document->optional->format_pdf;

        $pdf = PDF::loadView('tenant.templates.pdf.'.$format_pdf, ['document' => $this->document,
            'document_base' => $document_base,
            'company' => $this->company]);
        if ($format_pdf === 'ticket') {
            $additional = 0;
            if (in_array($this->document->document_type_code, ['07', '08'])) {
                $additional = 30;

            }
            if ($this->document->optional->box_number) {
                $additional += 10;
            }
            if ($this->document->optional->salesman) {
                $additional += 10;
            }

            $quantity = count($this->document->details);
            $pdf->setPaper([0, 0, 210, (380 + $additional + ($quantity * 10)) ]);
        }

        $this->uploadStorage('pdf', $pdf->output(), $this->document->filename, 'pdf');

        return true;
    }

    public function sendXml()
    {
        if ($this->document->group_id === '01') {
            return true;
        }
        return false;
    }
}
