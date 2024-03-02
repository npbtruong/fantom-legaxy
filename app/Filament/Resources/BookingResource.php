<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Track;
use App\Models\Booking;
use App\Models\Service;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\CheckboxColumn;
use App\Filament\Resources\BookingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookingResource\RelationManagers;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-m-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            TextInput::make('name')
                ->label('Name')
                ->required(),

            TextInput::make('phone_number')
                ->label('Phone Number')
                ->required(),

            TextInput::make('guests')
                ->label('Number of Guests')
                ->numeric()
                ->required(),

            Forms\Components\Hidden::make('user_id')
                ->default(
                    auth()->id()
                ),

            Select::make('service_id')
                ->multiple()
                ->label('Service')
                ->options(Service::all()->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'name' => $service->name . ' : ' . $service->price . '$',
                    ];
                })->pluck('name', 'id')->toArray())
                ->required(),
            DatePicker::make('booking_date')
                ->label('Booking Date')
                ->live()
                ->native(false)
                ->minDate(now())
                ->required()
                ->suffixIcon('heroicon-m-calendar-days'),

            Select::make('track_id')
                ->label('Track')
                ->live()
                ->options(function(Get $get){
                    if(!$get('booking_date')){
                        return;
                    };

                    $bookingDates = $get('booking_date');
                    $track_id_exclude = Booking::where('booking_date', $bookingDates)->pluck('track_id')->unique()->all();
                        

                    return Track::whereNotIn('id', $track_id_exclude)->where('status', true)->get()->map(function ($track) {
                        return [
                            'id' => $track->id ,
                            'name' => substr($track->time, 0, -3) . ' ⏰',
                        ];
                    })->pluck('name', 'id')->toArray();
                })
                ->required(),

            TextInput::make('note')
                ->label('Note'),

            
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('user_id')->sortable()->searchable()->hidden(auth()->user()->role != 'admin'),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('phone_number')->sortable()->searchable(),
                TextColumn::make('service_id')->label('Service')
                ->formatStateUsing(function ($state) {
                    $serviceIds = explode(',', $state);
                    $serviceCount = Service::whereIn('id', $serviceIds)->pluck('name')->implode(', ');
                    $serviceCountPrice = Service::whereIn('id', $serviceIds)->pluck('price')->sum();
                    return $serviceCount . ' : ' . $serviceCountPrice . '$';
                }),
                
                CheckboxColumn::make('status'),
                TextColumn::make('booking_date')->date('d/m/Y')->sortable()->searchable(),
                TextColumn::make('track.time')->dateTime('H:i')->sortable()->searchable(),
            ])->defaultSort('booking_date')
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
            ->query(function (Booking $query) {
                if(auth()->user()->role == 'admin'){
                    return $query;
                }
                return $query->where('user_id', auth()->user()->id);
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
