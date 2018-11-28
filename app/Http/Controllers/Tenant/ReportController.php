<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\Company;
use App\Models\Tenant\Establishment;
use App\Http\Resources\Tenant\DocumentCollection;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\DocumentExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;


class ReportController extends Controller
{
    public function index(){
        return view("tenant.reports.index");
    }


    public function search(Request $request){
         
        $d=null;
        $a=null;
        $td=$this->getTypeDoc($request->type_doc);
        
        if($request->has('d') && $request->has('a') && ($request->d!=null && $request->a!=null)){
        
            $d=$request->d;
            $a=$request->a;

            if($td=="1000"){ 
                $reports = Document::with([ 'state_type', 'customer'])->whereBetween('date_of_issue',array($d,$a))->latest()->get(); 
            }else{
                $reports = Document::with([ 'state_type', 'customer'])->whereBetween('date_of_issue',array($d,$a))
                ->latest()->where('document_type_code',$td)->get();               
            }            
        
        }else{ 

            if($td=="1000"){
                $reports = Document::with([ 'state_type', 'customer'])->latest()->get();   
            }else{
                $reports = Document::with([ 'state_type', 'customer'])->latest()->where('document_type_code',$td)->get();   
            }
                        
        }
                  
        return view("tenant.reports.index", compact("reports","a","d","td"));  
    }

    

    public function pdf(Request $request){         

        $company=Company::first();
        $establishment=Establishment::first();
        $td=$request->td;

        if($request->has('d') && $request->has('a') && ($request->d!=null && $request->a!=null)){ 

            $d=$request->d;
            $a=$request->a;

            if($td=="1000"){
                $reports = Document::with([ 'state_type', 'customer'])->whereBetween('date_of_issue',array($d,$a))->latest()->get(); 
            }else{
                $reports = Document::with([ 'state_type', 'customer'])->whereBetween('date_of_issue',array($d,$a))
                ->latest()->where('document_type_code',$td)->get(); 
            }  

        }else{
            
            if($td=="1000"){
                $reports = Document::with([ 'state_type', 'customer'])->latest()->get();   
            }else{
                $reports = Document::with([ 'state_type', 'customer'])->latest()->where('document_type_code',$td)->get();   
            } 

        }

        $pdf = PDF::loadView('tenant.reports.report_pdf', compact("reports","company","establishment"));
        $filename = 'Reporte_'.date('YmdHis');
        return $pdf->download($filename.'.pdf');
    }


    public function excel(Request $request){ 


        $company=Company::first();
        $establishment=Establishment::first();
        $td=$request->td;
       

        if($request->has('d') && $request->has('a') && ($request->d!=null && $request->a!=null)){ 
 
            $d=$request->d;
            $a=$request->a;
            
            if($td=="1000"){
                $records = Document::with([ 'state_type', 'customer'])->whereBetween('date_of_issue',array($d,$a))->latest()->get(); 
            }else{
                $records = Document::with([ 'state_type', 'customer'])->whereBetween('date_of_issue',array($d,$a))
                ->latest()->where('document_type_code',$td)->get(); 
            } 

        }else{
            
            if($td=="1000"){
                $records = Document::with([ 'state_type', 'customer'])->latest()->get();   
            }else{  
                $records = Document::with([ 'state_type', 'customer'])->where('document_type_code',$td)->latest()->get();   
            }           

        }
        
        return (new DocumentExport)
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->download('ReporteDoc'.Carbon::now().'.xlsx');
    }



    public function getTypeDoc($request){

        $type_doc=null;

        switch ($request) {

            case 'Factura':
                $type_doc="01";
                break;
            case 'Boleta': 
                $type_doc="03";

                break;
            case 'Nota de Crédito': 
                $type_doc="07";

                break;
            case 'Nota de Débito':
                $type_doc="08"; 
                break;
            default: 
                $type_doc="1000";
                break;
        }

        return $type_doc;

    }
}
