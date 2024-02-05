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
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id');
            $table->foreignId('veiculo_id');
            $table->date('data_saida');
            $table->time('hora_saida');
            $table->date('data_retorno');
            $table->time('hora_retorno');
            $table->integer('qtd_diarias');
            $table->decimal('valor_total',10,2);
            $table->decimal('valor_desconto',10,2);
            $table->decimal('valor_pago',10,2);
            $table->decimal('valor_restante',10,2);
            $table->longText('obs');
            $table->boolean('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
