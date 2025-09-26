<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Exports\UserExporter;
use App\Filament\Imports\UserImporter;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ImportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Roller')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'dealer' => 'warning',
                        'worker' => 'info',
                        'central_worker' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('dealer.name')
                    ->label('Bağlı Bayi')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Merkez'),
                TextColumn::make('email_verified_at')
                    ->label('E-posta Doğrulandı')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Doğrulanmamış'),
                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Güncellenme')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->label('Rol')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('dealer_id')
                    ->label('Bayi')
                    ->relationship('dealer', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('email_verified_at')
                    ->label('E-posta Durumu')
                    ->options([
                        'verified' => 'Doğrulanmış',
                        'unverified' => 'Doğrulanmamış',
                    ])
                    ->query(function ($query, array $data) {
                        return match ($data['value'] ?? null) {
                            'verified' => $query->whereNotNull('email_verified_at'),
                            'unverified' => $query->whereNull('email_verified_at'),
                            default => $query,
                        };
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                ImportAction::make()
                    ->importer(UserImporter::class)
                    ->label('Import')
                    ->color('success'),
                ExportBulkAction::make()
                    ->exporter(UserExporter::class)
                    ->label('Export')
                    ->color('info'),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
