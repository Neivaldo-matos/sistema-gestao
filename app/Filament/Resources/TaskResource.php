<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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

            Tables\Columns\IconColumn::make('concluida')
                ->label('Status')
                ->boolean(), // Transforma o 0 ou 1 em ícone de check verde ou X vermelho

            Tables\Columns\TextColumn::make('created_at')
                ->label('Criada em')
                ->dateTime('d/m/Y H:i')
                ->sortable(),
            ])
            ->filters([
                // Aqui podemos adicionar filtros por status, 
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
