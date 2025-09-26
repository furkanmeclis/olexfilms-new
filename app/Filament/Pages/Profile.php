<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Hash;
use Illuminate\Validation\Rules\Password;

class Profile extends Page
{
    protected string $view = 'filament.pages.profile';
    protected static ?string $title = 'Profil';
    protected static ?string $navigationLabel = 'Profil';
    protected static ?string $slug = 'profile';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = [
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'email_verified_at' => auth()->user()->email_verified_at,
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Profil Bilgileri')
                    ->tabs([
                        Tabs\Tab::make('Genel Bilgiler')
                            ->schema([
                                Section::make('Kişisel Bilgiler')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Ad Soyad')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('email')
                                            ->label('E-posta')
                                            ->email()
                                            ->required()
                                            ->unique(User::class, 'email', ignoreRecord: auth()->user()->id)
                                            ->helperText('E-posta adresinizi değiştirdiğinizde doğrulama gerekebilir'),

                                        Toggle::make('email_verified_at')
                                            ->label('E-posta Doğrulanmış')
                                            ->disabled()
                                            ->helperText('E-posta doğrulama durumu'),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make('Güvenlik')
                            ->schema([
                                Section::make('Şifre Değiştir')
                                    ->schema([
                                        TextInput::make('current_password')
                                            ->label('Mevcut Şifre')
                                            ->password()
                                            ->required()
                                            ->currentPassword(),

                                        TextInput::make('password')
                                            ->label('Yeni Şifre')
                                            ->password()
                                            ->required()
                                            ->rule(Password::default())
                                            ->dehydrated(fn ($state) => filled($state))
                                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),

                                        TextInput::make('password_confirmation')
                                            ->label('Yeni Şifre Tekrar')
                                            ->password()
                                            ->required()
                                            ->same('password')
                                            ->dehydrated(false),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        $actions = [
            Action::make('save')
                ->label('Kaydet')
                ->action(fn() => $this->save())
                ->color('primary'),
        ];

        // E-posta doğrulanmamışsa doğrulama butonu ekle
        if (!auth()->user()->hasVerifiedEmail()) {
            $actions[] = Action::make('verify_email')
                ->label('E-posta Doğrula')
                ->action('verifyEmail')
                ->color('warning')
                ->icon('heroicon-o-envelope');
        }

        return $actions;
    }

    public function save(): void
    {
        $data = $this->data;

        // E-posta değiştiyse doğrulama sıfırla
        if ($data['email'] !== auth()->user()->email) {
            $data['email_verified_at'] = null;
        }

        auth()->user()->update($data);

        Notification::make()
            ->title('Profil Güncellendi')
            ->success()
            ->body('Profil bilgileriniz başarıyla güncellendi.')
            ->send();
    }

    public function verifyEmail(): void
    {
        if (auth()->user()->hasVerifiedEmail()) {
            Notification::make()
                ->title('E-posta Zaten Doğrulanmış')
                ->warning()
                ->body('E-posta adresiniz zaten doğrulanmış.')
                ->send();
            return;
        }

        auth()->user()->sendEmailVerificationNotification();

        Notification::make()
            ->title('Doğrulama E-postası Gönderildi')
            ->success()
            ->body('E-posta adresinize doğrulama bağlantısı gönderildi. Lütfen e-postanızı kontrol edin.')
            ->send();
    }
}
