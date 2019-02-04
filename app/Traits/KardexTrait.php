<?php

namespace App\Traits;
use App\Models\Tenant\Kardex; 
 


trait KardexTrait
{
    
    public function saveKardex($type, $item_id, $id, $quantity) {
        
        $kardex = Kardex::create([
            'type' => $type,
            'date_of_issue' => date('Y-m-d'),
            'item_id' => $item_id,
            'document_id' => ($type == 'sale') ? $id : null,
            'purchase_id' => ($type == 'purchase') ? $id : null,
            'quantity' => $quantity,
        ]);

        return $kardex;

    }
}
