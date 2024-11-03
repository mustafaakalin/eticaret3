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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack'; // Icon for products
    protected static ?string $navigationGroup = 'Ürün Yönetimi'; // Group name
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Ürün Adı'),
                    
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->label('Slug'),
                    
                Repeater::make('images')
                    ->relationship('images') // Product modelindeki images ilişkisini belirtir
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Resim')
                            ->directory('products/images')
                            ->image(),
                    ])
                    ->columns(1) // Bir sütunda göstermek için
                    ->label('Ürün Resimleri'),
                
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->label('Açıklama'),
                    
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('₺')
                    ->label('Fiyat'),
                    
                Forms\Components\TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->label('Stok'),
                    
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
                    
                Forms\Components\Toggle::make('featured')
                    ->label('Öne Çıkar')
                    ->default(false), // Default to false
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Ürün Adı'),
                    
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->label('Slug'),
                    
                Tables\Columns\ImageColumn::make('images.image_path')
                    ->label('Görseller')
                    ->disk('public')
                    ->size(50), 
                    
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable()
                    ->label('Fiyat'),
                    
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable()
                    ->label('Stok'),
                    
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->label('Kategori'), 

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
                // Additional filters can be added here
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->preload()
                    ->multiple(), // Allows filtering by multiple categories
                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Öne Çıkan'),
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
}
