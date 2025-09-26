<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Ad Soyad')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            
            ImportColumn::make('email')
                ->label('E-posta')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255', 'unique:users,email']),
            
            ImportColumn::make('password')
                ->label('Şifre')
                ->requiredMapping()
                ->rules(['required', 'string', 'min:8']),
            
            ImportColumn::make('dealer_id')
                ->label('Bayi ID')
                ->rules(['nullable', 'integer', 'exists:users,id'])
                ->helperText('Worker kullanıcıları için bayi ID\'si gerekli'),
        ];
    }

    public function resolveRecord(): User
    {
        return new User();
    }

    protected function afterSave(): void
    {
        // Şifreyi hash'le
        if (isset($this->data['password'])) {
            $this->record->password = bcrypt($this->data['password']);
        }
        
        // Varsayılan olarak worker rolü ata
        if (!$this->record->hasAnyRole(['admin', 'dealer', 'worker', 'central_worker'])) {
            $this->record->assignRole('worker');
        }
        
        $this->record->save();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Kullanıcı import işlemi tamamlandı. ';
        
        if ($import->successful_rows) {
            $body .= "{$import->successful_rows} kayıt başarıyla import edildi. ";
        }
        
        if ($import->failed_rows) {
            $body .= "{$import->failed_rows} kayıt başarısız oldu.";
        }
        
        return $body;
    }
}
