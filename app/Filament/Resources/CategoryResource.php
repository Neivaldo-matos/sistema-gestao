<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // formulario de create
                Forms\Components\TextInput::make('nome')
                        ->label('Nome da Categoria')
                        ->required() // Campo obrigatório
                        ->maxLength(24),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Adicione esta linha para o nome aparecer na lista
            Tables\Columns\TextColumn::make('nome')
                ->label('Nome da Categoria')
                ->searchable()
                ->sortable(),

            // Opcional: mostrar quando foi criada
            Tables\Columns\TextColumn::make('created_at')
                ->label('Criada em')
                ->dateTime('d/m/Y')
                ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
