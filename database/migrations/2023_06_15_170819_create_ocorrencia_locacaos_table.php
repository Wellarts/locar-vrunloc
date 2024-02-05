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
        Schema::create('ocorrencia_locacaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('locacao_id');
            $table->date('data');
            $table->longText('descricao');
            $table->decimal('valor',10,2);
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocorrencia_locacaos');
    }
};
