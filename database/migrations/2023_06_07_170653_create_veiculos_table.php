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
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marca_id');
            $table->string('modelo');
            $table->string('ano');
            $table->string('placa');
            $table->string('cor');
            $table->integer('km_atual');
            $table->decimal('valor_diaria',10,2);
            $table->integer('prox_troca_oleo');
            $table->integer('prox_troca_filtro');
            $table->integer('aviso_troca_oleo');
            $table->integer('aviso_troca_filtro');
            $table->integer('prox_troca_correia');
            $table->integer('prox_troca_pastilha');
            $table->integer('aviso_troca_correia');
            $table->integer('aviso_troca_pastilha');
            $table->string('chassi');
            $table->date('data_compra');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('veiculos');
    }
};
