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
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;

class CartResource extends Resource
{
    protected static ?string $model = Cart::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Sepet Yönetimi'; // Grouping under a relevant category
    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Card::make()
                ->schema([
                    Grid::make()
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
                                })
                                ->helperText('Lütfen bir kullanıcı seçiniz.')
                                ->placeholder('Kullanıcı seçiniz')
                                ->extraAttributes(['class' => 'w-full']),
                        ])
                        ->columns(1), // Tek sütunda göster

                    Tabs::make('Sepet')
                        ->tabs([
                            Tabs\Tab::make('Ürünler')
                                ->schema([
                                    Fieldset::make('Sepet Ürünleri')
                                        ->schema([
                                            Repeater::make('cartItems')
                                                ->relationship('cartItems') // Cart model'daki cartItems ilişkisini belirtir
                                                ->schema([
                                                    Select::make('product_id')
                                                        ->relationship('product', 'name') // Assuming a Product model with a name field
                                                        ->required()
                                                        ->label('Ürün')
                                                        ->helperText('Lütfen bir ürün seçiniz.')
                                                        ->placeholder('Ürün seçiniz')
                                                        ->extraAttributes(['class' => 'w-full']),

                                                    TextInput::make('quantity')
                                                        ->required()
                                                        ->numeric()
                                                        ->minValue(1)
                                                        ->default(1)
                                                        ->label('Miktar')
                                                        ->helperText('Lütfen miktarı giriniz.')
                                                        ->placeholder('Miktar giriniz')
                                                        ->extraAttributes(['class' => 'w-full']),
                                                ])
                                                ->columns(1) // Display items in a single column
                                                ->label('Sepet Ürünleri')
                                                ->helperText('Sepetinizdeki ürünleri buradan düzenleyebilirsiniz.')
                                                ->extraAttributes(['class' => 'w-full']),
                                        ]),
                                ]),
                        ])
                        ->columnSpan(1), // Tek sütunda göster
                ])
                ->columns(1), // Tek sütunda göster
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('user.name')
                ->sortable()
                ->label('Kullanıcı')
                ->tooltip(fn($record) => $record->user->email), // Kullanıcı emailini göster

            Tables\Columns\TextColumn::make('cartItems.quantity') 
                ->label('Ürün Sayısı')
                ->formatStateUsing(fn ($record) => $record->cartItems()->count()),

            Tables\Columns\TextColumn::make('cartItems') 
                ->label('Ürünler')
                ->formatStateUsing(fn ($record) => 
                    $record->cartItems->map(fn($item) => $item->product->name . ' (x' . $item->quantity . ')')->join(', ')
                )
                ->limit(30)
                ->tooltip(fn ($record) => 
                    $record->cartItems->map(fn($item) => 
                        $item->product->name . ' (x' . $item->quantity . ') - ' . $item->product->price * $item->quantity . ' ₺'
                    )->join(', ')
                ),

            
            Tables\Columns\TextColumn::make('cartItems.product.price')
                ->label('Toplam Fiyat')
                ->formatStateUsing(fn ($record) => 
                    $record->cartItems->sum(fn($item) => $item->quantity * ($item->product->price ?? 0)) . ' ₺'
                ),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->label('Oluşturulma Tarihi')
                ->toggleable(isToggledHiddenByDefault: true)
                ->formatStateUsing(fn ($state) => $state->format('d.m.Y H:i')), // Formatlayarak göstermek

            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->label('Son Güncelleme')
                ->toggleable(isToggledHiddenByDefault: true)
                ->formatStateUsing(fn ($state) => $state->format('d.m.Y H:i')), // Formatlayarak göstermek

            Tables\Columns\TextColumn::make('deleted_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->formatStateUsing(fn ($state) => $state ? $state->format('d.m.Y H:i') : 'Aktif'),
        ])
        ->filters([
            Tables\Filters\TrashedFilter::make(),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(), // Silme butonu eklemek
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
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


public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

}
