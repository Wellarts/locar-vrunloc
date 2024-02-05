<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ocorrenciaLocacao extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [

       'locacao_id',
        'data',
        'descricao',
        'valor',10,2,
        'status',
           
    ];

    public function Locacao()
    {
        return $this->belongsTo(Locacao::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
}
