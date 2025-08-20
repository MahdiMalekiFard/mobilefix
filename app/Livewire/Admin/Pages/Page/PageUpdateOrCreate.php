<?php

namespace App\Livewire\Admin\Pages\Page;

use App\Actions\Page\StorePageAction;
use App\Actions\Page\UpdatePageAction;
use App\Enums\PageTypeEnum;
use App\Livewire\Traits\SeoOptionTrait;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class PageUpdateOrCreate extends Component
{
    use SeoOptionTrait, Toast, WithFileUploads;

    public Page $model;
    public ?string $title            = '';
    public ?string $body             = '';
    public ?string $type             = PageTypeEnum::ABOUT_US->value;
    public $images;
    public array $existingImages   = [];
    public array $removedNewImages = [];

    public function mount(Page $page): void
    {
        $this->model = $page;
        if ($this->model->id) {
            $this->mountStaticFields();
            $this->title = $this->model->title;
            $this->body  = $this->model->body;
            $this->type  = $this->model->type->value;

            // If editing an About Us page, show plain text in the textarea
            if ($this->type === PageTypeEnum::ABOUT_US->value) {
                $this->body = $this->htmlToTextForUi($this->body ?? '');
            }

            // Load existing media
            $this->existingImages = $this->model->getMedia('images')->map(function ($media) {
                return [
                    'id'        => $media->id,
                    'url'       => $media->getUrl(),
                    'name'      => $media->name,
                    'file_name' => $media->file_name,
                ];
            })->toArray();
        }
    }

    protected function htmlToTextForUi(string $html): string
    {
        $withBreaks = preg_replace('/<\/(p|div|li|h[1-6])>/i', "\n", $html);
        $withBreaks = preg_replace('/<br\s*\/?>/i', "\n", $withBreaks);
        $plain      = html_entity_decode(strip_tags($withBreaks));
        $plain      = preg_replace('/\r?\n\s*\n+/', '\n\n', $plain);
        $plain      = preg_replace('/[ \t]+\n/', "\n", $plain);

        return trim($plain);
    }

    protected function rules(): array
    {
        return array_merge($this->seoOptionRules(), [
            'slug'     => 'required|string|unique:pages,slug,' . $this->model->id,
            'title'    => 'required|string|max:255',
            'body'     => array_merge(
                ['required'],
                $this->type === 'about-us'
                    ? ['string', 'max:5000']      // tighter limit for plain text
                    : ['string']                  // HTML allowed for other types
            ),
            'type'     => 'required|string|in:' . implode(',', PageTypeEnum::values()),
            'images'   => 'nullable',
            'images.*' => 'image|max:2048',
        ]);
    }

    public function updatedBody($value): void
    {
        if ( ! $this->model->id || empty($this->seo_description)) {
            $plain = html_entity_decode(strip_tags($value));
            $plain = preg_replace('/\s+/u', ' ', $plain);
            $plain = trim($plain);

            $this->seo_description = Str::limit($plain, 200);
        }
    }

    public function submit(): void
    {
        $payload = $this->validate();

        // Add media files to payload
        if ($this->images) {
            $payload['images'] = $this->images;
        }

        if ($this->model->id) {
            UpdatePageAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('page.model')]),
                redirectTo: route('admin.page.index')
            );
        } else {
            StorePageAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('page.model')]),
                redirectTo: route('admin.page.index')
            );
        }
    }

    public function deleteImage($mediaId): void
    {
        $media = $this->model->getMedia('images')->find($mediaId);
        if ($media) {
            $media->delete();

            // Refresh the model to clear any cached media collections
            $this->model->refresh();

            // Update the existing images array
            $this->existingImages = $this->model->getMedia('images')->map(function ($media) {
                return [
                    'id'        => $media->id,
                    'url'       => $media->getUrl(),
                    'name'      => $media->name,
                    'file_name' => $media->file_name,
                ];
            })->toArray();

            $this->success('Image deleted successfully');
        }
    }

    public function removeNewImage($index): void
    {
        if (isset($this->images[$index])) {
            $this->removedNewImages[] = $index;
            $this->images             = collect($this->images)->filter(function ($image, $key) {
                return ! in_array($key, $this->removedNewImages);
            })->values()->toArray();
            $this->success('New image removed');
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.page.page-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.page.index'), 'label' => trans('general.page.index.title', ['model' => trans('page.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('page.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.page.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
