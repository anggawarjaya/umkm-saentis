<?php

namespace App\Filament\Resources\BusinessProfileApprovedResource\Pages;

use App\Filament\Resources\BusinessProfileApprovedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessProfileApproveds extends ListRecords
{
    protected static string $resource = BusinessProfileApprovedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
