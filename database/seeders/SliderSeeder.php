<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Slider\StoreSliderAction;
use Exception;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/mobilefix.php');

        foreach ($data['sliders'] as $row) {
            $slider = StoreSliderAction::run([
                'title'       => $row['title'],
                'description' => $row['description'],
                'published'   => $row['published'],
            ]);

            // Add image for the sliders

            try {
                $slider->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
        }
    }
}
