<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialLinkResource\Pages;
use App\Models\SocialLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class SocialLinkResource extends Resource
{
    protected static ?string $model = SocialLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Tautan Sosial';

    protected static ?string $pluralModelLabel = 'Tautan Sosial';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('platform')
                ->label('Platform')
                ->options(collect(SocialLink::PLATFORMS)->map(fn ($p) => $p['label'])->toArray())
                ->required()
                ->helperText('Pilih platform (mis. Telegram). Logonya otomatis muncul di footer.'),

            Forms\Components\TextInput::make('url')
                ->label('URL / Username / Nomor')
                ->required()
                ->maxLength(255)
                ->helperText('Contoh: "t.me/RayzelllStores", "@RayzellStores", atau URL lengkap. WhatsApp boleh nomor (0812...).'),

            Forms\Components\TextInput::make('label')
                ->label('Keterangan (opsional)')
                ->maxLength(255)
                ->helperText('Opsional, hanya untuk catatan internal.'),

            Forms\Components\TextInput::make('sort_order')
                ->label('Urutan')
                ->numeric()
                ->default(0),

            Forms\Components\Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('platform')
                    ->label('Platform')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => SocialLink::PLATFORMS[$state]['label'] ?? ucfirst($state)),
                Tables\Columns\TextColumn::make('url')->label('URL/Username')->limit(40)->copyable(),
                Tables\Columns\TextColumn::make('sort_order')->label('Urutan')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('preview')
                        ->label('Preview')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->modalHeading('Preview Tautan Sosial')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup')
                        ->modalContent(fn (SocialLink $record) => view('admin.previews.social-link', ['link' => $record])),
                    Tables\Actions\EditAction::make()->label('Ubah'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->requiresConfirmation()
                        ->successNotificationTitle('Tautan dihapus'),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada tautan sosial')
            ->emptyStateDescription('Tambah tautan (mis. Telegram) agar muncul di footer website.');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSocialLinks::route('/'),
            'create' => Pages\CreateSocialLink::route('/create'),
            'edit' => Pages\EditSocialLink::route('/{record}/edit'),
        ];
    }
}
