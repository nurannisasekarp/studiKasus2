<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price'];
    // fillable / guard menentukan column yang wajib diisi (column yang bisa diisi dari luar)

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
