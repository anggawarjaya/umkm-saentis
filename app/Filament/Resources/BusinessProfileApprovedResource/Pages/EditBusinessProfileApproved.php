<?php

namespace App\Filament\Resources\BusinessProfileApprovedResource\Pages;

use App\Filament\Resources\BusinessProfileApprovedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBusinessProfileApproved extends EditRecord
{
    protected static string $resource = BusinessProfileApprovedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
