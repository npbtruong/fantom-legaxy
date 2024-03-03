<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-m-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')->required(),
                TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                TextInput::make('phone_number')->required()->rules('numeric')->unique(ignoreRecord: true),
                TextInput::make('salon_name')->required()->unique(ignoreRecord: true),
                TextInput::make('address')->required()->unique(ignoreRecord: true),
                Select::make('role')->options([
                    'admin' => 'Admin',
                    'user' => 'User',
                    'banned' => 'Banned',
                ])->default('user')->required(),
                Fieldset::make('')
                ->schema([
                    TextInput::make('password')
                    ->password()
                    ->rules('confirmed')
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                    TextInput::make('password_confirmation')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create'),
                ]) 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('phone_number')->sortable()->searchable(),
                TextColumn::make('salon_name')->sortable()->searchable(),
                TextColumn::make('role')->sortable()->searchable()
                ->badge() // note cáu $state là dựa vào cái nơi nó đang đứng ví dụ ở đây là role
                ->color(function (string $state): string {
                    return match ($state) {
                        'admin' => 'success',
                        'user' => 'info',
                        'banned' => 'danger',
                        default => 'default',
                    };
                }),
                TextColumn::make('created_at')->date('d M Y'),
                TextColumn::make('updated_at'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
