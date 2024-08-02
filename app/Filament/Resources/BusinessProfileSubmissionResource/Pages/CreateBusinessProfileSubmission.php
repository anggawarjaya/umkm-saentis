<?php

namespace App\Filament\Resources\BusinessProfileSubmissionResource\Pages;

use App\Filament\Resources\BusinessProfileApprovedResource;
use App\Filament\Resources\BusinessProfileSubmissionResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateBusinessProfileSubmission extends CreateRecord
{
    protected static string $resource = BusinessProfileSubmissionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $owner = auth()->user()->id;

        $data['approved'] = false;
        $data['user_id'] = $owner; 
        return $data;
    }

    protected function afterCreate(): void
    {
        $recipient = auth()->user();

        Notification::make()
            ->success()
            ->title('Data UMKM '.$this->record->name.' berhasil dibuat')
            ->body('Kepada Saudara '.User::find($this->record->user_id)->name.' mohon ditunggu untuk dilakukan pengecekan terlebih dahulu.')
            ->actions([
                Action::make('TandaiSudahDiBaca')
                    ->button()
                    ->markAsRead(),
                Action::make('Lihat')
                    ->button()
                    ->url(fn() => BusinessProfileSubmissionResource::getUrl('edit', ['record' => $this->record->id]), shouldOpenInNewTab:true),
            ])
            ->sendToDatabase($recipient);

        $recipients = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'super_admin', 'kepala_dusun']);
        })->where('id', '!=', $recipient->id)->get();

        Notification::make()
            ->success()
            ->title('Data UMKM '.$this->record->name.' berhasil ditambahkan oleh '.$recipient->name)
            ->body('Mohon segera dilakukan verifikasi.')
            ->actions([
                Action::make('TandaiSudahDiBaca')
                    ->button()
                    ->markAsRead(),
                Action::make('Lihat')
                    ->button()
                    ->url(fn() => BusinessProfileApprovedResource::getUrl('setujui', ['record' => $this->record->id]), shouldOpenInNewTab:true),
            ])
            ->sendToDatabase($recipients);
    }
}
