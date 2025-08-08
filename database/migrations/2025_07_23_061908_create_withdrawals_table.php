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
            $table->foreignId('id_siswa')->constrained('siswas', 'id_siswa')->onDelete('cascade');
            $table->foreignId('id_user_bank')->constrained('users', 'id_user')->onDelete('cascade');
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
