<?php

namespace App\Models;

use App\Models\Anchor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HasilPenilaianGuru extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function anchor()
    {
        return $this->belongsTo(Anchor::class);
    }

    
    
}
