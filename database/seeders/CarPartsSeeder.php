<?php

namespace Database\Seeders;

use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarPartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚗 Araç parçaları verisi yükleniyor...');

        // JSON dosyasını oku
        $jsonPath = storage_path('app/private/carparts.json');
        if (!File::exists($jsonPath)) {
            $this->command->error('❌ carparts.json dosyası bulunamadı!');
            return;
        }

        $jsonData = json_decode(File::get($jsonPath), true);
        if (!$jsonData) {
            $this->command->error('❌ JSON dosyası okunamadı!');
            return;
        }

        // Brands verilerini işle
        $this->processBrands($jsonData);
        
        // Models verilerini işle
        $this->processModels($jsonData);

        $this->command->info('✅ Araç parçaları verisi başarıyla yüklendi!');
    }

    private function processBrands(array $jsonData): void
    {
        $this->command->info('📋 Markalar işleniyor...');

        $brandsData = [];
        foreach ($jsonData as $item) {
            if (isset($item['type']) && $item['type'] === 'table' && $item['name'] === 'car_brands') {
                $brandsData = $item['data'] ?? [];
                break;
            }
        }

        if (empty($brandsData)) {
            $this->command->warn('⚠️ Marka verisi bulunamadı!');
            return;
        }

        $progressBar = $this->command->getOutput()->createProgressBar(count($brandsData));
        $progressBar->start();

        foreach ($brandsData as $brandData) {
            try {
                $brand = CarBrand::updateOrCreate(
                    ['external_id' => $brandData['external_id']],
                    [
                        'name' => $brandData['name'],
                        'logo' => $this->processLogo($brandData['logo']),
                        'last_update' => $brandData['last_update'] ? \Carbon\Carbon::parse($brandData['last_update']) : null,
                        'is_active' => (bool) $brandData['is_active'],
                    ]
                );

                $progressBar->advance();
            } catch (\Exception $e) {
                $this->command->error("❌ Marka işlenirken hata: {$e->getMessage()}");
            }
        }

        $progressBar->finish();
        $this->command->newLine();
        $this->command->info("✅ " . count($brandsData) . " marka işlendi!");
    }

    private function processModels(array $jsonData): void
    {
        $this->command->info('🚙 Modeller işleniyor...');

        $modelsData = [];
        foreach ($jsonData as $item) {
            if (isset($item['type']) && $item['type'] === 'table' && $item['name'] === 'car_models') {
                $modelsData = $item['data'] ?? [];
                break;
            }
        }

        if (empty($modelsData)) {
            $this->command->warn('⚠️ Model verisi bulunamadı!');
            return;
        }

        $progressBar = $this->command->getOutput()->createProgressBar(count($modelsData));
        $progressBar->start();

        foreach ($modelsData as $modelData) {
            try {
                // Brand ID'yi bul
                $brand = CarBrand::where('external_id', $modelData['brand_id'])->first();
                if (!$brand) {
                    $this->command->warn("⚠️ Brand bulunamadı: {$modelData['brand_id']}");
                    continue;
                }

                CarModel::updateOrCreate(
                    ['external_id' => $modelData['external_id']],
                    [
                        'brand_id' => $brand->id,
                        'name' => $modelData['name'],
                        'last_update' => $modelData['last_update'] ? \Carbon\Carbon::parse($modelData['last_update']) : null,
                        'is_active' => (bool) $modelData['is_active'],
                    ]
                );

                $progressBar->advance();
            } catch (\Exception $e) {
                $this->command->error("❌ Model işlenirken hata: {$e->getMessage()}");
            }
        }

        $progressBar->finish();
        $this->command->newLine();
        $this->command->info("✅ " . count($modelsData) . " model işlendi!");
    }

    private function processLogo(?string $logoUrl): ?string
    {
        if (!$logoUrl) {
            return null;
        }

        try {
            // URL'den dosya adını çıkar
            $filename = basename(parse_url($logoUrl, PHP_URL_PATH));
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            
            // Eğer uzantı yoksa, URL'den çıkar
            if (!$extension) {
                $extension = 'png'; // Varsayılan uzantı
            }

            // Benzersiz dosya adı oluştur
            $uniqueFilename = Str::slug(pathinfo($filename, PATHINFO_FILENAME)) . '-' . time() . '.' . $extension;
            $localPath = "car-brands/{$uniqueFilename}";

            // Dosyayı indir
            $response = Http::timeout(30)->get($logoUrl);
            if ($response->successful()) {
                // Storage'a kaydet
                Storage::disk('public')->put($localPath, $response->body());
                
                $this->command->info("📸 Logo indirildi: {$logoUrl} -> {$localPath}");
                return $localPath;
            } else {
                $this->command->warn("⚠️ Logo indirilemedi: {$logoUrl}");
                return null;
            }
        } catch (\Exception $e) {
            $this->command->warn("⚠️ Logo işlenirken hata: {$e->getMessage()}");
            return null;
        }
    }
}
