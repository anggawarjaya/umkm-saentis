<?php

namespace App\Filament\Resources\BusinessProfileSubmissionResource\Pages;

use App\Filament\Resources\BusinessProfileSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBusinessProfileSubmission extends EditRecord
{
    protected static string $resource = BusinessProfileSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
