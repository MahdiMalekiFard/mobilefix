<?php

namespace App\Traits;

use App\Helpers\StringHelper;
use Illuminate\Support\Facades\Lang;
use Livewire\Attributes\On;

trait PowerGridHelperTrait
{
    #[On('toggle')]
    public function toggle($rowId, $field='published'): void
    {
        $model = $this->datasource()->getModel()::where('id', $rowId)->first();
        $model->update([$field => ! $model->{$field}->value]);
        if ($model->published_at) {
            $model->update(['published_at' => now()->format('Y-m-d')]);
        }
    }

    #[On('force-delete')]
    public function forceDelete($rowId): void
    {
        $modelClass = StringHelper::basename($this->datasource()->getModel()::class);
        $action     = resolve('\\App\\Actions\\' . $modelClass . '\\Delete' . $modelClass . 'Action');
        $model      = $this->datasource()->getModel()::where('id', $rowId)->first();
        $action::run($model);
    }

    #[On('delete')]
    public function delete($rowId): void
    {
        $this->js('Swal.fire({
          title: "' . trans('powergrid.modal.delete.title') . '",
          text: "' . trans('powergrid.modal.delete.body') . '",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "' . trans('powergrid.modal.delete.footer.delete') . '",
          cancelButtonText: "' . trans('powergrid.modal.delete.footer.cancel') . '",
         }).then((result) => {
         if (result.isConfirmed) {
         Livewire.dispatch("force-delete", { rowId: ' . $rowId . ' });
         }
         })');
    }

    /** Force translator to fallback just for admin area (UI strings only) */
    private function forceAdminFallbackTranslator(): void
    {
        // works for normal requests and Livewire /livewire/message/* thanks to session flag
        if (session('__area') === 'admin' || request()->is('admin/*')) {
            $fallback = config('app.fallback_locale', 'en');
            Lang::setLocale($fallback);
            app('translator')->setLocale($fallback);
        }
    }

    /** Runs on initial load (and before mount) */
    public function boot(): void
    {
        $this->forceAdminFallbackTranslator();
    }

    /** Runs on every Livewire AJAX hydration */
    public function hydrate(): void
    {
        $this->forceAdminFallbackTranslator();
    }
}
