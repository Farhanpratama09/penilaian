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
        Schema::create('periode_penilaians', function (Blueprint $table) {
            $table->id();
            $table->string('periode_penilaian')->unique();
            $table->boolean('aktif')->default(false);
            $table->timestamps();
        });

        DB::table('periode_penilaians')->insert(
            array(
                [
                    'periode_penilaian' => 'TRIWULAN 1',
                    'aktif' => true
                ],
                [
                    'periode_penilaian' => 'TRIWULAN 2',
                    'aktif' => false
                ],
                [
                    'periode_penilaian' => 'TRIWULAN 3',
                    'aktif' => false
                ],
                [
                    'periode_penilaian' => 'TRIWULAN 4',
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
        Schema::dropIfExists('periode_penilaians');
    }
};
