<?php

namespace App\Filament\Resources\BusinessProfileHamletResource\Pages;

use App\Filament\Resources\BusinessProfileHamletResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessProfileHamlets extends ListRecords
{
    protected static string $resource = BusinessProfileHamletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
