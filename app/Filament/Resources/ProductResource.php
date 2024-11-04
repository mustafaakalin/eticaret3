<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack'; // Icon for products
    protected static ?string $navigationGroup = 'Ürün Yönetimi'; // Group name
    
    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Card::make([
                Tabs::make('Product Information')
                    ->tabs([
                        Tabs\Tab::make('Genel Bilgiler')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->label('Ürün Adı'),
                                            
                                        TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->label('Slug'),
                                    ]),
                                
                                Textarea::make('description')
                                    ->columnSpanFull()
                                    ->label('Açıklama'),
                            ]),
                        
                        Tabs\Tab::make('Fiyat & Stok')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('price')
                                            ->required()
                                            ->numeric()
                                            ->prefix('₺')
                                            ->label('Fiyat'),
                                            
                                        TextInput::make('stock')
                                            ->required()
                                            ->numeric()
                                            ->default(0)
                                            ->label('Stok'),
                                    ]),
                                    
                                Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->required(),
                                
                                Select::make('tags')
                                    ->label('Etiketler')
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->required(),
                            ]),
                        
                        Tabs\Tab::make('Medya')
                            ->schema([
                                Repeater::make('images')
                                    ->relationship('images')
                                    ->schema([
                                        FileUpload::make('image_path')
                                            ->label('Resim')
                                            ->directory('products/images')
                                            ->image(),
                                    ])
                                    ->columns(1)
                                    ->label('Ürün Resimleri'),
                            ]),
                        
                        Tabs\Tab::make('Ayarlar')
                            ->schema([
                                Section::make('Özellikler')
                                    ->schema([
                                        Toggle::make('featured')
                                            ->label('Öne Çıkar')
                                            ->default(false),
                                    ])
                                    ->columns(1),
                            ]),
                    ]),
            ]),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\ImageColumn::make('images.image_path')
                ->label('Görsel')
                ->disk('public')
                ->size(60)
                ->circular(),

            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->label('Ürün Adı')
                ->weight('bold')
                ->tooltip(fn($record) => $record->description),

            Tables\Columns\TextColumn::make('category.name')
                ->sortable()
                ->label('Kategori')
                ->badge(),

            Tables\Columns\BadgeColumn::make('stock')
                ->label('Stok')
                ->sortable()
                ->formatStateUsing(function ($state) {
                    if ($state > 50) {
                        return 'Yüksek Stok';
                    } elseif ($state > 10) {
                        return 'Orta Stok';
                    } else {
                        return 'Düşük Stok';
                    }
                })
                ->color(fn($state) => match ($state) {
                    'Yüksek Stok' => 'success',
                    'Orta Stok' => 'warning',
                    'Düşük Stok' => 'danger',
                    default => 'secondary',
                }),

            Tables\Columns\TextColumn::make('price')
                ->money('TRY', true)
                ->sortable()
                ->label('Fiyat')
                ->description('Vergiler dahil'),

            Tables\Columns\IconColumn::make('featured')
                ->boolean()
                ->label('Öne Çıkan')
                ->trueIcon('heroicon-o-star')
                ->falseIcon('heroicon-s-x-mark'),

            Tables\Columns\TextColumn::make('deleted_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->label('Silinme Tarihi'),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->label('Oluşturulma Tarihi'),

            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->label('Güncellenme Tarihi'),
        ])
        ->filters([
            Tables\Filters\TrashedFilter::make(),
            Tables\Filters\SelectFilter::make('category_id')
                ->label('Kategori')
                ->relationship('category', 'name')
                ->preload()
                ->multiple(),
            Tables\Filters\TernaryFilter::make('featured')
                ->label('Öne Çıkan'),
            Tables\Filters\SelectFilter::make('stock_status')
                ->label('Stok Durumu')
                ->options([
                    'high' => '50 ve üzeri',
                    'medium' => '10 - 50',
                    'low' => '10 ve altı',
                ])
                ->query(fn($query, $state) => $query->when($state === 'high', fn($query) => $query->where('stock', '>=', 50))
                ->when($state === 'medium', fn($query) => $query->whereBetween('stock', [10, 49]))
                ->when($state === 'low', fn($query) => $query->where('stock', '<=', 10))),
        ])
        ->actions([
            Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]),
        ])
        ->defaultSort('created_at', 'desc');
}



    public static function getRelations(): array
    {
        return [
            // İlişkisel yöneticiler buraya eklenebilir
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
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
