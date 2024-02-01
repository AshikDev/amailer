<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MailsRelationManager extends RelationManager
{
    protected static string $relationship = 'mails';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('category_id')->relationship('category', 'name'),
                    TextInput::make('name')->required(),
                    TextInput::make('email')->email()->required(),
                    Toggle::make('is_active')->default(true)
                ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->sortable(),
                TextColumn::make('email')->sortable(),
                IconColumn::make('is_active')->sortable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
