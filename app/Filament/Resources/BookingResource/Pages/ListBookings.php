<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            
            'Status' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    $query->where('status', false);
                }),
            'Done Status' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    $query->where('status', true);
                }),
            'All' => Tab::make(),
        ];
    }
}
