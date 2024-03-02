<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Track;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\CheckboxColumn;
use App\Filament\Resources\TrackResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TrackResource\RelationManagers;

class TrackResource extends Resource
{
    protected static ?string $model = Track::class;

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
                TextInput::make('name')->required()
                ->unique(function(){
                    $track_user_id = Track::pluck('user_id')->all();
                    $user_id = auth()->user()->id;
                    return in_array($user_id, $track_user_id) ? null : false;
                },ignoreRecord: true),

                TimePicker::make('time')->required()->hoursStep(1)->minutesStep(30)
                ->unique(function(){
                    $track_user_id = Track::pluck('user_id')->all();
                    $user_id = auth()->user()->id;
                    return in_array($user_id, $track_user_id) ? null : false;
                },ignoreRecord: true),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('time')->dateTime('H:i')->searchable()->sortable(),
                CheckboxColumn::make('status')->label('Apply Track'),
            ])->defaultSort('time')
            ->filters([
                //
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
            // ->query(function (Track $query) {
            //     if(auth()->user()->role == 'admin'){
            //         return $query;
            //     }
            //     return $query->where('user_id', auth()->user()->id);
            // });
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
            'index' => Pages\ListTracks::route('/'),
            'create' => Pages\CreateTrack::route('/create'),
            'edit' => Pages\EditTrack::route('/{record}/edit'),
        ];
    }
}
