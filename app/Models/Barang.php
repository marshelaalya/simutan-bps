<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Barang;
use App\Models\Kelompok;
use Auth;
use Illuminate\Support\Carbon;

class Barang extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function kelompok(){
        return $this->belongsTo(Kelompok::class,'kelompok_id','id');
    }

}
