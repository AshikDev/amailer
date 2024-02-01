<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages;
use App\Http\Controllers\SendController;
use App\Models\Job;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;
    protected static ?string $navigationGroup = 'Send E-mail';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('category_id')->relationship('category', 'name')->required(),
                    Select::make('template_id')->relationship('template', 'name')->required(),
                    Select::make('send_as')->options(Job::getSendAsOptions())->required()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')->sortable()->searchable(),
                TextColumn::make('template.name')->sortable()->searchable(),
                TextColumn::make('send_as')->sortable()->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Send')
                    ->action(
                        function (Job $record) {
                            (new SendController())->index($record);
                        }
                    )->icon('heroicon-o-paper-airplane'),
                EditAction::make(),
                DeleteAction::make()
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
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }
}
