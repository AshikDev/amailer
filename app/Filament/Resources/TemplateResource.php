<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\RelationManagers\JobsRelationManager;
use App\Filament\Resources\TemplateResource\Pages;
use App\Models\Template;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('subject')->required(),
                    RichEditor::make('body')->required(),
                    Toggle::make('is_active')->default(true)
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('subject')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('body')->sortable()->html(true),
                Tables\Columns\TextColumn::make('is_active')->sortable()
            ])
            ->filters([
                Filter::make('Active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),
                Filter::make('Inactive')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', false))
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make()
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
            JobsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }
}
