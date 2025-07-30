<?php

namespace Database\Seeders;

use App\Actions\PaymentMethod\StorePaymentMethodAction;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = require database_path('seeders/data/mobilefix.php');

        foreach ($data['payment_methods'] as $row) {
            $paymentMethod = StorePaymentMethodAction::run([
                'title' => $row['title'],
                'description' => $row['description'],
                'published' => $row['published'],
                'provider' => $row['provider'],
            ]);
        }
    }
}
