<?php

namespace App\Providers;

use App\Models\Tenant\Item;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\Document;
use App\Models\Tenant\PurchaseItem; 
use App\Models\Tenant\Kardex;
use Illuminate\Support\ServiceProvider;
use App\Traits\KardexTrait;

class KardexServiceProvider extends ServiceProvider
{
    use KardexTrait;
     
    public function boot()
    {        
        $this->sale();
        $this->purchase();
    }
 
    public function register()
    {
        
    }

    private function sale()
    {
        DocumentItem::created(function ($document_item) {
            $document = Document::whereIn('document_type_id',['01','03'])->find($document_item->document_id);
            if($document){

                $kardex = $this->saveKardex('sale', $document_item->item_id, $document_item->document_id, $document_item->quantity);
                
                if($document->state_type_id != 11){
                    $item = Item::find($document_item->item_id);
                    $item->stock -= $kardex->quantity;
                    $item->save();
                }
                
            }
        });
    }

    private function purchase()
    {
        PurchaseItem::created(function ($purchase_item) {

            $kardex = $this->saveKardex('purchase', $purchase_item->item_id, $purchase_item->purchase_id, $purchase_item->quantity);             

            $item = Item::find($purchase_item->item_id);
            $item->stock += $kardex->quantity;
            $item->save();
        });
    }
}
