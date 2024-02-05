<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Locacao extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [

       
        'cliente_id',
        'veiculo_id',
        'data_saida',
        'hora_saida',
        'data_retorno',
        'hora_retorno',
        'qtd_diarias',
        'km_saida',
        'km_retorno',
        'valor_desconto',
        'valor_total',
        'valor_total_desconto',
        'obs',
        'status',
    ];

    public function Cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function Veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }

    public function OcorrenciaLocacao()
    {
        return $this->hasMany(ocorrenciaLocacao::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
}


