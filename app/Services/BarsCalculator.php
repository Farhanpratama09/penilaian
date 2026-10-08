<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\NilaiAkhir;
use App\Models\PenilaianGuru;

class BarsCalculator
{
    /**
     * Menghitung skor BARS lengkap untuk satu PenilaianGuru.
     *
     * @param PenilaianGuru $penilaian
     * @return array
     */
    public static function calculate(PenilaianGuru $penilaian): array
    {
        // Pastikan relasi hasil_penilaian_guru dan anchor sudah ter-load
        if (!$penilaian->relationLoaded('hasil_penilaian_guru')) {
            $penilaian->load('hasil_penilaian_guru.anchor');
        }

        $kriteriaList = Kriteria::with('subKriteria')->get();
        $nak = 0.0;
        $kriteriaScores = []; // skala murni 1-5 per kriteria
        $kriteriaWeighted = []; // nilai setelah dikali bobot

        foreach ($kriteriaList as $k) {
            $subKriteriaCount = $k->subKriteria->count();
            $sumBobot = 0.0;

            if ($subKriteriaCount > 0) {
                foreach ($k->subKriteria as $sk) {
                    foreach ($penilaian->hasil_penilaian_guru as $h) {
                        if ($h->anchor && $h->anchor->sub_kriteria_id == $sk->id) {
                            $sumBobot += (float) $h->anchor->bobot;
                            break;
                        }
                    }
                }
                $nilaiKriteria = round($sumBobot / $subKriteriaCount, 2);
            } else {
                $nilaiKriteria = 0.0;
            }

            $nilaiTertimbang = $nilaiKriteria * (float) $k->bobot;
            $nak += $nilaiTertimbang;

            $kriteriaScores[$k->id] = $nilaiKriteria;
            $kriteriaWeighted[$k->id] = round($nilaiTertimbang, 4);
        }

        $nilaiAkhir = round($nak, 2);
        $predikat = self::getPredikat($nilaiAkhir);
        $predikatClass = self::getPredikatClass($predikat);
        $ratingPerilakuKerja = self::getRatingPerilakuKerja($predikat, $nilaiAkhir);

        return [
            'nilai_akhir' => $nilaiAkhir,
            'predikat' => $predikat,
            'predikat_class' => $predikatClass,
            'rating_perilaku_kerja' => $ratingPerilakuKerja,
            'kriteria_scores' => $kriteriaScores,
            'kriteria_weighted' => $kriteriaWeighted,
        ];
    }

    /**
     * Mendapatkan predikat berdasarkan nilai akhir kumulatif (NAK).
     * Menjembatani seluruh celah desimal (floating-point boundary gap) sehingga
     * tidak pernah mengembalikan NULL atau tidak terdefinisi.
     *
     * @param float $nak
     * @return string
     */
    public static function getPredikat(float $nak): string
    {
        try {
            $nilaiAkhirList = NilaiAkhir::orderBy('batas_bawah')->get();
        } catch (\Throwable $th) {
            $nilaiAkhirList = collect();
        }

        if ($nilaiAkhirList->isNotEmpty()) {
            foreach ($nilaiAkhirList as $key => $na) {
                // Berada dalam rentang batas bawah & batas atas
                if ($nak >= $na->batas_bawah && $nak <= $na->batas_atas) {
                    return $na->nilai_akhir;
                }

                // Berada di celah antara rentang sebelumnya dengan rentang saat ini
                if ($key > 0 && $nak < $na->batas_bawah && $nak > $nilaiAkhirList[$key - 1]->batas_atas) {
                    return $nilaiAkhirList[$key - 1]->nilai_akhir;
                }
            }

            // Jika nilai di atas batas atas tertinggi
            $tertinggi = $nilaiAkhirList->last();
            if ($nak > $tertinggi->batas_atas) {
                return $tertinggi->nilai_akhir;
            }

            // Jika nilai di bawah batas bawah terendah
            $terendah = $nilaiAkhirList->first();
            if ($nak < $terendah->batas_bawah) {
                return $terendah->nilai_akhir;
            }
        }

        // Fallback standar PermenPANRB / Pedoman BARS jika tabel nilai_akhirs belum tersedia
        if ($nak >= 4.30) {
            return 'Sangat Baik';
        } elseif ($nak >= 3.50) {
            return 'Baik';
        } elseif ($nak >= 2.75) {
            return 'Cukup';
        } elseif ($nak >= 2.50) {
            return 'Kurang';
        } else {
            return 'Sangat Kurang';
        }
    }

    /**
     * Menentukan badge CSS class untuk predikat.
     *
     * @param string $predikat
     * @return string
     */
    public static function getPredikatClass(string $predikat): string
    {
        return match ($predikat) {
            'Sangat Baik' => 'success',
            'Baik' => 'info',
            'Cukup' => 'primary',
            'Kurang' => 'warning',
            'Sangat Kurang' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Menentukan rating perilaku kerja berdasarkan predikat dan nilai akhir.
     *
     * @param string $predikat
     * @param float $nak
     * @return string
     */
    public static function getRatingPerilakuKerja(string $predikat, float $nak): string
    {
        return match ($predikat) {
            'Sangat Baik' => 'di atas ekspektasi',
            'Baik' => $nak >= 4.0 ? 'di atas ekspektasi' : 'sesuai ekspektasi',
            'Cukup' => $nak >= 3.0 ? 'di atas ekspektasi' : 'sesuai ekspektasi',
            'Kurang', 'Sangat Kurang' => 'di bawah ekspektasi',
            default => 'Belum Dinilai',
        };
    }
}
