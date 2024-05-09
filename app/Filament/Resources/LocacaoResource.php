<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocacaoResource\Pages;
use App\Filament\Resources\LocacaoResource\RelationManagers;
use App\Models\Cliente;
use App\Models\Locacao;
use App\Models\Veiculo;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Leandrocfe\FilamentPtbrFormFields\Money;

class LocacaoResource extends Resource
{
    protected static ?string $model = Locacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Locações';

    protected static ?string $navigationGroup = 'Locar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                   Fieldset::make('Dados da Locação')
                            ->schema([
                                Grid::make([
                                    'xl' => 4,
                                    '2xl' => 4,
                                ])
                                    ->schema([
                                Forms\Components\Select::make('cliente_id')
                                    ->label('Cliente')
                                    ->columnSpan('2')
                                    ->reactive()
                                    ->required()
                                    ->options(Cliente::all()->pluck('nome', 'id')->toArray())
                                    ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                        $cliente = Cliente::find($state);
                                        Notification::make()
                                            ->title('ATENÇÃO')
                                            ->body('A validade da CNH do cliente selecionado: '. Carbon::parse($cliente->validade_cnh)->format('d/m/Y') )
                                            ->warning()
                                            ->persistent()
                                            ->send();

                                    }),
                                Forms\Components\Select::make('veiculo_id')
                                    ->required()
                                    ->relationship(
                                        name: 'veiculo',
                                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('modelo')->orderBy('placa'),
                                    )
                                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->modelo} {$record->placa}")
                                    ->searchable(['modelo', 'placa'])
                                    ->columnSpan('2'),
                                Forms\Components\DatePicker::make('data_saida')
                                    ->displayFormat('d/m/Y')
                                    ->label('Data Saída')
                                    ->required(),
                                Forms\Components\TimePicker::make('hora_saida')
                                    ->label('Hora Saída')
                                    ->required(),
                                Forms\Components\DatePicker::make('data_retorno')
                                    ->displayFormat('d/m/Y')
                                    ->label('Data Retorno')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                        $dt_saida = Carbon::parse($get('data_saida'));
                                        $dt_retorno = Carbon::parse($get('data_retorno'));
                                        $qtd_dias = $dt_retorno->diffInDays($dt_saida);
                                        $set('qtd_diarias', $qtd_dias);

                                        $carro = Veiculo::find($get('veiculo_id'));
                                        $set('valor_total', ($carro->valor_diaria * $qtd_dias));

                                    })
                                    ->required(),
                                Forms\Components\TimePicker::make('hora_retorno')
                                    ->label('Hora Retorno')
                                    ->required(),
                                Forms\Components\TextInput::make('km_saida')
                                    ->label('Km Saída')
                                    ->required(),
                                Forms\Components\TextInput::make('km_retorno')
                                    ->label('Km Retorno'),

                            ]),
                        Fieldset::make('Valores')
                            ->schema([

                                Forms\Components\TextInput::make('qtd_diarias')
                                    ->extraInputAttributes(['tabindex' => 1, 'style' => 'font-weight: bolder; font-size: 1rem; color: #CF9A16;'])
                                    ->label('Qtd Diárias')
                                    ->readOnly()
                                    ->required(),
                                Forms\Components\TextInput::make('valor_total')
                                    ->extraInputAttributes(['tabindex' => 1, 'style' => 'font-weight: bolder; font-size: 1rem; color: #D33644;'])
                                    ->label('Valor Total')
                                    ->currencyMask(thousandSeparator: '.',decimalSeparator: ',',precision: 2)
                                    ->readOnly()
                                    ->required(),
                                Forms\Components\TextInput::make('valor_desconto')
                                    ->extraInputAttributes(['tabindex' => 1, 'style' => 'font-weight: bolder; font-size: 1rem; color: #3668D3;'])
                                    ->label('Desconto')
                                    ->currencyMask(thousandSeparator: '.',decimalSeparator: ',',precision: 2)
                                    ->required()
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function ($state, callable $set, Get $get,) {
                                         $set('valor_total_desconto', ((float)$get('valor_total') - (float)$get('valor_desconto')));

                                     }),
                                Forms\Components\TextInput::make('valor_total_desconto')
                                    ->extraInputAttributes(['tabindex' => 1, 'style' => 'font-weight: bolder; font-size: 1rem; color: #17863E;'])
                                    ->label('Valor Total com Desconto')
                                    ->currencyMask(thousandSeparator: '.',decimalSeparator: ',',precision: 2)
                                    ->readOnly()
                                    ->required(),
                                Forms\Components\Textarea::make('obs')
                                    ->label('Observações'),
                                Forms\Components\Toggle::make('status')
                                    ->label('Finalizar Locação'),

                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente.nome')
                    ->sortable()
                    ->searchable()
                    ->label('Cliente'),
                Tables\Columns\TextColumn::make('veiculo.modelo')
                    ->sortable()
                    ->searchable()
                    ->label('Veículo'),
                Tables\Columns\TextColumn::make('veiculo.placa')
                    ->searchable()
                     ->label('Placa'),
                Tables\Columns\TextColumn::make('data_saida')
                    ->label('Data Saída')
                    ->date(),
                Tables\Columns\TextColumn::make('hora_saida')
                    ->sortable()
                    ->label('Hora Saída'),
                Tables\Columns\TextColumn::make('data_retorno')
                    ->label('Data Retorno')
                    ->date(),
                Tables\Columns\TextColumn::make('hora_retorno')
                    ->label('Hora Retorno'),
                Tables\Columns\TextColumn::make('Km_Percorrido')
                    ->label('Km Percorrido')
                    ->getStateUsing(function (Locacao $record): int {

                        return  ($record->km_retorno - $record->km_saida);

                    }),
                Tables\Columns\TextColumn::make('qtd_diarias')
                    ->label('Qtd Diárias'),
                Tables\Columns\TextColumn::make('valor_total')
                    ->money('BRL')
                    ->label('Valor Total'),
                Tables\Columns\TextColumn::make('valor_desconto')
                    ->money('BRL')
                    ->label('Desconto'),
                Tables\Columns\TextColumn::make('valor_total_desconto')
                    ->summarize(Sum::make()->money('BRL')->label('Total'))
                    ->money('BRL')
                    ->label('Valor Total com Desconto'),
                Tables\Columns\ToggleColumn::make('status')
                    ->label('Finalizada')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Imprimir')
                ->url(fn (Locacao $record): string => route('imprimirLocacao', $record))
                ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLocacaos::route('/'),
        ];
    }
}
