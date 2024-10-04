<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('trns_dks', function (Blueprint $table) {
            $table->id();
            $table->string('user_sales');
            $table->date('tgl_kunjungan');
            $table->string('kd_toko');
            $table->date('waktu_kunjungan');
            $table->enum('type', ['in', 'out']);
            $table->string('latitude');
            $table->string('longitude');
            $table->string('keterangan')->nullable();
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trns_dks');
    }
};
