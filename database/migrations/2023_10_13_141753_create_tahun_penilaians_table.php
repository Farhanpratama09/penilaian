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
        Schema::create('tahun_penilaians', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_penilaian')->unique();
            $table->boolean('aktif')->default(false);
            $table->timestamps();
        });

        DB::table('tahun_penilaians')->insert(
            array(
                [
                    'tahun_penilaian' => '2023',
                    'aktif' => true
                ],
                [
                    'tahun_penilaian' => '2024',
                    'aktif' => false
                ]
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
        Schema::dropIfExists('tahun_penilaians');
    }
};
