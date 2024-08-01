<?php

namespace App\Filament\Resources\BusinessProfileHamletResource\Pages;

use App\Filament\Resources\BusinessProfileHamletResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBusinessProfileHamlet extends EditRecord
{
    protected static string $resource = BusinessProfileHamletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
