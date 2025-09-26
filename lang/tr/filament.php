<?php

return [
    'actions' => [
        'attach' => 'Ekle',
        'cancel' => 'İptal',
        'close' => 'Kapat',
        'confirm' => 'Onayla',
        'create' => 'Oluştur',
        'delete' => 'Sil',
        'detach' => 'Ayır',
        'edit' => 'Düzenle',
        'export' => 'Dışa Aktar',
        'import' => 'İçe Aktar',
        'new' => 'Yeni',
        'save' => 'Kaydet',
        'search' => 'Ara',
        'submit' => 'Gönder',
        'view' => 'Görüntüle',
    ],
    'fields' => [
        'name' => 'Ad',
        'email' => 'E-posta',
        'password' => 'Şifre',
        'password_confirmation' => 'Şifre Onayı',
        'remember' => 'Beni Hatırla',
    ],
    'labels' => [
        'actions' => 'İşlemler',
        'created_at' => 'Oluşturulma Tarihi',
        'updated_at' => 'Güncellenme Tarihi',
        'deleted_at' => 'Silinme Tarihi',
    ],
    'navigation' => [
        'dashboard' => 'Kontrol Paneli',
        'users' => 'Kullanıcılar',
        'roles' => 'Roller',
    ],
    'pages' => [
        'dashboard' => [
            'title' => 'Kontrol Paneli',
        ],
    ],
    'resources' => [
        'users' => [
            'label' => 'Kullanıcı',
            'plural_label' => 'Kullanıcılar',
            'navigation_label' => 'Kullanıcılar',
        ],
        'roles' => [
            'label' => 'Rol',
            'plural_label' => 'Roller',
            'navigation_label' => 'Roller',
        ],
    ],
    'tables' => [
        'actions' => 'İşlemler',
        'bulk_actions' => 'Toplu İşlemler',
        'columns' => 'Sütunlar',
        'filters' => 'Filtreler',
        'search' => 'Ara',
        'sort' => 'Sırala',
    ],
    'validation' => [
        'required' => 'Bu alan zorunludur.',
        'email' => 'Geçerli bir e-posta adresi giriniz.',
        'min' => 'En az :min karakter olmalıdır.',
        'max' => 'En fazla :max karakter olmalıdır.',
        'confirmed' => 'Onay eşleşmiyor.',
        'unique' => 'Bu değer zaten kullanılıyor.',
    ],
];
