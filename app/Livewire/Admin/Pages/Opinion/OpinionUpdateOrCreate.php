<?php

namespace App\Livewire\Admin\Pages\Opinion;

use App\Actions\Opinion\StoreOpinionAction;
use App\Actions\Opinion\UpdateOpinionAction;
use App\Livewire\Admin\BaseAdminComponent;
use App\Models\Opinion;
use App\Traits\CrudHelperTrait;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class OpinionUpdateOrCreate extends BaseAdminComponent
{
    use CrudHelperTrait, Toast, WithFileUploads;

    public Opinion $model;
    public string $user_name       = '';
    public string $comment         = '';
    public string $company         = '';
    public bool $published         = false;
    public int $ordering           = 1;
    public ?string $published_at;
    public $image;

    public function mount(Opinion $opinion): void
    {
        $this->model   = $opinion;
        $this->ordering=Opinion::query()->count() + 1;
        if ($this->model->id) {
            $this->user_name    = $this->model->user_name;
            $this->comment      = $this->model->comment;
            $this->company      = $this->model->company;
            $this->published    = $this->model->published->value;
            $this->ordering     = $this->model->ordering;
            $this->published_at = $this->setPublishedAt($this->model->published_at);
        }
    }

    protected function rules(): array
    {
        return [
            'user_name'    => 'required|string',
            'comment'      => 'required|string',
            'company'      => 'nullable|string',
            'published'    => ['required', 'boolean'],
            'ordering'     => ['required', 'numeric'],
            'published_at' => [
                Rule::requiredIf( ! $this->published),
                'date',
                function ($attribute, $value, $fail) {
                    if ( ! $this->published && $value && Carbon::parse($value)->addMinutes(2)->isBefore(now())) {
                        $fail(trans('slider.exceptions.published_at_after_now'));
                    }
                },
            ],
            'image'        => 'nullable|image|max:1024',
        ];
    }

    public function updatedPublished($value): void
    {
        if ($value) {
            $this->published_at = now()->format('Y-m-d');
        }
    }

    public function submit(): void
    {
        $payload = $this->validate();

        // If published is true, automatically set published_at to now
        if ($this->published) {
            $payload['published_at'] = now();
        }

        $payload = $this->normalizePublishedAt($payload);

        if ($this->model->id) {
            UpdateOpinionAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('opinion.model')]),
                redirectTo: route('admin.opinion.index')
            );
        } else {
            StoreOpinionAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('opinion.model')]),
                redirectTo: route('admin.opinion.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.opinion.opinion-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.opinion.index'), 'label' => trans('general.page.index.title', ['model' => trans('opinion.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('opinion.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.opinion.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
