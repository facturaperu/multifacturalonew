<?php

namespace App\Console\Commands;

use App\Models\Tenant\Document;
use Illuminate\Console\Command;
use Modules\Services\Helpers\Extras\ValidateCpe2;

class ValidateDocumentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate:documents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consultar el estado de los documentos electrónicos';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $documents = Document::query()
//                                ->whereNull('response_code')
//                                ->orWhere('response_code', '0')
//                                ->where('document_type_id', '03')
                                ->orderBy('series')
                                ->orderBy('number')
                                ->get();

        $count = 0;
        $this->info('-------------------------------------------------');
        $this->info('Documentos:' . count($documents));
        foreach ($documents as $document)
        {
            $count++;
            reValidate:
            $validate_cpe = new ValidateCpe2();
            $response = $validate_cpe->search($document->company->number,
                                              $document->document_type_id,
                                              $document->series,
                                              $document->number,
                                              $document->date_of_issue,
                                              $document->total);
            if ($response['success']) {
                $state_type_id = null;
                $response_code = $response['data']['comprobante_estado_codigo'];
                $response_description = $response['data']['comprobante_estado_descripcion'];

                $this->info($count.': '.$document->number_full.'|'.'Mensaje: '.$response_description);

//                if ($response_code === '0') {
//                    $state_type_id = '01';
//                }
//                if ($response_code === '1') {
//                    $state_type_id = '05';
//                }
//                if ($response_code === '2') {
//                    $state_type_id = '11';
//                }
//                if (in_array($response_code, ['0', '1', '2'])) {
//                    $document->update([
//                        'state_type_id' => $state_type_id,
//                        'response_code' => $response_code,
//                        'response_description' => $response_description,
//                    ]);
//                }
            } else {
                goto reValidate;
            }
        }
        $this->info('-------------------------------------------------');

        return ;
    }
}
