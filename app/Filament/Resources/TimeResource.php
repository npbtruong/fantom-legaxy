<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Time;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Actions\Action;
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
                Forms\Components\Hidden::make('name')
                ->default(
                    'TimeUser'.auth()->id()
                ),
                CheckboxList::make('time')
                ->options([
                    '07:00:00' => '7 am',
                    '07:30:00' => '7:30 am',
                    '08:00:00' => '8 am',
                    '08:30:00' => '8:30 am',
                    '09:00:00' => '9 am',
                    '09:30:00' => '9:30 am',
                    '10:00:00' => '10 am',
                    '10:30:00' => '10:30 am',
                    '11:00:00' => '11 am',
                    '11:30:00' => '11:30 am',
                    '12:00:00' => '12 pm',
                    '12:30:00' => '12:30 pm',
                    '13:00:00' => '1 pm',
                    '13:30:00' => '1:30 pm',
                    '14:00:00' => '2 pm',
                    '14:30:00' => '2:30 pm',
                    '15:00:00' => '3 pm',
                    '15:30:00' => '3:30 pm',
                    '16:00:00' => '4 pm',
                    '16:30:00' => '4:30 pm',
                    '17:00:00' => '5 pm',
                    '17:30:00' => '5:30 pm',
                    '18:00:00' => '6 pm',
                    '18:30:00' => '6:30 pm',
                    '19:00:00' => '7 pm',
                    '19:30:00' => '7:30 pm',
                    '20:00:00' => '8 pm',
                    '20:30:00' => '8:30 pm',
                    '21:00:00' => '9 pm',
                    '21:30:00' => '9:30 pm',
                    '22:00:00' => '10 pm',
                    '22:30:00' => '10:30 pm',
                    '23:00:00' => '11 pm',
                ])->bulkToggleable()->columns(4)
    
                
                
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('user_id')->sortable()->searchable()->hidden(auth()->user()->role != 'admin'),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('time')->sortable()->searchable()->limit(50),
                
            ])->modifyQueryUsing(function (Builder $query) {
                $userRole = Auth::user()->role;
                if($userRole != 'admin'){
                    $query->where('user_id', Auth::user()->id);
                }
            })
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
