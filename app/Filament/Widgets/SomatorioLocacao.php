<?php

namespace App\Filament\Widgets;

use App\Models\Temp_lucratividade;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SomatorioLocacao extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        $ano = date('Y');
        $mes = date('m');
        $dia = date('d');
        // dd($ano);
        return [
            Stat::make('Total de Locações', number_format(Temp_lucratividade::all()->sum('valor_diaria'), 2, ",", "."))
                ->description('Todo Perído')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total de Locações', number_format(DB::table('temp_lucratividades')->whereYear('data_saida', $ano)->whereMonth('data_saida', $mes)->sum('valor_diaria'), 2, ",", "."))
                ->description('Este mês')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total de Locações', number_format(DB::table('temp_lucratividades')->whereYear('data_saida', $ano)->whereMonth('data_saida', $mes)->whereDay('data_saida', $dia)->sum('valor_diaria'), 2, ",", "."))
                ->description('Hoje')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
