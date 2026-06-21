<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarrantyClaimResource\Pages;
use App\Models\WarrantyClaim;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WarrantyClaimResource extends Resource
{
    protected static ?string $model = WarrantyClaim::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Klaim Garansi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Pelanggan')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('full_name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('whatsapp')
                        ->label('WhatsApp')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('invoice_number')
                        ->label('Nomor Invoice')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('product_name')
                        ->label('Nama Produk')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('complaint')
                        ->label('Keluhan')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('attachment')
                        ->label('Lampiran')
                        ->disk('public')
                        ->directory('warranty-claims')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Tindak Lanjut Admin')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options(WarrantyClaim::STATUSES)
                        ->default('pending')
                        ->required(),
                    Forms\Components\Textarea::make('admin_note')
                        ->label('Catatan Admin')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('invoice_number')->label('Invoice')->searchable(),
                Tables\Columns\TextColumn::make('product_name')->label('Produk')->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => WarrantyClaim::STATUSES[$state] ?? ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'diproses' => 'info',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        'selesai' => 'gray',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->dateTime()->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(WarrantyClaim::STATUSES),
            ])
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
            'index' => Pages\ListWarrantyClaims::route('/'),
            'create' => Pages\CreateWarrantyClaim::route('/create'),
            'edit' => Pages\EditWarrantyClaim::route('/{record}/edit'),
        ];
    }
}
