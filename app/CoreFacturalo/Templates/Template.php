<?php

namespace App\CoreFacturalo\Templates;

class Template
{
    public function pdf($template, $company, $document, $format_pdf)
    {
        if($template === 'credit' || $template === 'debit') {
            $template = 'note';
        }
        $template = 'pdf.'.$template.'_'.$format_pdf;
        return self::render($template, $company, $document);
    }

    public function xml($template, $company, $inputs)
    {
        return self::render('xml.'.$template, $company, $inputs);
    }

    private function render($view, $company, $inputs)
    {
        view()->addLocation(__DIR__);
        return view($view, compact('company', 'inputs'))->render();
    }
}