<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('username')->unique(); //NIP
            $table->string('pangkat')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->string('password');
            $table->tinyInteger('role')->default(3);
            $table->timestamps();
        });

        DB::table('users')->insert(
            array(
                [
                    'nama' => 'Admin',
                    'username' => 'admin',
                    'pangkat' => null,
                    'jabatan' => null,
                    'unit_kerja' => null,
                    'password' => bcrypt('12345'),
                    'role' => 1,
                ],
                [
                    'nama' => 'Kepala Sekolah',
                    'username' => 'kepsek',
                    'pangkat' => null,
                    'jabatan' => null,
                    'unit_kerja' => null,
                    'password' => bcrypt('12345'),
                    'role' => 2,
                ],
                [
                    'nama' => 'Guru',
                    'username' => 'guru',
                    'pangkat' => null,
                    'jabatan' => null,
                    'unit_kerja' => null,
                    'password' => bcrypt('12345'),
                    'role' => 3,
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
        Schema::dropIfExists('users');
    }
};
