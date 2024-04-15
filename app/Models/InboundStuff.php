<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Stuff;
use Illuminate\Database\Eloquent\SoftDeletes;

class InboundStuff extends Model
{
    use softDeletes;

    protected $fillable = ["stuff_id", "total", "date", "proof_file"];

    public function stuff() {
        return $this->belongsTo(Stuff::class);
    }
}
