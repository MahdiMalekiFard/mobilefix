<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Page\StorePageAction;
use Exception;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/mobilefix.php');

        foreach ($data['pages'] as $row) {
            $page = StorePageAction::run([
                'title'           => $row['title'],
                'body'            => $row['body'],
                'type'            => $row['type'],
                'slug'            => $row['slug'],
                'view_count'      => $row['view_count'],
                'seo_title'       => $row['seo_options']['title'],
                'seo_description' => $row['seo_options']['description'],
                'canonical'       => $row['seo_options']['canonical'],
                'old_url'         => $row['seo_options']['old_url'],
                'redirect_to'     => $row['seo_options']['redirect_to'],
                'robots_meta'     => $row['seo_options']['robots_meta'],
            ]);

            // Add images for the (about-us) page

            if (array_key_exists('images', $row)) {
                foreach ($row['images'] as $image) {
                    try {
                        $page->addMedia($image)
                            ->preservingOriginal()
                            ->toMediaCollection('image');
                    } catch (Exception) {
                        // do nothing
                    }
                }
            }
        }
    }
}
