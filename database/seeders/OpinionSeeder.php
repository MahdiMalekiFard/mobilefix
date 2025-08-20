<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Opinion\StoreOpinionAction;
use Exception;
use Illuminate\Database\Seeder;

class OpinionSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/mobilefix.php');

        foreach ($data['opinions'] as $row) {
            $opinion = StoreOpinionAction::run([
                'user_name'    => $row['user_name'],
                'company'      => $row['company'],
                'comment'      => $row['comment'],
                'ordering'     => $row['ordering'],
                'view_count'   => $row['view_count'],
                'published'    => $row['published'],
                'published_at' => $row['published_at'],
            ]);

            // Add image for the sliders

            try {
                $opinion->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
        }
    }
}
