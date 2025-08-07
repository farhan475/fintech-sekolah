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
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->decimal('jumlah', 12 ,2);
            $table->date('tanggal');
            $table->unsignedBigInteger('id_siswa');
            $table->foreign('id_siswa')->references('id')->on('siswas')->onDelete('cascade');
            $table->unsignedBigInteger('id_user_bank');
            $table->foreign('id_user_bank')->references('id_user')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
