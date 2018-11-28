<?php

namespace App\Mail\Tenant;

use App\Core\Helpers\StorageDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DocumentEmail extends Mailable
{
    use Queueable, SerializesModels;
    use StorageDocument;

    public $company;
    public $document;

    public function __construct($company, $document)
    {
        $this->company = $company;
        $this->document = $document;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = $this->getStorage('pdf', $this->document->filename, 'pdf');
        $xml = $this->getStorage('signed', $this->document->filename);

        return $this->subject('Envio de Comprobante de Pago Electrónico')
                    ->from(env('MAIL_USERNAME'), 'Comprobante electrónico')
                    ->view('tenant.templates.email.document')
                    ->attachData($pdf, $this->document->filename.'.pdf')
                    ->attachData($xml, $this->document->filename.'.xml');
    }
}