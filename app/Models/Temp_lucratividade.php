<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temp_lucratividade extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'veiculo_id',
        'data_saida',
        'valor_diaria',
        'qtd_diaria',
    ];

    public function Veiculo()
    {
        return $this->belongsTo(Veiculo::class);

    }

    public function Cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
