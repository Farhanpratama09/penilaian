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
        Schema::create('kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('kriteria');
            $table->double('bobot');
            $table->timestamps();
        });

        DB::table('kriterias')->insert(
            array(
                [
                    'kriteria' => 'Berorientasi Pelayanan',
                    'bobot' => 0.2,
                ],
                [
                    'kriteria' => 'Akuntabel',
                    'bobot' => 0.2,
                ],
                [
                    'kriteria' => 'Kompeten',
                    'bobot' => 0.2,
                ],
                [
                    'kriteria' => 'Harmonis',
                    'bobot' => 0.1,
                ],
                [
                    'kriteria' => 'Loyal',
                    'bobot' => 0.1,
                ],
                [
                    'kriteria' => 'Adaptif',
                    'bobot' => 0.1,
                ],
                [
                    'kriteria' => 'Kolaboratif',
                    'bobot' => 0.1,
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
        Schema::dropIfExists('kriterias');
    }
};
