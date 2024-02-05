<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Fornecedor extends Model
{
    use HasFactory, LogsActivity;

        protected $fillable = [
        'nome',
        'cpf_cnpj',
        'endereco',
        'estado_id',
        'cidade_id',
        'telefone',
        'email',
        
    ];

    public function Estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function Cidade()
    {
        return $this->belongsTo(Cidade::class);
    }

    public function CustoVeiculo()
    {
        return $this->hasMany(CustoVeiculo::class);
    }

    public function ContasPagar()
    {
        return $this->hasMany(ContasPagar::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
        
    

    
}
