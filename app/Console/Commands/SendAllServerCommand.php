<?php

namespace App\Console\Commands;

use Facades\App\Http\Controllers\Tenant\DocumentController;
use Illuminate\Console\Command;
use App\Models\Tenant\Document;

class SendAllServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offline:send-all';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all pending documents to the online server';
    
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
        if (!$this->isOffline()) {
            $this->info('The offline service is disabled');
            
            return;
        };
        
        $documents = Document::query()
            ->where('send_server', 0)
            ->get();
        
        foreach ($documents as $document) {
            try {
                DocumentController::sendServer($document->id);
            }
            catch (\Exception $e) {
                $document->shipping_status = json_encode([
                    'message' => $e->getMessage(),
                    'payload' => $e
                ]);
                
                $document->save();
            }
        }
        
        $this->info('The command is finished');
    }
    
    /**
     * Is offline
     * @return boolean
     */
    private function isOffline() {
        return config('tenant.is_client');
    }
}
