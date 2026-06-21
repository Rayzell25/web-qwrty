<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiteSetting extends EditRecord
{
    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Tampilkan file saat ini ke field uploader untuk key logo/favicon.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (in_array($data['key'] ?? null, ['logo', 'favicon'], true)) {
            $data['upload'] = $data['value'] ?? null;
        }

        return $data;
    }

    /**
     * Simpan path hasil upload ke kolom value.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (in_array($data['key'] ?? null, ['logo', 'favicon'], true)) {
            $data['value'] = $data['upload'] ?? null;
        }

        unset($data['upload']);

        return $data;
    }
}
