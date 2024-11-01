<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('total_price')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'pending' => 'Bekleniyor',
                        'shipped' => 'Kargoya Verildi',
                        'completed' => 'Tamamlandı',
                    ])
                    ->default('pending'),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();
        return $table
        ->query(Order::query()->when(!Auth::user()->hasRole('admin'), function ($query) {
            $query->where('user_id', Auth::id()); // Normal kullanıcı sadece kendi siparişlerini görür
        }))
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
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
    $user = Auth::user();

    // Eğer admin değilse sadece kendi order kayıtlarını görsün
    if ($user->hasRole('admin')) {
        return true; // Admin tüm kayıtları görebilir
    }

    // Kullanıcı sadece kendi order'larını görebilir
    return $user->id === $record->user_id;
}



public static function query(): Builder
{
    $user = Auth::user();
    
    // Eğer admin değilse, sadece kendi verilerini görebilsin
    if ($user->hasRole('admin')) {
        return parent::query(); // Admin her şeyi görür
    } else {
        return parent::query()->where('user_id', $user->id); // Sadece kendi siparişlerini görür
    }
}



}
