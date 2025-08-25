<?php

namespace App\Livewire\Admin\Pages\ArtGallery;

use App\Actions\ArtGallery\StoreArtGalleryAction;
use App\Actions\ArtGallery\UpdateArtGalleryAction;
use App\Models\ArtGallery;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class ArtGalleryUpdateOrCreate extends Component
{
    use Toast, WithFileUploads;

    public ArtGallery $model;
    public string $title            = '';
    public string $description      = '';
    public bool $published          = false;
    public ?string $icon            = null;
    public array $icons             = [];

    // media
    public $images;
    public $videos;
    public array $existingImages    = [];
    public array $existingVideos    = [];
    public array $removedNewImages  = [];
    public array $removedNewVideos  = [];

    public function mount(ArtGallery $artGallery): void
    {
        $this->model = $artGallery;

        $this->icons    = config('font_awesome.icons');
        $this->icon     = $this->model->icon ?? array_key_first($this->icons);

        if ($this->model->id) {
            $this->title       = $this->model->title;
            $this->description = $this->model->description;
            $this->published   = $this->model->published->value;

            // Load existing media
            $this->existingImages = $this->model->getMedia('images')
                ->reject(function ($media) {
                    // reject if this image is linked as a video poster
                    return $this->model->getMedia('videos')->contains(function ($video) use ($media) {
                        return $video->getCustomProperty('poster_media_id') === $media->id;
                    });
                })
                ->map(function ($media) {
                    return [
                        'id'        => $media->id,
                        'url'       => $media->getUrl(),
                        'name'      => $media->name,
                        'file_name' => $media->file_name,
                    ];
                })->toArray();

            $this->existingVideos = $this->model->getMedia('videos')->map(function ($media) {
                return [
                    'id'              => $media->id,
                    'url'             => $media->getUrl(),
                    'name'            => $media->name,
                    'file_name'       => $media->file_name,
                    'poster_url'      => $media->getCustomProperty('poster_url'),
                    'poster_media_id' => $media->getCustomProperty('poster_media_id'),
                ];
            })->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'title'       => 'required|string',
            'description' => 'required|string',
            'published'   => 'required|boolean',
            'icon'        => ['required', 'string', Rule::in(array_keys($this->icons))],
            'images'      => 'nullable',
            'images.*'    => 'image|max:2048',
            'videos'      => 'nullable',
            'videos.*'    => 'mimes:mp4,avi,mov,wmv,webm|max:10240',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();

        // Add media files to payload
        if ($this->images) {
            $payload['images'] = $this->images;
        }
        if ($this->videos) {
            $payload['videos'] = $this->videos;
        }

        if ($this->model->id) {
            UpdateArtGalleryAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('artGallery.model')]),
                redirectTo: route('admin.art-gallery.index')
            );
        } else {
            StoreArtGalleryAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('artGallery.model')]),
                redirectTo: route('admin.art-gallery.index')
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

    public function deleteVideo($mediaId): void
    {
        $media = $this->model->getMedia('videos')->find($mediaId);

        if ($media) {
            // also delete poster if we have it
            $posterId = $media->getCustomProperty('poster_media_id');

            $media->delete();

            if ($posterId) {
                $poster = $this->model->media()->find($posterId);
                if ($poster) {
                    $poster->delete();
                }
            }

            // Refresh the model to clear any cached media collections
            $this->model->refresh();

            // Update the existing videos array
            $this->existingVideos = $this->model->getMedia('videos')->map(function ($media) {
                return [
                    'id'              => $media->id,
                    'url'             => $media->getUrl(),
                    'name'            => $media->name,
                    'file_name'       => $media->file_name,
                    'poster_url'      => $media->getCustomProperty('poster_url'),
                    'poster_media_id' => $media->getCustomProperty('poster_media_id'),
                ];
            })->toArray();

            $this->success('Video deleted successfully');
        }
    }

    public function removeNewImage($index): void
    {
        if (isset($this->images[$index])) {
            $this->removedNewImages[] = $index;
            $this->images             = collect($this->images)->filter(function ($image, $key) {
                return ! in_array($key, $this->removedNewImages, true);
            })->values()->toArray();
            $this->success('New image removed');
        }
    }

    public function removeNewVideo($index): void
    {
        if (isset($this->videos[$index])) {
            $this->removedNewVideos[] = $index;
            $this->videos             = collect($this->videos)->filter(function ($video, $key) {
                return ! in_array($key, $this->removedNewVideos, true);
            })->values()->toArray();
            $this->success('New video removed');
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.artGallery.artGallery-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.art-gallery.index'), 'label' => trans('general.page.index.title', ['model' => trans('artGallery.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('artGallery.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.art-gallery.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
