<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Placeholder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag'; // Icon for categories
    protected static ?string $navigationGroup = 'Ürün Yönetimi'; // Group name
    
    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Card::make([
                Tabs::make('Kategori Bilgisi') // Ana bilgileri 'Kategori Bilgisi' tabı altında
                    ->tabs([
                        Tabs\Tab::make('Genel')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Kategori Adı')
                                    ->placeholder('Kategorinin adını girin'),

                                Textarea::make('description')
                                    ->label('Açıklama')
                                    ->columnSpanFull()
                                    ->placeholder('Kategori hakkında kısa bir açıklama girin'),
                            ]),
                        
                        Tabs\Tab::make('Ayarlar')
                            ->schema([
                                Section::make('Kategori Ayarları')
                                    ->schema([
                                        Select::make('parent_id')
                                            ->relationship('parent', 'name')
                                            ->label('Ebeveyn Kategori')
                                            ->nullable()
                                            ->searchable()
                                            ->placeholder('Üst kategori seçin'),
                                    ]),
                            ]),
                    ]),
                
                Section::make('Diğer Bilgiler')
                    ->schema([
                        Placeholder::make('Ek bilgi alanı')
                            ->content('Bu bölümde ek bilgiler görüntülenecektir.'),
                    ])
                    ->collapsible() // Bu bölümü gizlenebilir yapar
                    ->collapsed(), // Varsayılan olarak gizlenmiş açılır
            ]),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Kategori Adı')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Ebeveyn Kategori')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers if necessary, e.g., products in this category
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
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
