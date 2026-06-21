<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaderboardEntryResource\Pages;
use App\Models\LeaderboardEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeaderboardEntryResource extends Resource
{
    protected static ?string $model = LeaderboardEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Leaderboard';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('city')
                ->label('Kota')
                ->maxLength(255),
            Forms\Components\TextInput::make('score')
                ->label('Skor')
                ->numeric()
                ->default(0)
                ->required(),
            Forms\Components\TextInput::make('rank')
                ->label('Peringkat')
                ->numeric()
                ->helperText('Kosongkan untuk peringkat otomatis berdasarkan skor.'),
            Forms\Components\FileUpload::make('image')
                ->label('Foto')
                ->image()
                ->disk('public')
                ->directory('leaderboard'),
            Forms\Components\Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('Foto')->circular()->disk('public'),
                Tables\Columns\TextColumn::make('rank')->label('Peringkat')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('city')->label('Kota')->searchable(),
                Tables\Columns\TextColumn::make('score')->label('Skor')->numeric()->sortable(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->defaultSort('score', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Aktif'),
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
            'index' => Pages\ListLeaderboardEntries::route('/'),
            'create' => Pages\CreateLeaderboardEntry::route('/create'),
            'edit' => Pages\EditLeaderboardEntry::route('/{record}/edit'),
        ];
    }
}
