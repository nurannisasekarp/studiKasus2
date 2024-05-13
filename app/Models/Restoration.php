<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restoration extends Model
{
    use softDeletes;

    protected $fillable = ["lending_id", "user_id", "date_time", "total_good_stuff", "total_defec_stuff"];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function lending() {
        return $this->belongsTo(Lending::class);
    }
}
