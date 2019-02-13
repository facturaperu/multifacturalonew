<?php

namespace App\Console\Commands;

use App\CoreFacturalo\Requests\Web\Validation\SummaryValidation;
use App\CoreFacturalo\Requests\Inputs\SummaryInput;
use Illuminate\Console\Command;
use App\Traits\SummaryTrait;
use Illuminate\Http\Request;
use App\Models\Tenant\{
    Configuration,
    Document,
    Company,
    User
};
use Carbon\Carbon;
use Auth;

class SummarySendCommand extends Command
{
    use SummaryTrait;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summary:send';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatic send of summaries';
    
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
            
            $documents = Document::query()
                ->where([
                    'soap_type_id' => Company::firstOrFail()->active()->soap_type_id,
                    'date_of_issue' => $date,
                    'state_type_id' => '01',
                    'group_id' => '02'
                ])
                ->get();
            
            if ($documents->count() > 0) {
                $data = [
                    'documents' => $documents->toArray(),
                    'summary_status_type_id' => '1',
                    'date_of_reference' => $date,
                    'date_of_issue' => null
                ];
                
                $data = SummaryValidation::validation($data);
                $data = SummaryInput::set($data);
                
                $request = new Request;
                $request->merge($data);
                
                $this->save($request);
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
