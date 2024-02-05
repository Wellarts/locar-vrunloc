<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Agendamento extends Model
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
        'valor_total',
        'valor_desconto',
        'valor_pago',
        'valor_restante',
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
}
