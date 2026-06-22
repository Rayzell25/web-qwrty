<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Pengaturan Situs';

    public static function form(Form $form): Form
    {
        $isMedia = fn (Forms\Get $get): bool => in_array($get('key'), ['logo', 'favicon'], true);

        return $form->schema([
            Forms\Components\TextInput::make('key')
                ->label('Kunci')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->live(onBlur: true)
                ->helperText('Contoh: site_name, company_email, logo, favicon'),
            Forms\Components\TextInput::make('group')
                ->label('Grup')
                ->maxLength(255)
                ->helperText('Opsional, untuk pengelompokan (general, contact, social, dll).'),

            // Uploader khusus saat key = logo / favicon (nama field "upload", bukan "value")
            Forms\Components\FileUpload::make('upload')
                ->label('File (Logo / Favicon)')
                ->disk('public')
                ->directory('settings')
                ->visibility('public')
                ->acceptedFileTypes([
                    'image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml',
                    'image/x-icon', 'image/vnd.microsoft.icon', 'video/mp4', 'video/webm',
                ])
                ->maxSize(20480)
                ->downloadable()
                ->openable()
                ->visible($isMedia)
                ->dehydrated($isMedia)
                ->helperText('Upload logo/favicon. Logo bisa gambar (PNG/JPG/SVG), GIF bergerak, atau video MP4/WEBM. Kosongkan "site_name" jika ingin hanya logo yang tampil.'),

            // Textarea untuk setting teks biasa
            Forms\Components\Textarea::make('value')
                ->label('Nilai')
                ->rows(3)
                ->columnSpanFull()
                ->visible(fn (Forms\Get $get) => ! $isMedia($get))
                ->dehydrated(fn (Forms\Get $get) => ! $isMedia($get)),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->label('Kunci')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('value')->label('Nilai')->limit(60)->wrap(),
                Tables\Columns\TextColumn::make('group')->label('Grup')->badge()->sortable(),
            ])
            ->defaultSort('group')
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->label('Grup')
                    ->options([
                        'general' => 'Umum',
                        'hero'    => 'Hero / Banner',
                        'contact' => 'Kontak',
                        'social'  => 'Media Sosial',
                        'seo'     => 'SEO',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('preview')
                        ->label('Lihat di Web')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->url(function (SiteSetting $record) {
                            if (in_array($record->key, ['logo', 'favicon'], true) && filled($record->value)) {
                                $v = $record->value;
                                return str_starts_with($v, 'http://') || str_starts_with($v, 'https://')
                                    ? $v
                                    : \Illuminate\Support\Facades\Storage::disk('public')->url($v);
                            }
                            return url('/');
                        })
                        ->openUrlInNewTab(),
                    Tables\Actions\EditAction::make()->label('Ubah'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus pengaturan ini?')
                        ->modalDescription('Pengaturan akan dihapus permanen.')
                        ->successNotificationTitle('Pengaturan dihapus'),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->button(),
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
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
