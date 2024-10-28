<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Models\Kriteria;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_kriterias', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Kriteria::class)->constrained()->onDelete('cascade');
            $table->string('sub_kriteria');
            $table->timestamps();
        });

        DB::table('sub_kriterias')->insert(
            array(
                [
                    'kriteria_id' => 1,
                    'sub_kriteria' => 'Memahami dan memenuhi kebutuhan masyarakat'
                ],
                [
                    'kriteria_id' => 1,
                    'sub_kriteria' => 'Ramah, Cekatan, Solutif, dan Dapat diandalkan'
                ],
                [
                    'kriteria_id' => 1,
                    'sub_kriteria' => 'Melakukan perbaikan tiada henti'
                ],
                [
                    'kriteria_id' => 2,
                    'sub_kriteria' => 'Melaksanakan tugas dengan jujur, bertanggungjawab, cermat, disiplin dan berintegritas tinggi'
                ],
                [
                    'kriteria_id' => 2,
                    'sub_kriteria' => 'Menggunakan kekayaan dan barang milik negara secara bertanggungjawab, efektif, dan efisien'
                ],
                [
                    'kriteria_id' => 2,
                    'sub_kriteria' => 'Tidak menyalahgunakan kewenangan jabatan'
                ],
                [
                    'kriteria_id' => 3,
                    'sub_kriteria' => 'Meningkatkan kompetensi diri untuk menjawab tantangan yang selalu berubah'
                ],
                [
                    'kriteria_id' => 3,
                    'sub_kriteria' => 'Membantu orang lain belajar'
                ],
                [
                    'kriteria_id' => 3,
                    'sub_kriteria' => 'Melaksanakan tugas dengan kualitas terbaik'
                ],
                [
                    'kriteria_id' => 4,
                    'sub_kriteria' => 'Menghargai setiap orang apapun latar belakangnya'
                ],
                [
                    'kriteria_id' => 4,
                    'sub_kriteria' => 'Suka menolong orang lain'
                ],
                [
                    'kriteria_id' => 4,
                    'sub_kriteria' => 'Membangun lingkungan kerja yang kondusif'
                ],
                [
                    'kriteria_id' => 5,
                    'sub_kriteria' => 'Memegang teguh ideologi Pancasila, UndangUndang Dasar Negara Republik Indonesia Tahun 1945, setia kepada Negara Kesatuan Republik Indonesia serta pemerintahan yang sah'
                ],
                [
                    'kriteria_id' => 5,
                    'sub_kriteria' => 'Menjaga nama baik sesama ASN, Pimpinan, Instansi, dan Negara'
                ],
                [
                    'kriteria_id' => 5,
                    'sub_kriteria' => 'Menjaga rahasia jabatan dan negara'
                ],
                [
                    'kriteria_id' => 6,
                    'sub_kriteria' => 'Cepat menyesuaikan diri menghadapi perubahan'
                ],
                [
                    'kriteria_id' => 6,
                    'sub_kriteria' => 'Terus berinovasi dan mengembangkan kreativitas'
                ],
                [
                    'kriteria_id' => 6,
                    'sub_kriteria' => 'Bertindak proaktif'
                ],
                [
                    'kriteria_id' => 7,
                    'sub_kriteria' => 'Memberi kesempatan kepada berbagai pihak untuk berkontribusi'
                ],
                [
                    'kriteria_id' => 7,
                    'sub_kriteria' => 'Terbuka dalam bekerja sama untuk menghasilkan nilai tambah'
                ],
                [
                    'kriteria_id' => 7,
                    'sub_kriteria' => 'Menggerakkan pemanfaatan berbagai sumberdaya untuk tujuan bersama'
                ],
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_kriterias');
    }
};
