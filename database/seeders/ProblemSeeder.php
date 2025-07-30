<?php

namespace Database\Seeders;

use App\Actions\Problem\StoreProblemAction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProblemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = require database_path('seeders/data/mobilefix.php');

        foreach ($data['problems'] as $row) {
            $problem = StoreProblemAction::run([
                'title' => $row['title'],
                'description' => $row['description'],
                'published' => $row['published'],
                'ordering' => $row['ordering'],
                'min_price' => $row['min_price'],
                'max_price' => $row['max_price'],
                'config' => $row['config'],
            ]);
        }
    }
}
