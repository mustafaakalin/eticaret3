<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CartResource\Pages;
use App\Filament\Resources\CartResource\RelationManagers;
use App\Models\Cart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;

class CartResource extends Resource
{
    protected static ?string $model = Cart::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Sepet Yönetimi'; // Grouping under a relevant category
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name') // Assuming a User model with a name field
                    ->required()
                    ->label('Kullanıcı')
                    ->options(function () {
                        $user = auth()->user(); // Authenticated user'ı al
    
                        // Eğer kullanıcı admin değilse, yalnızca kendisi
                        return $user->hasRole('admin')
                            ? \App\Models\User::all()->pluck('name', 'id') // Admin tüm kullanıcıları görebilir
                            : \App\Models\User::where('id', $user->id)->pluck('name', 'id'); // Admin olmayan kullanıcı yalnızca kendisini görür
                    }),

                // Add a relation to display cart items
                Forms\Components\Repeater::make('cartItems')
                    ->relationship('cartItems') // Cart model'daki cartItems ilişkisini belirtir
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name') // Assuming a Product model with a name field
                            ->required()
                            ->label('Ürün'),

                        Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->label('Miktar'),
                    ])
                    ->columns(1) // Display items in a single column
                    ->label('Sepet Ürünleri'),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('user.name')
                ->sortable()
                ->label('Kullanıcı'),

            Tables\Columns\TextColumn::make('cartItems.quantity') // Renamed for uniqueness
                ->label('Ürün Sayısı')
                ->formatStateUsing(fn ($record) => $record->cartItems()->count()),

            Tables\Columns\TextColumn::make('cartItems') // Renamed for uniqueness
                ->label('Ürünler')
                ->formatStateUsing(fn ($record) => $record->cartItems->map(fn($item) => $item->product->name . ' (x' . $item->quantity . ')')->join(', '))
                ->limit(30)
                ->tooltip(fn ($record) => $record->cartItems->map(fn($item) => $item->product->name . ' (x' . $item->quantity . ')' ." ". $item->product->price * $item->quantity. " ₺")->join(', ')),

            Tables\Columns\TextColumn::make('cartItems.product.price') // Renamed for uniqueness
                ->label('Sepet Fiyat')
                ->formatStateUsing(fn ($record) => 
                    $record->cartItems->map(fn($item) => $item->product->price * $item->quantity)->join(', ')
                )
                ->tooltip(fn ($record) => $record->cartItems->map(fn($item) => $item->product->name . ' (x' . $item->quantity . ')')->join(', ')),
                
            Tables\Columns\TextColumn::make('cartItems.product.price')
                ->label('Toplam Fiyat')
                ->formatStateUsing(fn ($record) => 
                    $record->cartItems->sum(fn($item) => $item->quantity * ($item->product->price ?? 0)) . ' ₺'
                ),

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
            // Include any relation managers if necessary
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarts::route('/'),
            'create' => Pages\CreateCart::route('/create'),
            'view' => Pages\ViewCart::route('/{record}'),
            'edit' => Pages\EditCart::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ])
        ->with(['cartItems.product']);

    // Eğer kullanıcı admin değilse, yalnızca kendi sepetlerini görüntüleyebilir
    if (!auth()->user()->hasRole('admin')) {
        $query->where('user_id', auth()->id());
    }

    return $query;
}



}
