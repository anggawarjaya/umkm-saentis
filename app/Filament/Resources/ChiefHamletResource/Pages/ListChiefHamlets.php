<?php

namespace App\Filament\Resources\ChiefHamletResource\Pages;

use App\Filament\Resources\ChiefHamletResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChiefHamlets extends ListRecords
{
    protected static string $resource = ChiefHamletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
