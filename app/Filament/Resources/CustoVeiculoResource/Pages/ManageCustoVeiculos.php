<?php

namespace App\Filament\Resources\CustoVeiculoResource\Pages;

use App\Filament\Resources\CustoVeiculoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCustoVeiculos extends ManageRecords
{
    protected static string $resource = CustoVeiculoResource::class;

    protected static ?string $title = 'Manutenção de Veículos';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novo')
                ->modalHeading('Criar Manutenção'),
        ];
    }
}
