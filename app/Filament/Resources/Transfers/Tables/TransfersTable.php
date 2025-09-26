<?php

namespace App\Filament\Resources\Transfers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TransfersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transfer_number')
                    ->label('Transfer No')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->tooltip('Kopyalamak için tıklayın'),
                
                TextColumn::make('fromUser.name')
                    ->label('Gönderen')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                
                TextColumn::make('toUser.name')
                    ->label('Alıcı')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                
                TextColumn::make('status_display')
                    ->label('Durum')
                    ->badge()
                    ->color(fn ($record) => $record->status_color),
                
                TextColumn::make('cargo_code')
                    ->label('Kargo Kodu')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('cargo_company')
                    ->label('Kargo Şirketi')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('items_count')
                    ->label('Kalem Sayısı')
                    ->counts('items')
                    ->sortable(),
                
                TextColumn::make('createdBy.name')
                    ->label('Oluşturan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('sent_at')
                    ->label('Gönderilme Tarihi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('delivered_at')
                    ->label('Teslim Tarihi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'beklemede' => 'Beklemede',
                        'yolda' => 'Yolda',
                        'teslim_edildi' => 'Teslim Edildi',
                        'iptal' => 'İptal',
                    ]),
                
                SelectFilter::make('from_user_id')
                    ->label('Gönderen')
                    ->relationship('fromUser', 'name')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('to_user_id')
                    ->label('Alıcı')
                    ->relationship('toUser', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->visible(fn($record) => $record->status === 'beklemede'),
                
                Action::make('markAsSent')
                    ->label('Yolda Olarak İşaretle')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'beklemede')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('cargo_code')
                            ->label('Kargo Kodu')
                            ->maxLength(255)
                            ->helperText('Kargo takip numarası'),
                        
                        \Filament\Forms\Components\TextInput::make('cargo_company')
                            ->label('Kargo Şirketi')
                            ->maxLength(255)
                            ->helperText('Kargo şirketi adı'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->markAsSent($data['cargo_code'], $data['cargo_company']);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Transfer Yolda Olarak İşaretlendi')
                            ->success()
                            ->body("Transfer {$record->transfer_number} yolda olarak işaretlendi.")
                            ->send();
                    }),
                
                Action::make('markAsDelivered')
                    ->label('Teslim Edildi Olarak İşaretle')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'yolda')
                    ->requiresConfirmation()
                    ->modalHeading('Transfer Teslim Edildi')
                    ->modalDescription('Bu transferi teslim edildi olarak işaretlemek istediğinizden emin misiniz?')
                    ->action(function ($record) {
                        $record->markAsDelivered();
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Transfer Teslim Edildi')
                            ->success()
                            ->body("Transfer {$record->transfer_number} teslim edildi olarak işaretlendi.")
                            ->send();
                    }),
                
                Action::make('cancel')
                    ->label('İptal Et')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => in_array($record->status, ['beklemede', 'yolda']))
                    ->requiresConfirmation()
                    ->modalHeading('Transfer İptal Et')
                    ->modalDescription('Bu transferi iptal etmek istediğinizden emin misiniz?')
                    ->action(function ($record) {
                        $record->cancel();
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Transfer İptal Edildi')
                            ->warning()
                            ->body("Transfer {$record->transfer_number} iptal edildi.")
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
