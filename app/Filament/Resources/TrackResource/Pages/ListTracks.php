<?php

namespace App\Filament\Resources\TrackResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\TrackResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTracks extends ListRecords
{
    protected static string $resource = TrackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            
            'Use Track' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    $query->where('status', true);
                }),

            'Un Use Track' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    $query->where('status', false);
                }),

            'All Track' => Tab::make(),
            
        ];
    }
}
