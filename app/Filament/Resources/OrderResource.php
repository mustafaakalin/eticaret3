<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card'; // Change to a receipt icon
    protected static ?string $navigationGroup = 'Sipariş Yönetimi'; // Grouping under a relevant category

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->options(function () {
                                $user = auth()->user();

                                // Admin, tüm kullanıcıları görebilir; aksi durumda kullanıcı yalnızca kendisini görebilir
                                return $user->hasRole('admin')
                                    ? User::all()->pluck('name', 'id')
                                    : User::where('id', $user->id)->pluck('name', 'id');
                            })
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('total_price')
                            ->label('Toplam Fiyat')
                            ->required()
                            ->numeric()
                            ->prefix('₺'),
                    ])
                    ->columnSpanFull()
                    ->label('Order Information'), // Kullanıcı ve fiyat bilgileri için bir kart

                Forms\Components\Section::make('Order Status') // Durum seçimi için bir bölüm
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->required()
                            ->options([
                                'pending' => 'Bekleniyor',
                                'shipped' => 'Kargoya Verildi',
                                'completed' => 'Tamamlandı',
                            ])
                            ->default('pending')
                            ->columnSpan(2),
                    ])
                    ->description('Siparişin güncel durumu')
                    ->collapsible(), // Bu bölümü katlanabilir yapıyor
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->when(!Auth::user()->hasRole('admin'), function ($query) {
                $query->where('user_id', Auth::id()); // Normal kullanıcı sadece kendi siparişlerini görür
            }))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->tooltip(function (Model $record) {
                        $user = $record->user;
                        return "Role: " . $user->getRoleNames()->first() . "\nEmail: " . $user->email;
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray' => 'pending',
                        'warning' => 'shipped',
                        'success' => 'completed',
                    ])
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('orderItems')
                    ->label('Ürünler')
                    ->formatStateUsing(function ($state, Model $record) {
                        // Siparişe ait ürünleri al ve formatla
                        return $record->orderItems()
                            ->with('product') // Ürün bilgilerini yükle
                            ->limit(30) // Sadece 30 ürünü getir
                            ->get()
                            ->map(function ($item) {
                                return "Ürün İsmi: {$item->product->name}, Miktar: {$item->quantity}, Fiyat: ₺{$item->price}";
                            })->join("<br />") ?: 'Ürün bulunmuyor.';
                    })
                    // ->html() // HTML formatını etkinleştir
                    ->tooltip(function (Model $record) {
                        // Siparişe ait ürünleri al
                        $orderItems = $record->orderItems()->get();
                        $itemsInfo = $orderItems->map(function ($item) {
                            return "Ürün ismi: {$item->product->name}, Miktar: {$item->quantity}, Fiyat: ₺{$item->price}";
                        })->join("\n");
                        return $itemsInfo ?: 'Ürün bulunmuyor.';
                    })
                    ->limit(20)
                    ->sortable()
                    ->searchable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canView(Model $record): bool
    {
        return Auth::user()->hasRole('admin') || Auth::id() === $record->user_id;
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
