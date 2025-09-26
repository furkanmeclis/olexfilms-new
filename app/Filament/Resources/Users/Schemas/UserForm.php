<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kullanıcı Bilgileri')
                    ->schema([
                        TextInput::make('name')
                            ->label('Ad Soyad')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('E-posta Adresi')
                            ->email()
                            ->required()
                            ->unique(User::class, 'email', ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Şifre')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                    ])
                    ->columns(2),
                
                Section::make('Rol ve Yetkilendirme')
                    ->schema([
                        Select::make('roles')
                            ->label('Roller')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable()
                            ->required(),
                        Select::make('dealer_id')
                            ->label('Bağlı Bayi')
                            ->relationship('dealer', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (): bool => auth()->user()->hasRole('admin'))
                            ->required(fn (): bool => auth()->user()->hasRole('admin')),
                    ])
                    ->columns(2),
                
                Section::make('Sistem Bilgileri')
                    ->schema([
                        DateTimePicker::make('email_verified_at')
                            ->label('E-posta Doğrulama Tarihi')
                            ->displayFormat('d/m/Y H:i'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
