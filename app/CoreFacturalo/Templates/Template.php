<?php

namespace App\CoreFacturalo\Templates;

class Template
{
    public function pdf($template, $company, $document)
    {
        if($template === 'credit' || $template === 'debit') {
            $template = 'note';
        }
        $template = 'pdf.'.$template.'_'.$document->optional->format_pdf;
        return self::render($template, $company, $document);
    }

    public function xml($template, $company, $document)
    {
        return self::render('xml.'.$template, $company, $document);
    }

    private function render($view, $company, $document)
    {
        view()->addLocation(__DIR__);
        return view($view, compact('company', 'document'))->render();
    }
}