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
        Schema::create('contas_pagars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->constrained('fornecedors')->restrictOnDelete();
            $table->string('parcelas');
            $table->string('ordem_parcela');
            $table->string('formaPgmto');
            $table->date('data_vencimento');
            $table->date('data_pagamento');
            $table->boolean('status');
            $table->decimal('valor_total',10,2);
            $table->decimal('valor_parcela',10,2);
            $table->decimal('valor_pago',10,2);
            $table->longText('obs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas_pagars');
    }
};
