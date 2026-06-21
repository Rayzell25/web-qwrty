<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSiteSetting extends CreateRecord
{
    protected static string $resource = SiteSettingResource::class;

    /**
     * Simpan path hasil upload ke kolom value (untuk key logo/favicon).
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (in_array($data['key'] ?? null, ['logo', 'favicon'], true)) {
            $data['value'] = $data['upload'] ?? null;
        }

        unset($data['upload']);

        return $data;
    }
}
