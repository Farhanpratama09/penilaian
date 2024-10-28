<?php

namespace App\Models;

use App\Models\Kriteria;
use App\Models\PenilaianGuru;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tinjauan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function penilaianGuru()
    {
        return $this->belongsTo(PenilaianGuru::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
