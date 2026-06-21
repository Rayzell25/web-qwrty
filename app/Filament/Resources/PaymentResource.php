<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 0;

    protected static ?string $modelLabel = 'Pembayaran';

    public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist->schema([
            \Filament\Infolists\Components\Section::make('Detail Pembayaran')
                ->columns(2)
                ->schema([
                    \Filament\Infolists\Components\TextEntry::make('order_id')->label('Order ID')->copyable(),
                    \Filament\Infolists\Components\TextEntry::make('transaction_id')->label('Transaction ID')->placeholder('-'),
                    \Filament\Infolists\Components\TextEntry::make('amount')->label('Nominal')->money('IDR'),
                    \Filament\Infolists\Components\TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->formatStateUsing(fn (string $state): string => Payment::STATUSES[$state] ?? $state)
                        ->color(fn (string $state): string => match ($state) {
                            'settlement' => 'success',
                            'pending' => 'warning',
                            'expire' => 'gray',
                            'cancel' => 'danger',
                            default => 'secondary',
                        }),
                    \Filament\Infolists\Components\TextEntry::make('payment_type')->label('Tipe'),
                    \Filament\Infolists\Components\TextEntry::make('issuer')->label('Issuer')->placeholder('-'),
                    \Filament\Infolists\Components\TextEntry::make('reference')->label('Untuk')->placeholder('-'),
                    \Filament\Infolists\Components\TextEntry::make('user.name')->label('User')->placeholder('Guest'),
                    \Filament\Infolists\Components\TextEntry::make('paid_at')->label('Dibayar')->dateTime()->placeholder('-'),
                    \Filament\Infolists\Components\TextEntry::make('expired_at')->label('Kedaluwarsa')->dateTime()->placeholder('-'),
                    \Filament\Infolists\Components\TextEntry::make('created_at')->label('Dibuat')->dateTime(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')->label('Order ID')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('amount')->label('Nominal')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Payment::STATUSES[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'settlement' => 'success',
                        'pending' => 'warning',
                        'expire' => 'gray',
                        'cancel' => 'danger',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('issuer')->label('Issuer')->toggleable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->toggleable()->placeholder('Guest'),
                Tables\Columns\TextColumn::make('reference')->label('Untuk')->toggleable(),
                Tables\Columns\TextColumn::make('paid_at')->label('Dibayar')->dateTime()->placeholder('-')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->label('Status')->options(Payment::STATUSES),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'view' => Pages\ViewPayment::route('/{record}'),
        ];
    }
}
