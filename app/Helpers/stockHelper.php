<?php

use App\Models\stock;

function createStock($id, $cr, $db, $date, $notes, $ref)
{
    stock::create(
        [
            'product_id'     => $id,
            'cr'            => $cr,
            'db'            => $db,
            'date'          => $date,
            'notes'         => $notes,
            'refID'         => $ref,
        ]
    );
}
function getStock($id){
   
        $cr  = stock::where('product_id', $id)->sum('cr');
        $db  = stock::where('product_id', $id)->sum('db');
  
    return $cr - $db;
}

