<?php

namespace App\Console\Commands;

use Facades\App\Http\Controllers\Tenant\DocumentController;
use Illuminate\Console\Command;
use App\Models\Tenant\Document;
use App\Traits\CommandTrait;

class SendAllSunatCommand extends Command
{
    use CommandTrait;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'online:send-all';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all pending documents to be sent to the Sunat';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        if ($this->isOffline()) {
            $this->info('Offline service is enabled');
            
            return;
        }
        
        $documents = Document::query()
            ->where('send_server', 0)
            ->where('state_type_id', '!=', '05')
            ->orWhere('sunat_shipping_status', '!=', '')
            ->get();
        
        foreach ($documents as $document) {
            try {
                DocumentController::send($document->id);
                
                $document->sunat_shipping_status = '';
                $document->save();
            }
            catch (\Exception $e) {
                $document->sunat_shipping_status = json_encode([
                    'message' => $e->getMessage(),
                    'payload' => $e
                ]);
                
                $document->save();
            }
        }
        
        $this->info('The command is finished');
    }
}
