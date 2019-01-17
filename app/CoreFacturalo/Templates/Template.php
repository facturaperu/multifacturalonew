<?php

namespace App\CoreFacturalo\Templates;

class Template
{
    public function pdf($template, $company, $data, $format_pdf)
    {
        if($template === 'credit' || $template === 'debit') {
            $template = 'note';
        }
        $template = 'pdf.'.$template.'_'.$format_pdf;
        return self::render($template, $company, $data);
    }

    public function xml($template, $company, $data)
    {
        return self::render('xml.'.$template, $company, $data);
    }

    private function render($view, $company, $data)
    {
        view()->addLocation(__DIR__);
        return view($view, compact('company', 'data'))->render();
    }
}