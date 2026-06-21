<?php

namespace App\Filament\Resources\InvoiceRecordResource\Pages;

use App\Filament\Resources\InvoiceRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoiceRecord extends EditRecord
{
    protected static string $resource = InvoiceRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
