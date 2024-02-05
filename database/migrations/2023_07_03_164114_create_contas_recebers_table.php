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
        Schema::create('contas_recebers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->string('parcelas');
            $table->string('ordem_parcela');
            $table->string('formaPgmto');
            $table->date('data_vencimento');
            $table->date('data_recebimento');
            $table->boolean('status');
            $table->decimal('valor_total',10,2);
            $table->decimal('valor_parcela',10,2);
            $table->decimal('valor_recebido',10,2);
            $table->longText('obs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas_recebers');
    }
};
