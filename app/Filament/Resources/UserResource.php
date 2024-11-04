<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user'; // Icon for users
    protected static ?string $navigationGroup = 'Kullanıcı Yönetimi'; // Group name

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                // Kullanıcı Bilgileri Kartı
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Ad')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('surname')
                            ->label('Soyad')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('identity_number')
                            ->label('Kimlik Numarası')
                            ->maxLength(20),
                    ])
                    ->columnSpanFull()
                    ->label('Kullanıcı Bilgileri'),

                // Şifre ve E-posta Onayı
                Forms\Components\Section::make('Güvenlik')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255)
                            ->label('Şifre'),

                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('E-posta Onay Tarihi'),
                    ])
                    ->collapsible(),

                // Adres Bilgileri Kartı
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->label('Adres')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('city')
                            ->label('Şehir')
                            ->maxLength(100),

                        Forms\Components\TextInput::make('country')
                            ->label('Ülke')
                            ->maxLength(100),

                        Forms\Components\TextInput::make('zip_code')
                            ->label('Posta Kodu')
                            ->maxLength(10),
                    ])
                    ->columnSpanFull()
                    ->label('Adres Bilgileri'),

                // Rol ve İzinler Sekmesi
                Forms\Components\Tabs::make('Erişim Yönetimi')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Roller')
                            ->schema([
                                Forms\Components\Select::make('roles')
                                    ->multiple()
                                    ->relationship('roles', 'name')
                                    ->label('Roller')
                                    ->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('İzinler')
                            ->schema([
                                Forms\Components\Select::make('permissions')
                                    ->multiple()
                                    ->relationship('permissions', 'name')
                                    ->label('İzinler')
                            ]),
                    ]),

                // Profil Resmi
                Forms\Components\FileUpload::make('avatar')
                    ->label('Profil Resmi')
                    ->directory('avatars')
                    ->image()
                    ->columnSpanFull(),
            ]);
    }


    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->label('Ad')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('surname')
                ->label('Soyad')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('email')
                ->label('E-posta')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('identity_number')
                ->label('Kimlik Numarası')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('address')
                ->label('Adres')
                ->limit(50) // Adresin uzunluğunu sınırlamak için
                ->sortable(),

            Tables\Columns\TextColumn::make('city')
                ->label('Şehir')
                ->sortable(),

            Tables\Columns\TextColumn::make('country')
                ->label('Ülke')
                ->sortable(),

            Tables\Columns\TextColumn::make('zip_code')
                ->label('Posta Kodu')
                ->sortable(),

            Tables\Columns\ImageColumn::make('avatar')
                ->label('Avatar')
                ->disk('public')
                ->size(50)
                ->tooltip('Kullanıcının avatarı'),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Oluşturulma Tarihi')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->formatStateUsing(fn ($state) => $state->format('d.m.Y H:i')),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Son Güncelleme Tarihi')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->formatStateUsing(fn ($state) => $state->format('d.m.Y H:i')),

            Tables\Columns\TextColumn::make('roles.name')
                ->label('Roller')
                ->sortable()
                ->toggleable(),

            Tables\Columns\TextColumn::make('permissions.name')
                ->label('İzinler')
                ->sortable()
                ->toggleable(),
        ])
        ->filters([
            Tables\Filters\TrashedFilter::make(),
        ])
        ->actions([
            Tables\Actions\ViewAction::make()
                ->label('Görüntüle'),
            Tables\Actions\EditAction::make()
                ->label('Düzenle'),
            Tables\Actions\DeleteAction::make()
                ->label('Sil'),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Sil'),
                Tables\Actions\RestoreBulkAction::make()
                    ->label('Geri Yükle'),
            ]),
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make()
                ->label('Yeni Kullanıcı Ekle')
                ->icon('heroicon-o-plus'), // İkon ekleme
        ]);
}


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
