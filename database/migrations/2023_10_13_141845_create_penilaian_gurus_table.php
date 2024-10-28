<?php

use App\Models\PeriodePenilaian;
use App\Models\TahunPenilaian;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penilaian_gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(TahunPenilaian::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(PeriodePenilaian::class)->constrained()->onDelete('cascade');
            $table->foreignId('penilai_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->text('catatan_rekomendasi')->nullable();
            $table->enum('status', ['DRAFT', 'PENDING', 'SELESAI'])->default('DRAFT');
            $table->string('dokumen')->nullable();
            $table->string('dokumen_pdf')->nullable();
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
        Schema::dropIfExists('penilaian_gurus');
    }
};
