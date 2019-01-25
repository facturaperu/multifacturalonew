<?php

namespace App\Providers;

use App\Models\Tenant\Item;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\Document;
use App\Models\Tenant\PurchaseItem; 
use App\Models\Tenant\Kardex;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
     
    public function boot()
    {      
        $this->sale();
        $this->purchase();
    }

     
    public function register()
    {
        
    }

    private function sale(){

        DocumentItem::created(function ($document_item) {          
            
            $document = Document::whereIn('document_type_id',['01','03'])->find($document_item->document_id);
            
            if($document){ 

                $kardex = Kardex::create([
                    'type' => 'sale',
                    'date_of_issue' => date('Y-m-d'),
                    'item_id' => $document_item->item_id,
                    'document_id' => $document_item->document_id,
                    'purchase_id' => null,
                    'quantity' => $document_item->quantity,
                ]);

                $item = Item::find($document_item->item_id);
                $item->stock -= $kardex->quantity;
                $item->save();

            }
            

        });
    }

    private function purchase(){

        PurchaseItem::created(function ($purchase_item) {                    
            
            $kardex = Kardex::create([
                'type' => 'purchase',
                'date_of_issue' => date('Y-m-d'),
                'item_id' => $purchase_item->item_id,
                'document_id' => null,
                'purchase_id' => $purchase_item->purchase_id,
                'quantity' => $purchase_item->quantity,
            ]); 

            $item = Item::find($purchase_item->item_id);
            $item->stock += $kardex->quantity;
            $item->save();

        });

    }
}
