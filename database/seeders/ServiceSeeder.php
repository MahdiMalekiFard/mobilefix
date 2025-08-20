<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Service\StoreServiceAction;
use Exception;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/mobilefix.php');

        foreach ($data['services'] as $row) {
            $service = StoreServiceAction::run([
                'title'           => $row['title'],
                'description'     => $row['description'],
                'body'            => $row['body'],
                'slug'            => $row['slug'],
                'published'       => $row['published'],
                'seo_title'       => $row['seo_options']['title'],
                'seo_description' => $row['seo_options']['description'],
                'canonical'       => $row['seo_options']['canonical'],
                'old_url'         => $row['seo_options']['old_url'],
                'redirect_to'     => $row['seo_options']['redirect_to'],
                'robots_meta'     => $row['seo_options']['robots_meta'],
            ]);

            // Add image for the blogs

            try {
                $service->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
        }
    }
}
