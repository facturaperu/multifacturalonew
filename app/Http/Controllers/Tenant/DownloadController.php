<?php
namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use Exception;

class DownloadController extends Controller
{
    use StorageDocument;

    public function downloadExternal($model, $type, $external_id)
    {
        $model = "App\\Models\\Tenant\\".ucfirst($model);
        $document = $model::where('external_id', $external_id)->first();
        if(!$document) {
            throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");
        }
        return $this->download($type, $document);
    }

    public function download($type, $document)
    {
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
}