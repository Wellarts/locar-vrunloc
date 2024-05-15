<?php

namespace App\Filament\Pages;

use App\Models\CustoVeiculo;
use App\Models\Locacao;
use App\Models\Veiculo;
use Filament\Forms\Components\Grid;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Leandrocfe\FilamentPtbrFormFields\Money;

class LucroVeiculo extends Page implements HasForms
{

    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.lucro-veiculo';

    protected static ?string $title = 'Lucratividade por Veículo';

    protected static ?string $navigationGroup = 'Consultas';

    public array $data = [];


    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                DatePicker::make('inicio')
                    ->label('Data de Início'),
                DatePicker::make('fim')
                    ->label('Data de Fim'),
                    Select::make('veiculo_id')
                        ->searchable()
                        ->options(Veiculo::all()->pluck('placa', 'id')->toArray())
                        ->live()
                       // ->searchable()
                        ->label('Veículo')
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                             //   dd($state);

                              $total_locacao = Locacao::where('veiculo_id', $state)->whereBetween('data_saida',[$get('inicio'),$get('fim')])->sum('valor_total_desconto');
                              $total_custo = CustoVeiculo::where('veiculo_id', $state)->whereBetween('data',[$get('inicio'),$get('fim')])->sum('valor');
                           // dd('$total_custo');
                                $set('total_locacao', $total_locacao);
                                $set('total_custo', $total_custo );
                                $set('lucro', $total_locacao - $total_custo);


                        }),
                  //  Forms\Components\TextInput::make('total_locacao')
                        Money::make('total_locacao')
                        ->currencyMask(thousandSeparator: '.',decimalSeparator: ',',precision: 2)
                        ->readOnly()
                      //  ->money('BRL')
                        ->label('Total de Locação R$:'),
                        Money::make('total_custo')
                        ->currencyMask(thousandSeparator: '.',decimalSeparator: ',',precision: 2)
                        ->readOnly()
                      //  ->money('BRL')
                        ->label('Total de Custos R$:'),
                        Money::make('lucro')
                        ->currencyMask(thousandSeparator: '.',decimalSeparator: ',',precision: 2)
                        ->readOnly()
                       // ->money('BRL')
                        ->label('Lucro Real R$:'),



                ])->columns(2)->inlineLabel();
    }




}





