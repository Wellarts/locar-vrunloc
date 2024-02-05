<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Cliente extends Model
{
    use HasFactory, LogsActivity;

    

    protected $fillable = [

        'nome',
        'cpf_cnpj',
        'endereco',
        'estado_id',
        'cidade_id',
        'telefone_1',
        'telefone_2',
        'email',
        'rede_social',
        'cnh',
        'validade_cnh',
        'rg',
        'exp_rg',
        'estado_exp_rg',
        'data_nascimento',
        'img_cnh',


    ];

    public function Estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function Cidade()
    {
        return $this->belongsTo(Cidade::class);
    }

    public function Agendamento()
    {
        return $this->hasMany(Agendamento::class);
    }

    public function Temp_lucratividade()
    {
        return $this->hasMany(Temp_lucratividade::class);
    }

    public function ContaReceber() 
    {
        return $this->hasMany(ContasReceber::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    
}
