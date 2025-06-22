<?php

namespace App\Filament\Resources;

use App\Filament\Exports\LocacaoExporter;
use App\Filament\Resources\LocacaoResource\Pages;
use App\Filament\Resources\LocacaoResource\RelationManagers;
use App\Filament\Resources\LocacaoResource\RelationManagers\OcorrenciaRelationManager;
use App\Models\Cliente;
use App\Models\Estado;
use App\Models\Locacao;
use App\Models\Veiculo;
use Carbon\Carbon;
use DateTime;
use FFI;
use Filament\Tables\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Laravel\SerializableClosure\Serializers\Native;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Illuminate\Support\Str;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class LocacaoResource extends Resource
{
    protected static ?string $model = Locacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Locações';

    protected static ?string $navigationGroup = 'Locar';



    public static function form(Form $form): Form
    {
        return $form
            ->columns(null)
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informações da Locação')
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
                                                    ->searchable()
                                                    ->autofocus(true)
                                                    ->extraInputAttributes(['tabindex' => 1])
                                                    ->native(false)
                                                    ->columnSpan([
                                                        'xl' => 2,
                                                        '2xl' => 2,
                                                    ])
                                                    ->live()
                                                    ->required(false)
                                                    ->relationship('cliente', 'nome')
                                                    ->createOptionForm([
                                                        Grid::make([
                                                            'xl' => 3,
                                                            '2xl' => 3,
                                                        ])
                                                            ->schema([
                                                                Forms\Components\TextInput::make('nome')
                                                                    ->label('Nome')
                                                                    ->columnSpan([
                                                                        'xl' => 2,
                                                                        '2xl' => 2,
                                                                    ])
                                                                    ->maxLength(255),
                                                                Forms\Components\TextInput::make('cpf_cnpj')
                                                                    ->label('CPF/CNPJ')
                                                                    ->mask(RawJs::make(<<<'JS'
                                                                $input.length > 14 ? '99.999.999/9999-99' : '999.999.999-99'
                                                            JS))
                                                                    ->rule('cpf_ou_cnpj'),
                                                                Forms\Components\Textarea::make('endereco')
                                                                    ->label('Endereço')
                                                                    ->columnSpanFull(),
                                                                Forms\Components\Select::make('estado_id')
                                                                    ->label('Estado')
                                                                    ->native(false)
                                                                    ->searchable()
                                                                    ->required()
                                                                    ->default(33)
                                                                    ->options(Estado::all()->pluck('nome', 'id')->toArray())
                                                                    ->live(),
                                                                Forms\Components\Select::make('cidade_id')
                                                                    ->label('Cidade')
                                                                    ->default(3243)
                                                                    ->native(false)
                                                                    ->searchable()
                                                                    ->required()
                                                                    ->options(function (callable $get) {
                                                                        $estado = Estado::find($get('estado_id'));
                                                                        if (!$estado) {
                                                                            return Estado::all()->pluck('nome', 'id');
                                                                        }
                                                                        return $estado->cidade->pluck('nome', 'id');
                                                                    })
                                                                    ->live(),
                                                                Forms\Components\TextInput::make('telefone_1')
                                                                    ->label('Telefone 1')
                                                                    ->tel()
                                                                    ->mask('(99)99999-9999'),
                                                                Forms\Components\TextInput::make('telefone_2')
                                                                    ->tel()
                                                                    ->label('Telefone 2')
                                                                    ->tel()
                                                                    ->mask('(99)99999-9999'),
                                                                Forms\Components\TextInput::make('email')
                                                                    ->columnSpan([
                                                                        'xl' => 2,
                                                                        '2xl' => 2,
                                                                    ])
                                                                    ->email()
                                                                    ->maxLength(255),
                                                                Forms\Components\TextInput::make('rede_social')
                                                                    ->label('Rede Social'),
                                                                Forms\Components\TextInput::make('cnh')
                                                                    ->label('CNH'),
                                                                Forms\Components\DatePicker::make('validade_cnh')
                                                                    ->label('Valiade da CNH'),
                                                                Forms\Components\TextInput::make('rg')
                                                                    ->label('RG'),
                                                                Forms\Components\TextInput::make('exp_rg')
                                                                    ->label('Orgão Exp.'),
                                                                Forms\Components\Select::make('estado_exp_rg')
                                                                    ->searchable()
                                                                    ->label('UF - Expedidor')
                                                                    ->options(Estado::all()->pluck('nome', 'id')->toArray()),
                                                                FileUpload::make('img_cnh')
                                                                    ->columnSpan([
                                                                        'xl' => 2,
                                                                        '2xl' => 2,
                                                                    ])
                                                                    ->downloadable()
                                                                    ->label('Foto CNH'),
                                                                Forms\Components\DatePicker::make('data_nascimento')
                                                                    ->label('Data de Nascimento'),
                                                            ])
                                                    ])
                                                    ->afterStateUpdated(function ($state) {
                                                        if ($state != null) {
                                                            $cliente = Cliente::find($state);
                                                            Notification::make()
                                                                ->title('ATENÇÃO')
                                                                ->body('A validade da CNH do cliente selecionado: ' . Carbon::parse($cliente->validade_cnh)->format('d/m/Y'))
                                                                ->warning()
                                                                ->persistent()
                                                                ->send();
                                                        }
                                                    }),
                                                Forms\Components\Select::make('veiculo_id')
                                                    ->required(false)
                                                    ->label('Veículo')
                                                    ->live(onBlur: true)
                                                    ->relationship(
                                                        name: 'veiculo',
                                                        modifyQueryUsing: function (Builder $query, $context) {
                                                            if ($context === 'create') {
                                                                $query->where('status', 1)->where('status_locado', 0)->orderBy('modelo')->orderBy('placa');
                                                            } else {
                                                                $query->where('status', 1)->orderBy('modelo')->orderBy('placa');
                                                            }
                                                        }
                                                    )
                                                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->modelo} {$record->placa}")
                                                    ->searchable(['modelo', 'placa'])
                                                    ->afterStateUpdated(function (Set $set, $state) {
                                                        $veiculo = Veiculo::find($state);
                                                        if ($state != null) {
                                                            $set('km_saida', $veiculo->km_atual);
                                                        }
                                                    })
                                                    ->columnSpan([
                                                        'xl' => 2,
                                                        '2xl' => 2,
                                                    ]),
                                                Forms\Components\Radio::make('forma_locacao')
                                                    ->label('Forma de Locação')
                                                    ->options([
                                                        '1' => 'Diária',
                                                        '2' => 'Semanal',
                                                    ])
                                                    ->inline()
                                                    ->inlineLabel(false)
                                                    ->live()
                                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                        if ($state == 1) {
                                                        } else {
                                                        }
                                                    })
                                                    ->default(1)
                                                    ->required()
                                                    ->columnSpan([
                                                        'xl' => 2,
                                                        '2xl' => 2,
                                                    ]),
                                                Forms\Components\TextInput::make('qtd_semanas')
                                                    ->extraInputAttributes(['style' => 'font-weight: bolder; font-size: 1rem; color: #CF9A16;'])
                                                    ->label('Qtd Semanas')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                                        $set('data_retorno', Carbon::parse($get('data_saida'))->addWeeks($state)->format('Y-m-d'));
                                                        $set('hora_retorno', Carbon::parse($get('hora_saida'))->format('H:i'));
                                                        $set('qtd_diarias', Carbon::parse($get('data_saida'))->addWeeks($state)->diffInDays(Carbon::parse($get('data_saida'))));

                                                        $carro = Veiculo::find($get('veiculo_id'));
                                                        $set('valor_total', ($carro->valor_semana * $state));
                                                        $set('valor_desconto', 0);
                                                        $set('valor_total_desconto', ($carro->valor_semana * $state));
                                                        Notification::make()
                                                            ->title('ATENÇÃO')
                                                            ->body(
                                                                'Para as informações escolhida temos:<br>
                                                        <b>' . $state . ' SEMANA(S) / ' . $get('qtd_diarias') . ' DIA(AS), e data de retorno do veículo para ' . Carbon::parse($get('data_saida'))->addWeeks($state)->format('d/m/Y') . '</b><br>
                                                        '
                                                            )
                                                            ->danger()
                                                            ->persistent()
                                                            ->send();
                                                    })
                                                    ->hidden(fn(Get $get) => $get('forma_locacao') == 1)
                                                    ->required(),
                                            ]),
                                    ]),
                                Fieldset::make('Datas e Valores')
                                    ->schema([
                                        Grid::make([
                                            'xl' => 4,
                                            '2xl' => 4,
                                        ])
                                            ->schema([

                                                Forms\Components\DatePicker::make('data_saida')
                                                    ->default(Carbon::today())
                                                    ->displayFormat('d/m/Y')
                                                    ->label('Data Saída')
                                                    ->required(false),

                                                Forms\Components\TimePicker::make('hora_saida')
                                                    ->seconds(false)
                                                    ->default(Carbon::now())
                                                    ->label('Hora Saída')
                                                    ->required(false),
                                                Forms\Components\DatePicker::make('data_retorno')
                                                    ->displayFormat('d/m/Y')
                                                    ->label('Data Retorno')
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                                        $dt_saida = Carbon::parse($get('data_saida'));
                                                        $dt_retorno = Carbon::parse($get('data_retorno'));
                                                        $qtd_dias = $dt_retorno->diffInDays($dt_saida);
                                                        $set('qtd_diarias', $qtd_dias);

                                                        $carro = Veiculo::find($get('veiculo_id'));
                                                        $set('valor_total', ($carro->valor_diaria * $qtd_dias));
                                                        $set('valor_desconto', 0);
                                                        $set('valor_total_desconto', ($carro->valor_diaria * $qtd_dias));

                                                        ### CALCULO DOS DIAS E SEMANAS
                                                        $diferencaEmDias = $dt_saida->diffInDays($dt_retorno);
                                                        // Calculando a diferença em semanas
                                                        $diferencaEmSemanas = $diferencaEmDias / 7;

                                                        // Arredondando para baixo para obter o número inteiro de semanas
                                                        $semanasCompletas = floor($diferencaEmSemanas);
                                                        // Calculando os dias restantes (módulo 7)
                                                        $diasRestantes = $diferencaEmDias % 7;
                                                        //Calculando os meses
                                                        $mesesCompleto = $diferencaEmDias / 30;
                                                        //Calculando os meses em número inteiro
                                                        $mesesCompleto = floor($mesesCompleto);
                                                        //Calculando semanas restantes
                                                        $diasRestantesMeses = $diferencaEmDias % 30;

                                                        Notification::make()
                                                            ->title('ATENÇÃO')
                                                            ->body(
                                                                'Para as datas escolhida temos:<br>
                                                            <b>' . $qtd_dias . ' DIA(AS).</b><br>
                                                            <b>' . $semanasCompletas . ' SEMANA(AS) e ' . $diasRestantes . ' DIA(AS). </b> <br>
                                                            <b>' . $mesesCompleto . ' MÊS/MESES  e ' . $diasRestantesMeses . ' DIA(AS).</b><br>
                                                        '
                                                            )
                                                            ->danger()
                                                            ->persistent()
                                                            ->send();
                                                    })
                                                    // ->hidden(fn(Get $get) => $get('forma_locacao') == 2)
                                                    ->required(false),
                                                Forms\Components\TimePicker::make('hora_retorno')
                                                    ->seconds(false)
                                                    ->default(Carbon::now())
                                                    ->label('Hora Retorno')
                                                    // ->hidden(fn(Get $get) => $get('forma_locacao') == 2)
                                                    ->required(false),
                                                Forms\Components\TextInput::make('km_saida')
                                                    ->label('Km Saída')
                                                    ->required(false),
                                                Forms\Components\TextInput::make('km_retorno')
                                                    ->hidden(fn($context) => $context === 'create')
                                                    ->label('Km Retorno'),
                                                Forms\Components\TextInput::make('valor_caucao')
                                                    ->label('Valor Caução')
                                                    ->hint('Caso exista, registre o valor do caução')
                                                    ->columnSpan([
                                                        'xl' => 2,
                                                        '2xl' => 2,
                                                    ])
                                                    ->numeric()
                                                    ->prefix('R$')
                                                    ->inputMode('decimal')
                                                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),

                                            ]),
                                        Forms\Components\TextInput::make('qtd_diarias')
                                            ->extraInputAttributes(['style' => 'font-weight: bolder; font-size: 1rem; color: #CF9A16;'])
                                            ->label('Qtd Diárias')
                                            ->readOnly()
                                            // ->hidden(fn(Get $get) => $get('forma_locacao') == 2)
                                            ->required(false),

                                        Forms\Components\TextInput::make('valor_total')
                                            ->extraInputAttributes(['style' => 'font-weight: bolder; font-size: 1rem; color: #D33644;'])
                                            ->label('Valor Total')
                                            ->prefix('R$')
                                            ->inputMode('decimal')
                                            ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                                            ->readOnly()
                                            ->required(false),
                                        Forms\Components\TextInput::make('valor_desconto')
                                            ->extraInputAttributes(['style' => 'font-weight: bolder; font-size: 1rem; color: #3668D3;'])
                                            ->label('Desconto')
                                            ->numeric()
                                            ->prefix('R$')
                                            ->inputMode('decimal')
                                            ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                                            ->required(true)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($state, callable $set, Get $get,) {
                                                $set('valor_total_desconto', ((float)$get('valor_total') - (float)$get('valor_desconto')));
                                            }),
                                        Forms\Components\TextInput::make('valor_total_desconto')
                                            ->extraInputAttributes(['style' => 'font-weight: bolder; font-size: 1rem; color: #17863E;'])
                                            ->label('Valor Total com Desconto')
                                            ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                                            ->numeric()
                                            ->prefix('R$')
                                            ->inputMode('decimal')
                                            ->readOnly()
                                            ->required(false),
                                        Forms\Components\Textarea::make('obs')
                                            ->autosize()
                                            ->columnSpanFull()
                                            ->label('Observações'),
                                    ]),
                                Fieldset::make('Financeiro')
                                    ->schema([
                                        Grid::make([
                                            'xl' => 4,
                                            '2xl' => 4,
                                        ])
                                            ->schema([
                                                Forms\Components\Toggle::make('status_financeiro')
                                                    ->live()
                                                    ->disabled(fn(string $context): bool => $context === 'edit')
                                                    ->afterStateUpdated(
                                                        function (Get $get, Set $set, $state) {
                                                            if ($state == true) {
                                                                $set('valor_total_financeiro', ((float)$get('valor_total_desconto')));
                                                            } else {
                                                                $set('valor_parcela_financeiro', 0);
                                                                $set('parcelas_financeiro', ' ');
                                                                $set('formaPgmto_financeiro', '');
                                                                $set('valor_total_financeiro', 0);
                                                            }
                                                        }
                                                    )
                                                    ->columnSpan([
                                                        'xl' => 1,
                                                        '2xl' => 1,
                                                    ])
                                                    ->label('Desejar lançar no financeiro?'),
                                                Forms\Components\Toggle::make('status_pago_financeiro')
                                                    ->hidden(fn(Get $get): bool => !$get('status_financeiro'))
                                                    ->disabled(fn(string $context): bool => $context === 'edit')
                                                    ->live()
                                                    ->afterStateUpdated(
                                                        function (Get $get, Set $set, $state) {
                                                            if ($state == true) {
                                                                $set('parcelas_financeiro', 1);
                                                                $set('valor_parcela_financeiro', ((float)$get('valor_total_desconto')));
                                                                $set('data_vencimento_financeiro', Carbon::now()->format('Y-m-d'));
                                                            } else {
                                                                $set('valor_parcela_financeiro', '');
                                                                $set('parcelas_financeiro', ' ');
                                                                $set('formaPgmto_financeiro', '');
                                                            }
                                                        }
                                                    )
                                                    ->label('Recebido'),
                                                Forms\Components\Select::make('proxima_parcela')
                                                    ->hidden(fn(Get $get): bool => !$get('status_financeiro'))
                                                    ->disabled(fn(string $context): bool => $context === 'edit')
                                                    ->options([
                                                        '7' => 'Semanal',
                                                        '15' => 'Quinzenal',
                                                        '30' => 'Mensal',
                                                    ])
                                                    ->default(7)
                                                    ->label('Próximas Parcelas'),
                                                Forms\Components\TextInput::make('parcelas_financeiro')
                                                    ->hidden(fn(Get $get): bool => !$get('status_financeiro'))
                                                    ->disabled(fn(string $context): bool => $context === 'edit')
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(
                                                        function (Get $get, Set $set) {
                                                            $set('valor_parcela_financeiro', ((float)($get('valor_total_financeiro') / $get('parcelas_financeiro'))));
                                                            $set('data_vencimento_financeiro',  Carbon::now()->addDays($get('proxima_parcela'))->format('Y-m-d'));
                                                        }
                                                    )
                                                    ->numeric()
                                                    ->label('Qtd Parcelas')
                                                    ->required(fn(Get $get): bool => $get('status_financeiro')),
                                                Forms\Components\Select::make('formaPgmto_financeiro')
                                                    ->hidden(fn(Get $get): bool => !$get('status_financeiro'))
                                                    ->disabled(fn(string $context): bool => $context === 'edit')
                                                    ->default(4)
                                                    ->label('Forma de Pagamento')
                                                    ->required(fn(Get $get): bool => $get('status_financeiro'))
                                                    ->options([
                                                        1 => 'Dinheiro',
                                                        2 => 'Pix',
                                                        3 => 'Cartão',
                                                        4 => 'Boleto',
                                                    ]),
                                                Forms\Components\DatePicker::make('data_vencimento_financeiro')
                                                    ->hidden(fn(Get $get): bool => !$get('status_financeiro'))
                                                    ->disabled(fn(string $context): bool => $context === 'edit')
                                                    ->required(fn(Get $get): bool => $get('status_financeiro'))
                                                    ->displayFormat('d/m/Y')
                                                    ->label("Vencimento da 1º"),
                                                Forms\Components\TextInput::make('valor_parcela_financeiro')
                                                    ->hidden(fn(Get $get): bool => !$get('status_financeiro'))
                                                    ->disabled(fn(string $context): bool => $context === 'edit')
                                                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                                                    ->numeric()
                                                    ->prefix('R$')
                                                    ->inputMode('decimal')
                                                    ->label('Valor da Parcela')
                                                    ->readOnly()
                                                    ->required(false),
                                                Forms\Components\TextInput::make('valor_total_financeiro')
                                                    ->hidden(fn(Get $get): bool => !$get('status_financeiro'))
                                                    ->disabled(fn(string $context): bool => $context === 'edit')
                                                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                                                    ->numeric()
                                                    ->prefix('R$')
                                                    ->inputMode('decimal')
                                                    ->label('Valor Total')
                                                    ->readOnly()
                                                    ->required(false),
                                            ]),
                                    ]),
                                Fieldset::make('Controle da Locação')
                                    ->schema([
                                        ToggleButtons::make('status')
                                            ->options([
                                                '0' => 'Locado',
                                                '1' => 'Finalizar',

                                            ])
                                            ->colors([
                                                '0' => 'danger',
                                                '1' => 'success',
                                            ])
                                            ->inline()
                                            ->default(0)
                                            ->label(''),
                                    ])

                            ]),

                        Forms\Components\Tabs\Tab::make('Assinaturas de Terceiros')
                            ->schema([
                                Fieldset::make('Assinaturas no Contrato')
                                    ->schema([
                                        Forms\Components\TextInput::make('testemunha_1')
                                            ->label('Testemunha 1')
                                            ->required(false),
                                        Forms\Components\TextInput::make('testemunha_1_rg')
                                            ->label('RG'),
                                        Forms\Components\TextInput::make('testemunha_2')
                                            ->label('Testemunha 2')
                                            ->required(false),
                                        Forms\Components\TextInput::make('testemunha_2_rg')
                                            ->label('RG'),
                                        Forms\Components\TextInput::make('fiador')
                                            ->label('Fiador')
                                            ->required(false),
                                            
                                    ]),

                                Fieldset::make('Dados Completo do Fiador')
                                    ->schema([
                                        Forms\Components\Textarea::make('dados_fiador')
                                            ->label('')
                                            ->autosize()
                                            ->columnSpanFull(),


                                    ]),
                            ]),



                        Forms\Components\Tabs\Tab::make('Ocorrências')
                            ->schema([
                                Fieldset::make('Ocorrências da Locação')
                                    ->schema([
                                        Repeater::make('ocorrencia')
                                            ->label('Ocorrências')
                                            ->schema([
                                                Grid::make([
                                                    'xl' => 3,
                                                    '2xl' => 3,
                                                ])
                                                    ->schema([
                                                        Select::make('tipo')
                                                            ->options([
                                                                'multa' => 'Multa',
                                                                'colisao' => 'Colisão',
                                                                'avaria' => 'Avaria',
                                                                'danos_terceiros' => 'Danos a Terceiros',
                                                                'outro' => 'Outros',
                                                            ]),
                                                        DateTimePicker::make('data_hora'),
                                                        TextInput::make('valor'),
                                                        Textarea::make('descricao')
                                                            ->columnSpan(2)
                                                            ->autosize()
                                                            ->label('Descrição'),
                                                        ToggleButtons::make('status')
                                                            ->label('Concluído?')
                                                            ->default(false)
                                                            ->boolean()
                                                            ->grouped()
                                                    ])
                                            ])
                                            ->columnSpanFull()
                                            ->addActionLabel('Novo')
                                    ]),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->headerActions([
                ExportAction::make()
                    ->exporter(LocacaoExporter::class)
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
                    ->columnMapping(false)
                    ->label('Exportar')
                    ->modalHeading('Confirmar exportação?')
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->label('ID'),

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
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('hora_saida')
                    ->alignCenter()
                    ->date('H:m')
                    ->sortable()
                    ->label('Hora Saída'),
                Tables\Columns\TextColumn::make('data_retorno')
                    ->badge()
                    ->label('Data Retorno')
                    ->date('d/m/Y')
                    ->color(static function ($state): string {
                        $hoje = Carbon::today();
                        $dataRetorno = Carbon::parse($state);
                        $qtd_dias = $hoje->diffInDays($dataRetorno, false);

                        if ($qtd_dias <= 3 && $qtd_dias >= 0) {
                            return 'warning';
                        }

                        if ($qtd_dias < 0) {
                            return 'danger';
                        }

                        if ($qtd_dias > 3) {
                            return 'success';
                        }
                    }),
                Tables\Columns\TextColumn::make('hora_retorno')
                    ->alignCenter()
                    ->date('H:m')
                    ->label('Hora Retorno'),
                Tables\Columns\TextColumn::make('Km_Percorrido')
                    ->label('Km Total')
                    ->getStateUsing(function (Locacao $record): int {

                        return ($record->km_retorno - $record->km_saida);
                    }),
                Tables\Columns\TextColumn::make('qtd_diarias')
                    ->label('Qtd Diárias'),
                Tables\Columns\TextColumn::make('valor_total_desconto')
                    ->summarize(Sum::make()->money('BRL')->label('Total'))
                    ->money('BRL')
                    ->label('Valor Total'),
                Tables\Columns\TextColumn::make('status')
                    ->summarize(Count::make())
                    ->Label('Status')
                    ->badge()
                    ->alignCenter()
                    ->color(fn(string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'success',
                    })
                    ->formatStateUsing(function ($state) {
                        if ($state == 0) {
                            return 'Locado';
                        }
                        if ($state == 1) {
                            return 'Finalizada';
                        }
                    }),
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
                Filter::make('Locados')
                    ->query(fn(Builder $query): Builder => $query->where('status', false))
                    ->default(1),
                SelectFilter::make('cliente')->searchable()->relationship('cliente', 'nome'),
                SelectFilter::make('veiculo')->searchable()->relationship('veiculo', 'placa'),
                Tables\Filters\Filter::make('datas')
                    ->form([
                        DatePicker::make('data_saida_de')
                            ->label('Saída de:'),
                        DatePicker::make('data_saida_ate')
                            ->label('Saída ate:'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['data_saida_de'],
                                fn($query) => $query->whereDate('data_saida', '>=', $data['data_saida_de'])
                            )
                            ->when(
                                $data['data_saida_ate'],
                                fn($query) => $query->whereDate('data_saida', '<=', $data['data_saida_ate'])
                            );
                    })

            ])
            ->actions([
                Tables\Actions\Action::make('Imprimir')
                    ->url(fn(Locacao $record): string => route('imprimirLocacao', $record))
                    ->label('Contrato 1')
                    ->openUrlInNewTab(),
                // Tables\Actions\Action::make('Imprimir')
                //     ->url(fn(Locacao $record): string => route('imprimirLocacao2', $record))
                //     ->label('Contrato 2')
                //     ->openUrlInNewTab(),
                Tables\Actions\EditAction::make()
                    ->modalHeading('Editar locação')
                    ->after(function ($data) {
                        if ($data['status'] == 1) {
                            $veiculo = Veiculo::find($data['veiculo_id']);
                            $veiculo->km_atual = $data['km_retorno'];
                            $veiculo->status_locado = 0;
                            $veiculo->save();
                        }
                    }),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        $veiculo = Veiculo::find($record->veiculo_id);
                        $veiculo->status_locado = 0;
                        $veiculo->save();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //  Tables\Actions\DeleteBulkAction::make(),
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
