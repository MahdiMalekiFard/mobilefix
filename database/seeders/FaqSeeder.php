<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Faq\StoreFaqAction;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/mobilefix.php');

        foreach ($data['faqs'] as $row) {
            $faq = StoreFaqAction::run([
                'title'        => $row['title'],
                'description'  => $row['description'],
                'ordering'     => $row['ordering'],
                'category_id'  => $row['category_id'],
                'favorite'     => $row['favorite'],
                'published'    => $row['published'],
                'published_at' => $row['published_at'],
            ]);
        }
    }
}
