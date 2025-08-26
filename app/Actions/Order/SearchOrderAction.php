<?php

declare(strict_types=1);

namespace App\Actions\Order;

use Illuminate\Database\Eloquent\Builder;

class SearchOrderAction
{
    public function execute(Builder $query, string $searchTerm): Builder
    {
        if (empty($searchTerm)) {
            return $query;
        }

        $locale = app()->getLocale();

        return $query->where(function ($q) use ($searchTerm, $locale) {
            // Search in order status
            $q->where('status', 'like', '%' . $searchTerm . '%')
            // Search in order number
                ->orWhere('order_number', 'like', '%' . $searchTerm . '%')
            // Search in user fields
                ->orWhereHas('user', function ($subQ) use ($searchTerm) {
                    $subQ->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('mobile', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%');
                })
            // Search in payment method translations
                ->orWhereHas('paymentMethod.translations', function ($subQ) use ($searchTerm, $locale) {
                    $subQ->where('key', 'title')
                        ->where('value', 'like', '%' . $searchTerm . '%')
                        ->where('locale', $locale);
                })
            // Search in brand translations
                ->orWhereHas('brand.translations', function ($subQ) use ($searchTerm, $locale) {
                    $subQ->where('key', 'title')
                        ->where('value', 'like', '%' . $searchTerm . '%')
                        ->where('locale', $locale);
                })
            // Search in config fields (name, phone, email)
                ->orWhere('config->name', 'like', '%' . $searchTerm . '%')
                ->orWhere('config->phone', 'like', '%' . $searchTerm . '%')
                ->orWhere('config->email', 'like', '%' . $searchTerm . '%');
        });
    }
}
