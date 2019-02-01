<?php
namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use App\CoreFacturalo\Template;
use App\Models\Tenant\Company;
use Mpdf\Mpdf;
use Exception;

class DownloadController extends Controller
{
    use StorageDocument;
    
    public function downloadExternal($model, $type, $external_id, $format = null) {
        $model = "App\\Models\\Tenant\\".ucfirst($model);
        $document = $model::where('external_id', $external_id)->first();
        
        if (!$document) throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");
        
        if ($format != null) $this->reloadPDF($document, $format);
        
        return $this->download($type, $document);
    }
    
    public function download($type, $document) {
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
        
        return $this->downloadStorage($document->filename, $folder);
    }
    
    public function toPrint($model, $external_id, $format = null) {
        $model = "App\\Models\\Tenant\\".ucfirst($model);
        $document = $model::where('external_id', $external_id)->first();
        
        if (!$document) throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");
        
        if ($format != null) $this->reloadPDF($document, $format);
        
        $temp = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($temp, $this->getStorage($document->filename, 'pdf'));
        
        return response()->file($temp);
    }
    
    /**
     * Reload PDF
     * @param  ModelTenant $document
     * @param  string $format
     * @return void
     */
    private function reloadPDF($document, $format) {
        $company = Company::active();
        $template = new Template();
        $pdf = new Mpdf();
        
        $html = $template->pdf('invoice', $company, $document, $format);
        
        if ($format === 'ticket') {
            $quantity_rows = count($document->items);
            $pdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => [78, 220 + ($quantity_rows * 10)],
                'margin_top' => 2,
                'margin_right' => 5,
                'margin_bottom' => 0,
                'margin_left' => 5
            ]);
        }
        
        $pdf->WriteHTML($html);
        $html_footer = $template->pdfFooter();
        $pdf->SetHTMLFooter($html_footer);
        
        $this->uploadStorage($document->filename, $pdf->output('', 'S'), 'pdf');
    }
}
