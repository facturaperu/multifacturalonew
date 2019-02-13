<?php
namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\CoreFacturalo\Facturalo;
use App\Http\Requests\Tenant\{
    SummaryDocumentsRequest,
    SummaryRequest
};
use App\Http\Resources\Tenant\{
    DocumentCollection,
    SummaryCollection
};
use App\Traits\SummaryTrait;
use App\Models\Tenant\{
    Document,
    Summary,
    Company
};
use Exception;

class SummaryController extends Controller
{
    use StorageDocument, SummaryTrait;
    
    public function __construct() {
        $this->middleware('input.request:summary,web', ['only' => ['store']]);
    }
    
    public function index() {
        return view('tenant.summaries.index');
    }
    
    public function records() {
        $records = Summary::where('summary_status_type_id', '1')
            ->latest();
        
        return new SummaryCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }
    
    public function documents(SummaryDocumentsRequest $request) {
        $company = Company::active();
        $date_of_reference = $request->input('date_of_reference');
        
        $documents = Document::query()
            ->where('date_of_issue', $request->input('date_of_reference'))
            ->where('soap_type_id', $company->soap_type_id)
            ->where('group_id', '02')
            ->where('state_type_id', '01')
            ->get();
            
        if (count($documents) === 0) throw new Exception("No se encontraron documentos con la fecha {$date_of_reference}");
        
        return new DocumentCollection($documents);
    }
    
    public function store(SummaryRequest $request) {
        return $this->save($request);
    }
    
    public function status($summary_id) {
        return $this->query($summary_id);
    }
}
