<?php

namespace App\Filament\Resources;

use Filament\Tables\Filters\TernaryFilter; // tem que ficar no topo
use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            // Criamos um cartão para organizar os campos
            Forms\Components\Section::make('Informações da Tarefa')
                ->description('Preencha os dados básicos da tarefa abaixo.')
                ->schema([
                    Forms\Components\TextInput::make('titulo')
                        ->label('Título da Tarefa')
                        ->required() // Campo obrigatório
                        ->maxLength(255),

                    Forms\Components\Textarea::make('descricao')
                        ->label('Descrição Detalhada')
                        ->rows(3),

                    Forms\Components\Toggle::make('concluida')
                        ->label('Está concluída?')
                        ->default(false),

                    Forms\Components\Select::make('category_id')
                        ->relationship('category','nome') // aqui está a mágica da conexão
                        ->label('Categoria')
                        ->searchable()
                        ->preload(),
                    //     
                    DatePicker::make('due_date')
                        ->label('Data Vencimento')
                        ->native(false) // Abre um calendário elegante em vez do seletor padrão
                        ->displayFormat('d/m/Y'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('titulo')
                ->label('Título')
                ->searchable(), // Adiciona barra de busca para este campo

            Tables\Columns\CheckboxColumn::make('concluida')
                ->label('Concluída'),
                
            Tables\Columns\TextColumn::make('created_at')
                ->label('Criada em')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true), // Esconde por padrão para limpar a tela

            Tables\Columns\TextColumn::make('due_date')
                ->label('Vencimento')
                ->date('d/m/Y') // Formata a data para o padraão brasileiro
                ->color(fn ($record) => $record && $record->due_date < now() && !$record->concluida ? 'danger' : 'gray' )
                ->sortable(),
            ])
            ->filters([
                // Aqui podemos adicionar filtros por status, 
                // Filtro para mostrar concluidas, pendentes ou todas
                TernaryFilter::make('concluida')
                    ->label('Estado da Tarefa')
                    ->placeholder('Todas')
                    ->trueLabel('Apenas Concluídas')
                    ->falseLabel('Apenas Pendentes'),

                // Filtro para tarefas que vencem HOJE
                Filter::make('due_date')
                    ->form([
                        DatePicker::make('vencimento_de')->label('Vence a partir de'),
                        DatePicker::make('vencimento_ate')->label('Vence até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['vencimento_de'],
                                fn (Builder $query, $date): Builder => $query->whereDate('due_date', '<=', $date),
                            )    
                            ->when(
                                $data['vencimento_ate'],
                                fn (Builder $query, $date): Builder => $query->whereDate('due_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['vencimento_de'] ?? null) {
                            $indicators[] = 'Inicio: ' . \Carbon\Carbon::parse($data['vencimento_de'])->format('d/m/Y');
                        }
                        if ($data['vencimento_ate'] ?? null) {
                            $indicators[] = 'Fim: ' . \Carbon\Carbon::parse($data['vencimento_ate'])->format('d/m/Y');
                        }
                        return $indicators;
                    }),

                    //->label('Vencem Hoje')
                    //->query(fn (Builder $query): Builder => $query->whereDate('due_date', now())),

                // Filtro por categoria
            SelectFilter::make('category_id')
                ->label('Categoria')
                ->relationship('category', 'nome'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
