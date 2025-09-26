<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        $guards = ['web'];
        return $schema
            ->components([
                Section::make('Rol Bilgileri')
                    ->schema([
                        TextInput::make('name')
                            ->label('Rol Adı')
                            ->required()
                            ->unique('roles', 'name', ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('guard_name')
                            ->label('Guard Adı')
                            ->default($guards[0])
                            ->required()
                            ->native(false)
                            ->options($guards),
                            
                    ])
                    ->columns(2),
                
                Section::make('İzinler')
                    ->schema([
                        Select::make('permissions')
                            ->label('İzinler')
                            ->multiple()
                            ->relationship('permissions', 'name')
                            ->searchable()
                            ->preload()
                            ->options(Permission::all()->pluck('name', 'id'))
                            ->required(),
                    ])
                    ->collapsible(),
            ]);
    }
}
