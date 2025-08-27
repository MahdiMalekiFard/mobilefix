<?php

namespace App\Livewire\Admin\Pages\Faq;

use App\Actions\Faq\StoreFaqAction;
use App\Actions\Faq\UpdateFaqAction;
use App\Enums\CategoryTypeEnum;
use App\Livewire\Admin\BaseAdminComponent;
use App\Models\Category;
use App\Models\Faq;
use App\Traits\CrudHelperTrait;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Mary\Traits\Toast;

class FaqUpdateOrCreate extends BaseAdminComponent
{
    use CrudHelperTrait, Toast;

    public Faq $model;
    public ?string $title       = '';
    public ?string $description = '';
    public bool $published      = false;
    public bool $favorite       = false;
    public int $ordering        = 1;
    public ?string $published_at;
    public ?int $category_id;
    public array $categories;

    public function mount(Faq $faq): void
    {
        $this->model      = $faq;
        $this->categories = Category::where('published', true)->where('type', CategoryTypeEnum::FAQ->value)->get()->map(function ($category) {
            return [
                'id'    => $category->id,
                'title' => $category->title,
            ];
        })->toArray();
        $this->category_id = count($this->categories) > 0 ? $this->categories[0]['id'] : null;

        if ($this->model->id) {
            $this->title        = $this->model->title;
            $this->description  = $this->model->description;
            $this->published    = $this->model->published->value;
            $this->favorite     = $this->model->favorite->value;
            $this->ordering     = $this->model->ordering;
            $this->category_id  = $this->model->category_id;
            $this->published_at = $this->setPublishedAt($this->model->published_at);
        }
    }

    protected function rules(): array
    {
        return [
            'title'        => 'required|string',
            'description'  => 'required|string',
            'published'    => 'required',
            'favorite'     => 'required',
            'ordering'     => 'required',
            'category_id'  => 'required|exists:categories,id,type,' . CategoryTypeEnum::FAQ->value,
            'published_at' => [
                Rule::requiredIf( ! $this->published),
                'date',
                function ($attribute, $value, $fail) {
                    if ( ! $this->published && $value && Carbon::parse($value)->addMinutes(2)->isBefore(now())) {
                        $fail(trans('faq.exceptions.published_at_after_now'));
                    }
                },
            ],
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
            UpdateFaqAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('faq.model')]),
                redirectTo: route('admin.faq.index')
            );
        } else {
            StoreFaqAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('faq.model')]),
                redirectTo: route('admin.faq.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.faq.faq-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.faq.index'), 'label' => trans('general.page.index.title', ['model' => trans('faq.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('faq.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.faq.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
