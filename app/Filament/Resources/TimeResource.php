<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Time;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Resources\TimeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TimeResource\RelationManagers;

class TimeResource extends Resource
{
    protected static ?string $model = Time::class;

    protected static ?string $navigationIcon = 'heroicon-m-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\Hidden::make('user_id')
                ->default(
                    auth()->id()
                ),
                TextInput::make('name')->required(),
                CheckboxList::make('time')
                ->options([
                    'tailwind' => 'Tailwind CSS',
                    'alpine' => 'Alpine.js',
                    'laravel' => 'Laravel',
                    'livewire' => 'Laravel Livewire',
                ])->columns(4)
                
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('time')->sortable()->searchable(),
                
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
            'index' => Pages\ListTimes::route('/'),
            'create' => Pages\CreateTime::route('/create'),
            'edit' => Pages\EditTime::route('/{record}/edit'),
        ];
    }
}
