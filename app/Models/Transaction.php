<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['product_id', 'order_date', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class); //relasi
    }
}

