<?php

use App\Models\Kriteria;
use App\Models\Anchor;
use App\Models\PenilaianGuru;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hasil_penilaian_gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PenilaianGuru::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Anchor::class)->constrained()->onDelete('cascade');
            $table->json('dokumen')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hasil_penilaian_gurus');
    }
};
