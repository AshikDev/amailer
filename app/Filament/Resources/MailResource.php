<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MailResource\Pages;
use App\Models\Mail;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MailResource extends Resource
{
    protected static ?string $model = Mail::class;
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public static function canViewAny(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('category_id')->relationship('category', 'name')->required(),
                    TextInput::make('name')->required(),
                    TextInput::make('email')->email()->required(),
                    Toggle::make('is_active')->default(true)
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                IconColumn::make('is_active')->sortable()
            ])
            ->filters([
                Filter::make('Active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),
                Filter::make('Inactive')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', false)),
                SelectFilter::make('category')->relationship('category', 'name')
            ])
            ->actions([
                DeleteAction::make(),
                EditAction::make()
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListMails::route('/'),
            'create' => Pages\CreateMail::route('/create'),
            'edit' => Pages\EditMail::route('/{record}/edit'),
        ];
    }
}
