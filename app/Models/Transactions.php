<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transactions extends Model
{

    protected $fillable = ["product_id","order_date", "quantity"];
    //protected $guarded = ['id']

    //property opsional :
    //kalau primary key bukan id : public $primarykey = 'no'
    //kalau misal gapake timestamps di migration : public $timestamps = FALSE
     //model yang PK : hasOne/ hanMany
     //panggil namaModelFk::class
     public function products()
     {
         return $this->belongsTo(Products::class);
     }

}