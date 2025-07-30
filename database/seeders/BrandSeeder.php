<?php

namespace Database\Seeders;

use App\Actions\Brand\StoreBrandAction;
use App\Actions\Device\StoreDeviceAction;
use Illuminate\Database\Seeder;
use Exception;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = require database_path('seeders/data/mobilefix.php');

        foreach ($data['brands'] as $row) {
            $brand = StoreBrandAction::run([
                'slug' => $row['slug'],
                'title' => $row['title'],
                'description' => $row['description'],
                'published' => $row['published'],
                'ordering' => $row['ordering'],
                'seo_title'       => $row['seo_options']['title'],
                'seo_description' => $row['seo_options']['description'],
                'canonical'       => $row['seo_options']['canonical'],
                'old_url'         => $row['seo_options']['old_url'],
                'redirect_to'     => $row['seo_options']['redirect_to'],
                'robots_meta'     => $row['seo_options']['robots_meta'],
            ]);

            // Add images to the categories

            try {
                $brand->addMedia($row['path'])
                         ->preservingOriginal()
                         ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }

            // Add devices to the brand
            foreach ($data['devices'] as $device) {
                if ($device['brand_id'] === $brand->id) {
                    $device = StoreDeviceAction::run([
                        'title' => $device['title'],
                        'description' => $device['description'],
                        'slug' => $device['slug'],
                        'published' => $device['published'],
                        'brand_id' => $brand->id,
                        'ordering' => $device['ordering'],
                    ]);
                }
            }
        }
    }
}
