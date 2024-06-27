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
        Schema::table('locacaos', function (Blueprint $table) {
            $table->boolean('status_financeiro')->nullable();
            $table->boolean('status_pago_financeiro')->nullable();
            $table->string('parcelas_financeiro')->nullable();
            $table->string('formaPgmto_financeiro')->nullable();
            $table->decimal('valor_parcela_financeiro',10,2)->nullable();
            $table->decimal('valor_total_financeiro',10,2)->nullable();
            $table->date('data_vencimento_financeiro')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locacao', function (Blueprint $table) {
            //
        });
    }
};
