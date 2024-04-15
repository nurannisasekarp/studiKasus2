<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class Stuff extends Model
{
    // jika di migrationnya menggunakan $table->softDeletes jadii ini g awajib
    use softDeletes;

    //fillable / guard
    // menentukan column yang wajib diisi (column yang bisa diisi dari luar)
    protected $fillable = ["name", "category"];
    // protected $guarded = ['id'];

    //property optional:
    // kalau primary key bukan id : public $primarykey = 'no'
    // kalau misal h=gapake timestamps di migration : public $timestamps = FALSE

    //relasi

    // nama function : samain kaya model, kata pertama huruf kecil
    //model yang PK : hasOne / hasMany
    //panggil namaModelRelasi::class
    public function stuffStock() {
        return $this->hasOne(StuffStock::class);
    }

    // relasi hasMany : nama func jamak
    public function inboundStuffs() {
        return $this->hasMany(InboundStuff::class);
    }

    public function lendings() {
        return $this->hasMany(Lending::class);
    }
}
