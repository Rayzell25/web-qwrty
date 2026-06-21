<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceRecordResource\Pages;
use App\Models\InvoiceRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceRecordResource extends Resource
{
    protected static ?string $model = InvoiceRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Invoice';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Invoice')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('invoice_number')
                        ->label('Nomor Invoice')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('product_name')
                        ->label('Nama Produk')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('customer_name')
                        ->label('Nama Customer')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('customer_email')
                        ->label('Email Customer')
                        ->email()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('customer_whatsapp')
                        ->label('WhatsApp Customer')
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('purchase_date')
                        ->label('Tanggal Pembelian'),
                    Forms\Components\TextInput::make('warranty_status')
                        ->label('Status Garansi')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('invoice_status')
                        ->label('Status Invoice')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->label('Nomor')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer_name')->label('Customer')->searchable(),
                Tables\Columns\TextColumn::make('product_name')->label('Produk')->searchable(),
                Tables\Columns\TextColumn::make('purchase_date')->label('Tgl Beli')->date()->sortable(),
                Tables\Columns\TextColumn::make('warranty_status')->label('Garansi')->badge(),
                Tables\Columns\TextColumn::make('invoice_status')->label('Status')->badge(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoiceRecords::route('/'),
            'create' => Pages\CreateInvoiceRecord::route('/create'),
            'edit' => Pages\EditInvoiceRecord::route('/{record}/edit'),
        ];
    }
}
