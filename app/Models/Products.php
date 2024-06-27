<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = ["name","price",];

    public function transactions()
    {
        return $this ->hasOne(Transactions::class);
    }

}
