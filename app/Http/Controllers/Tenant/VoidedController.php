<?php

namespace App\Http\Controllers\Tenant;

use App\Core\Builder\CpeBuilder;
use App\Core\Helpers\StorageDocument;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Summary;
use App\Models\Tenant\Voided;
use Exception;
use Illuminate\Support\Facades\DB;

class VoidedController extends Controller
{
    use StorageDocument;

    public function download($type, Voided $voided)
    {
        switch ($type) {
            case 'xml':
                $folder = 'signed';
                $extension = 'xml';
                $filename = $voided->filename;
                break;
            case 'cdr':
                $folder = 'cdr';
                $extension = 'xml';
                $filename = 'R-'.$voided->filename;
                break;
            default:
                throw new Exception('Tipo de archivo a descargar es inválido');
        }

        return $this->downloadStorage($folder, $filename, $extension);
    }

    public function ticket($voided_id, $group_id)
    {
        $voided = ($group_id === '01')?Voided::find($voided_id):Summary::find($voided_id);
        DB::connection('tenant')->transaction(function () use($voided) {

            $cpeBuilder = new CpeBuilder($voided);
            $res = $cpeBuilder->checkTicket($voided->ticket);

            if($res['success']) {
                $document_state_type_id = null;
                $code = $res['code'];
                if ($code === '0') {
                    $voided->update(['state_type_id' => '05']);
                    $document_state_type_id = '11';
                }
                if ($code === '99') {
                    $voided->update(['state_type_id' => '09']);
                    $document_state_type_id = '05';
                }
                if (in_array($code, ['0', '99'])) {
                    if ($res['cdrXml']) {
                        $this->uploadStorage('cdr', $res['cdrXml'], 'R-'.$voided->filename);
                        $voided->update(['has_cdr' => true]);
                    }
                }
                if ($document_state_type_id) {
                    foreach($voided->documents as $doc)
                    {
                        $doc->document()->update([
                            'state_type_id' => $document_state_type_id
                        ]);
                    }
                }
            }
        });

        return [
            'success' => true,
            'message' => 'Consulta realizada con éxito, la anulación fue aceptada'
        ];
    }
}