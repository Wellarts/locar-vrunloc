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
        'status_financeiro',
        'status_pago_financeiro',
        'parcelas_financeiro',
        'formaPgmto_financeiro',
        'valor_parcela_financeiro',
        'valor_total_financeiro',
        'data_vencimento_financeiro',
        'ocorrencia',
        'valor_caucao',
        'forma_locacao',
        'qtd_semanas',
        'testemunha_1',
        'testemunha_2',
        'testemunha_1_rg',
        'testemunha_2_rg',
        'fiador',
        'dados_fiador',
    ];

    protected $casts = [
        'ocorrencia' => 'array',
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
