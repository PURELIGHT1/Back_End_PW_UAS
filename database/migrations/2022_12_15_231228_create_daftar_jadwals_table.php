<?php

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
        Schema::create('daftar_jadwals', function (Blueprint $table) {
            $table->string('kode_daftar')->primary();
            $table->boolean('status');
            $table->string('tim')->nullable();
            $table->foreign('tim')->references('kode_tim')->on('tims')->onDelete('set null')->onUpdate('set null');
            $table->unsignedBigInteger('jadwal')->nullable();
            $table->foreign('jadwal')->references('id')->on('jadwals')->onDelete('set null')->onUpdate('set null');
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
        Schema::dropIfExists('daftar_jadwals');
    }
};
