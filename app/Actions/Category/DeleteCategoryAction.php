<?php

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteCategoryAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Category $category): bool
    {
        return DB::transaction(function () use ($category) {
            return $category->delete();
        });
    }
}
