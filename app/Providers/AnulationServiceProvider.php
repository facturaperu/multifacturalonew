<?php

namespace App\Providers;

use App\Models\Tenant\Item; 
use App\Models\Tenant\Document;  
use App\Models\Tenant\Kardex; 
use Illuminate\Support\ServiceProvider;

class AnulationServiceProvider extends ServiceProvider
{
     
    public function register()
    {
    }
    
    public function boot()
    {
        $this->anulation();
        
    }


    private function anulation(){

        Document::updated(function ($document) { 

            if($document['document_type_id'] == '01' || $document['document_type_id'] == '03'){

                if($document['state_type_id'] == 11){

                    foreach ($document['items'] as $detail) {
    
                        $item_id = $detail['item_id'];
    
                        $item = Item::find($item_id);
                        $item->stock = $item->stock + $detail['quantity'];
                        $item->save();
    
                        $kardex = Kardex::where('item_id',$item->id)->where('document_id',$document['id'])->first();
                        $kardex->quantity = 0;
                        $kardex->save();
                    }

                }
            }           

            
        });
        
    }
}
