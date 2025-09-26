# Rol Yönetimi Sistemi Dokümantasyonu

## Genel Bakış

Bu proje, Laravel 12.x ve Filament v4.x kullanılarak geliştirilmiş bir rol tabanlı yetkilendirme sistemidir. Spatie Laravel Permission paketi kullanılarak 4 farklı kullanıcı rolü tanımlanmıştır.

## Sistem Rolleri

### 1. Admin (admin)
- **Açıklama**: Sistem yöneticisi, tüm yetkilere sahip
- **İzinler**: Tüm sistem izinleri
- **Erişim**: Tam sistem erişimi

### 2. Dealer (dealer)
- **Açıklama**: Bayi yöneticisi
- **İzinler**:
  - `view_dashboard` - Dashboard görüntüleme
  - `manage_workers` - Çalışanları yönetme
  - `view_reports` - Raporları görüntüleme
  - `manage_products` - Ürünleri yönetme
  - `manage_orders` - Siparişleri yönetme

### 3. Worker (worker)
- **Açıklama**: Bayi çalışanı
- **İzinler**:
  - `view_dashboard` - Dashboard görüntüleme
  - `view_reports` - Raporları görüntüleme
  - `manage_products` - Ürünleri yönetme
  - `manage_orders` - Siparişleri yönetme

### 4. Central Worker (central_worker)
- **Açıklama**: Merkez çalışanı
- **İzinler**:
  - `view_dashboard` - Dashboard görüntüleme
  - `view_reports` - Raporları görüntüleme
  - `view_analytics` - Analitik verileri görüntüleme
  - `manage_products` - Ürünleri yönetme
  - `view_financials` - Finansal verileri görüntüleme

## Teknik Yapı

### Kurulum
```bash
# Spatie Laravel Permission paketi
composer require spatie/laravel-permission

# Yapılandırma dosyalarını yayınla
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Migration'ları çalıştır
php artisan migrate

# Rolleri ve test kullanıcılarını oluştur
php artisan db:seed
```

### Dosya Yapısı

#### 1. User Model (`app/Models/User.php`)
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    // ...
}
```

#### 2. Role Seeder (`database/seeders/RoleSeeder.php`)
- Rolleri oluşturur
- İzinleri tanımlar
- Rol-izin ilişkilerini kurar

#### 3. Database Seeder (`database/seeders/DatabaseSeeder.php`)
- Test kullanıcıları oluşturur
- Her role örnek kullanıcı atar

## Kullanım Örnekleri

### Filament'te Rol Kontrolü

#### 1. Panel Erişimi
```php
// User modelinde
public function canAccessPanel(Panel $panel): bool
{
    return $this->hasRole(['admin', 'dealer', 'central_worker']);
}
```

#### 2. Resource Erişimi
```php
// Resource'da
public static function canAccess(): bool
{
    return auth()->user()->hasRole(['admin', 'dealer']);
}
```

#### 3. Action Yetkilendirme
```php
// Action'da
Action::make('delete')
    ->visible(fn (): bool => auth()->user()->hasRole('admin'))
    ->authorize('delete');
```

#### 4. Form Field Görünürlüğü
```php
// Form'da
TextInput::make('admin_field')
    ->visible(fn (): bool => auth()->user()->hasRole('admin'));
```

### Laravel'de Rol Kontrolü

#### 1. Middleware Kullanımı
```php
// Route'da
Route::middleware(['role:admin'])->group(function () {
    // Admin only routes
});

Route::middleware(['role:dealer|central_worker'])->group(function () {
    // Dealer ve Central Worker routes
});
```

#### 2. Blade Template'te
```blade
@role('admin')
    <div>Admin only content</div>
@endrole

@hasrole('dealer')
    <div>Dealer content</div>
@endhasrole
```

#### 3. Controller'da
```php
public function index()
{
    if (auth()->user()->hasRole('admin')) {
        // Admin logic
    } elseif (auth()->user()->hasRole('dealer')) {
        // Dealer logic
    }
}
```

## Test Kullanıcıları

Seeder çalıştırıldıktan sonra aşağıdaki test kullanıcıları oluşturulur:

| Email | Rol | Şifre |
|-------|-----|-------|
| admin@example.com | admin | (factory default) |
| dealer@example.com | dealer | (factory default) |
| worker@example.com | worker | (factory default) |
| central@example.com | central_worker | (factory default) |

## Yeni Rol Ekleme

### 1. Seeder'da Yeni Rol
```php
// RoleSeeder.php
$newRole = Role::create(['name' => 'new_role']);
$newRole->givePermissionTo(['view_dashboard', 'view_reports']);
```

### 2. Yeni İzin Ekleme
```php
// RoleSeeder.php
$newPermission = Permission::create(['name' => 'new_permission']);
$adminRole->givePermissionTo($newPermission);
```

### 3. Seeder'ı Yeniden Çalıştırma
```bash
php artisan db:seed --class=RoleSeeder
```

## Filament Entegrasyonu

### 1. Panel Yapılandırması
```php
// AdminPanelProvider.php
public function panel(Panel $panel): Panel
{
    return $panel
        ->authGuard('web')
        ->authPasswordBroker('users');
}
```

### 2. Resource Yetkilendirme
```php
// Resource'da
protected static bool $shouldSkipAuthorization = false;

public static function canAccess(): bool
{
    return auth()->user()->hasAnyRole(['admin', 'dealer']);
}
```

### 3. Action Yetkilendirme
```php
// Action'da
Action::make('special_action')
    ->visible(fn (): bool => auth()->user()->hasRole('admin'))
    ->requiresConfirmation();
```

## Güvenlik Notları

1. **Rol Kontrolü**: Her kritik işlemde rol kontrolü yapın
2. **Middleware**: Route seviyesinde rol kontrolü kullanın
3. **Policy**: Karmaşık yetkilendirme için Policy kullanın
4. **Test**: Rol tabanlı testler yazın

## Sorun Giderme

### 1. Rol Atanmıyor
```bash
# Cache temizle
php artisan cache:clear
php artisan config:clear
```

### 2. İzin Kontrolü Çalışmıyor
```php
// User modelinde HasRoles trait'inin eklendiğini kontrol edin
use Spatie\Permission\Traits\HasRoles;
```

### 3. Migration Hataları
```bash
# Migration'ları sıfırla
php artisan migrate:fresh --seed
```

## Geliştirme Notları

- Bu sistem AI agent'lar için optimize edilmiştir
- Rol isimleri snake_case formatında tutulmuştur
- İzin isimleri açıklayıcı ve tutarlıdır
- Test kullanıcıları her rol için mevcuttur
- Dokümantasyon güncel tutulmalıdır

## İletişim

Bu dokümantasyon AI agent'lar için hazırlanmıştır. Güncellemeler için proje sahibi ile iletişime geçin.
