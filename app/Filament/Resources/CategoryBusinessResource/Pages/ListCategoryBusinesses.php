<?php

namespace App\Filament\Resources\CategoryBusinessResource\Pages;

use App\Filament\Resources\CategoryBusinessResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoryBusinesses extends ListRecords
{
    protected static string $resource = CategoryBusinessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
