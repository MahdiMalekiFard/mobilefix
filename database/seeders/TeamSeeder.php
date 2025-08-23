<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Team\StoreTeamAction;
use Exception;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/mobilefix.php');

        foreach ($data['teams'] as $row) {
            $team = StoreTeamAction::run([
                'name'         => $row['name'],
                'job'          => $row['job'],
                'special'      => $row['special'],
                'social_media' => $row['social'],
            ]);

            // Add image for the blogs

            try {
                $team->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
        }
    }
}
