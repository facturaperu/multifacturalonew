<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\SummaryTrait;
use App\Models\Tenant\{
    Configuration,
    Document,
    Summary,
    Company,
    User
};
use Carbon\Carbon;
use Auth;

class SummaryQueryCommand extends Command
{
    use SummaryTrait;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summary:query';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatic query of summaries';
    
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
        $this->info('The command was started');
        
        Auth::login(User::firstOrFail());
        
        if (Configuration::firstOrFail()->cron) {
            $date = Carbon::now()->subDay()->format('Y-m-d');
            
            $documents = Summary::query()
                ->where([
                    'soap_type_id' => Company::firstOrFail()->active()->soap_type_id,
                    'summary_status_type_id' => '1',
                    'date_of_reference' => $date,
                    'state_type_id' => '03',
                ])
                ->get();
            
            if ($documents->count() > 0) {
                foreach ($documents as $document) $this->query($document->id);
            }
            else {
                $this->info('No data to process');
            }
        }
        else {
            $this->info('The cron is disabled');
        }
        
        $this->info('The command is finished');
    }
}
