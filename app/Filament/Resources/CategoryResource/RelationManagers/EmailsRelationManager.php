<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmailsRelationManager extends RelationManager
{
    protected static string $relationship = 'emails';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('category_id')->relationship('category', 'name')->required(),
                    Textarea::make('account')->required(),
                    Toggle::make('is_active')->default(true)
                ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')->sortable(),
                TextColumn::make('account')->sortable()->searchable(),
                IconColumn::make('is_active')->sortable()
            ])
            ->filters([
                Filter::make('Active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),
                Filter::make('Inactive')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', false)),
                SelectFilter::make('category')->relationship('category', 'name')
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
