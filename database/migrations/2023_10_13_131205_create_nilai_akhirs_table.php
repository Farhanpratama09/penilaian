<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_akhirs', function (Blueprint $table) {
            $table->id();
            $table->string('nilai_akhir');
            $table->double('batas_bawah');
            $table->double('batas_atas');
            $table->timestamps();
        });

        DB::table('nilai_akhirs')->insert(
            array(
                [
                    'nilai_akhir' => 'Sangat Kurang',
                    'batas_bawah' => 1.0,
                    'batas_atas' => 2.45,
                ],
                [
                    'nilai_akhir' => 'Kurang',
                    'batas_bawah' => 2.5,
                    'batas_atas' => 2.7,
                ],
                [
                    'nilai_akhir' => 'Cukup',
                    'batas_bawah' => 2.75,
                    'batas_atas' => 3.45,
                ],
                [
                    'nilai_akhir' => 'Baik',
                    'batas_bawah' => 3.5,
                    'batas_atas' => 4.25,
                ],
                [
                    'nilai_akhir' => 'Sangat Baik',
                    'batas_bawah' => 4.3,
                    'batas_atas' => 5.0,
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
        Schema::dropIfExists('nilai_akhirs');
    }
};
