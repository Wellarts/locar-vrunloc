<?php

namespace App\Filament\Pages;

use App\Models\CustoVeiculo;
use App\Models\Locacao;
use App\Models\Veiculo;
use Filament\Forms\Components\Grid;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Set;

class LucroVeiculo extends Page implements HasForms
{

    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.lucro-veiculo';

    protected static ?string $title = 'Lucratividade por Veículo';

    protected static ?string $navigationGroup = 'Consultas';


    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                    Select::make('veiculo_id')
                        ->options(Veiculo::all()->pluck('placa', 'id')->toArray())
                        ->live()
                       // ->searchable()
                        ->label('Veículo')
                        ->afterStateUpdated(function ($state, Set $set) {
                                dd($state);

                            /*  $total_locacao = Locacao::where('veiculo_id', $state)->sum('valor_total_desconto');
                              $total_custo = CustoVeiculo::where('veiculo_id', $state)->sum('valor');
                           // dd('$total_custo');
                                $set('total_locacao', $total_locacao);
                                $set('total_custo', $total_custo );
                                $set('lucro', $total_locacao - $total_custo); */


                        }),
                    Forms\Components\TextInput::make('total_locacao')
                        ->readOnly()
                        ->label('Total de Locação R$:'),
                    Forms\Components\TextInput::make('total_custo')
                        ->readOnly()
                        ->label('Total de Custos R$:'),
                    Forms\Components\TextInput::make('lucro')
                        ->readOnly()
                        ->label('Lucro Real R$:'),



                ])->columns([2])->inlineLabel();
    }




}





