<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Resources\Tenant\DocumentCollection;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\DocumentExport;
use Illuminate\Http\Request;
use App\Models\Tenant\{
    Establishment,
    Document,
    Company,
};
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index() {
        $documentTypes = DocumentType::query()
            ->where('active', 1)
            ->get();
        
        return view('tenant.reports.index', compact('documentTypes'));
    }
    
    public function search(Request $request) {
        $documentTypes = DocumentType::all();
        $td = $this->getTypeDoc($request->document_type);
        $d = null;
        $a = null;
        
        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;
            
            if (is_null($td)) {
                $reports = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();
            }
            else {
                $reports = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('document_type_id', $td)
                    ->get();
            }
        }
        else {
            if (is_null($td)) {
                $reports = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->get();
            } else {
                $reports = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->where('document_type_id', $td)
                    ->get();
            }
        }
        
        return view("tenant.reports.index", compact("reports", "a", "d", "td", "documentTypes"));
    }
    
    public function pdf(Request $request) {
        $company = Company::first();
        $establishment = Establishment::first();
        $td = $request->td;
        
        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;
            
            if (is_null($td)) {
                $reports = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();
            }
            else {
                $reports = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('document_type_id', $td)
                    ->get();
            }
        }
        else {
            if (is_null($td)) {
                $reports = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->get();
            }
            else {
                $reports = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->where('document_type_id', $td)
                    ->get();
            }
        }
        
        $pdf = PDF::loadView('tenant.reports.report_pdf', compact("reports", "company", "establishment"));
        $filename = 'Reporte_'.date('YmdHis');
        
        return $pdf->download($filename.'.pdf');
    }
    
    public function excel(Request $request) {
        $company = Company::first();
        $establishment = Establishment::first();
        $td= $request->td;
       
        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;
            
            if (is_null($td)) {
                $records = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();
            }
            else {
                $records = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('document_type_id', $td)
                    ->get();
            }
        }
        else {
            if (is_null($td)) {
                $records = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->get();
            }
            else {
                $records = Document::with([ 'state_type', 'person'])->where('document_type_id', $td)
                    ->latest()
                    ->get();
            }
        }
        
        return (new DocumentExport)
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->download('ReporteDoc'.Carbon::now().'.xlsx');
    }
    
    public function getTypeDoc($documentType) {
        foreach (DocumentType::all() as $item) {
            if (mb_strtoupper($item->description) == $documentType) return $item->id;
        }
        
        return null;
    }
}
