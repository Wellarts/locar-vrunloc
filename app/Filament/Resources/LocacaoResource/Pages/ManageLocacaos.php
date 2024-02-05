<?php

namespace App\Filament\Resources\LocacaoResource\Pages;

use App\Filament\Resources\LocacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLocacaos extends ManageRecords
{
    protected static string $resource = LocacaoResource::class;

    protected static ?string $title = 'Locações';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novo')
                ->modalHeading('Criar Locação'),
        ];
    }
}
