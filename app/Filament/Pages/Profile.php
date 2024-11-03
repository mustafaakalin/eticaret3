<?php

namespace App\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Actions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $view = 'filament.pages.profile';

    public array $data = []; // Değişkeni dizi olarak tanımlıyoruz

    public function mount(): void
    {
        // Kullanıcı verilerini form için doldur
        $this->form->fill(Auth::user()->only([
            'name',
            'surname',
            'email',
            'identity_number',
            'address',
            'city',
            'country',
            'zip_code',
            'avatar',
        ]));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->autofocus()
                    ->required(),

                Forms\Components\TextInput::make('surname')
                    ->label('Surname')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),

                Forms\Components\TextInput::make('identity_number')
                    ->label('Identity Number'),

                Forms\Components\TextInput::make('address')
                    ->label('Address'),

                Forms\Components\TextInput::make('city')
                    ->label('City'),

                Forms\Components\TextInput::make('country')
                    ->label('Country'),

                Forms\Components\TextInput::make('zip_code')
                    ->label('Zip Code'),

                Forms\Components\FileUpload::make('avatar')
                    ->label('Avatar')
                    ->directory('avatars')
                    ->image(),
            ])
            ->statePath('data') // Formun statePath'ini ayarlıyoruz
            ->model(Auth::user()); // Model olarak giriş yapan kullanıcıyı atıyoruz
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('Update')
                ->color('primary')
                ->submit('update'), // Submit metodu için update fonksiyonunu çağırıyoruz
        ];
    }

    public function update()
    {
        // Kullanıcı verilerini güncelle
        Auth::user()->update($this->form->getState());

        // Başarılı bildirim gönder
        Notification::make()
            ->title('Profile updated!')
            ->success()
            ->send();
    }
}
