<?php

namespace App\Models;

use App\Models\User;
use App\Models\TahunPenilaian;
use App\Models\PeriodePenilaian;
use App\Models\HasilPenilaianGuru;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianGuru extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function tahun_penilaian()
    {
        return $this->belongsTo(TahunPenilaian::class);
    }

    public function periode_penilaian()
    {
        return $this->belongsTo(PeriodePenilaian::class);
    }

    public function hasil_penilaian_guru()
    {
        return $this->hasMany(HasilPenilaianGuru::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'penilai_id')->where('role', 2);
    }

    public function tinjauan()
    {
        return $this->hasMany(Tinjauan::class);
    }

}
