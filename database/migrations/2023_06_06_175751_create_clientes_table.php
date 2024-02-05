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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf_cnpj');
            $table->longText('endereco');
            $table->foreignId('estado_id');
            $table->foreignId('cidade_id');
            $table->string('telefone_1');
            $table->string('telefone_2');
            $table->string('email');
            $table->string('rede_social');
            $table->string('cnh');
            $table->string('validade_cnh');
            $table->string('rg');
            $table->string('exp_rg');
            $table->foreignId('estado_exp_rg');
            $table->string('img_cnh');
            $table->string('data_nascimento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
