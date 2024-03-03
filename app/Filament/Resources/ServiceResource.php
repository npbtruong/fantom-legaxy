<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Service;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Type\TrueType;

use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ServiceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ServiceResource\RelationManagers;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-m-pencil-square';

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
                    $service_user_id = Service::pluck('user_id')->all();
                    $user_id = auth()->user()->id;
                    return in_array($user_id, $service_user_id) ? null : false;
                },ignoreRecord: true),

                TextInput::make('price')
                ->required()
                ->numeric()
                ->suffixIcon('heroicon-m-currency-dollar'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('user_id')->sortable()->searchable()->hidden(auth()->user()->role != 'admin'),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('price')->searchable()->sortable(),
            ])
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
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $userRole = Auth::user()->role;
                if($userRole != 'admin'){
                    $query->where('user_id', Auth::user()->id);
                }
            });
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
