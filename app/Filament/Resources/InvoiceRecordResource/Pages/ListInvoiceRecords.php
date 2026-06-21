<?php

namespace App\Filament\Resources\InvoiceRecordResource\Pages;

use App\Filament\Resources\InvoiceRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvoiceRecords extends ListRecords
{
    protected static string $resource = InvoiceRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
