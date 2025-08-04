<?php

namespace App\Livewire\Admin\Pages\Order;

use App\Actions\Order\StoreOrderAction;
use App\Actions\Order\UpdateOrderAction;
use App\Enums\OrderStatusEnum;
use App\Models\Address;
use App\Models\Brand;
use App\Models\Device;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Problem;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class OrderUpdateOrCreate extends Component
{
    use Toast, WithFileUploads;

    public Order   $model;
    public string $order_number = '';
    public string $tracking_code = '';
    public string $status       = '';
    public int    $total        = 0;
    public string $user_name    = '';
    public string $user_phone   = '';
    public string $user_email   = '';
    public ?int    $brand_id     = null;
    public ?int    $device_id   = null;
    public ?int    $user_id      = null;
    public ?int    $address_id   = null;
    public ?int    $payment_method_id = null;
    public array  $selectedProblems = [];
    public string $user_note     = '';
    public string $admin_note    = '';
    public $images;
    public $videos;
    public array $existingImages = [];
    public array $existingVideos = [];
    public array $removedNewImages = [];
    public array $removedNewVideos = [];

    public function mount(Order $order): void
    {
        $this->model = $order;
        if ($this->model->id) {
            $this->order_number = $this->model->order_number;
            $this->tracking_code = $this->model->tracking_code;
            $this->status = $this->model->status;
            $this->total = $this->model->total;
            $this->user_name = $this->model->config()->get('name');
            $this->user_phone = $this->model->config()->get('phone');
            $this->user_email = $this->model->config()->get('email');
            $this->brand_id = $this->model->brand_id;
            $this->device_id = $this->model->device_id;
            $this->user_id = $this->model->user_id;
            $this->address_id = $this->model->address_id;
            $this->payment_method_id = $this->model->payment_method_id;
            $this->selectedProblems = $this->model->problems->pluck('id')->toArray();
            $this->user_note = $this->model->user_note ?? '';
            $this->admin_note = $this->model->admin_note ?? '';
            
            // Load existing media
            $this->existingImages = $this->model->getMedia('images')->map(function($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'name' => $media->name,
                    'file_name' => $media->file_name
                ];
            })->toArray();
            
            $this->existingVideos = $this->model->getMedia('videos')->map(function($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'name' => $media->name,
                    'file_name' => $media->file_name
                ];
            })->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'order_number' => 'required|string|unique:orders,order_number,' . $this->model->id,
            'tracking_code' => 'required|string|unique:orders,tracking_code,' . $this->model->id,
            'status'       => 'required|string',
            'total'        => 'required|integer|min:0',
            'user_name'    => 'required|string|max:255',
            'user_phone'   => 'required|string|max:255',
            'user_email'   => 'required|string|max:255',
            'brand_id'     => 'nullable|integer',
            'device_id'    => 'nullable|integer',
            'user_id'      => 'nullable|integer',
            'address_id'   => 'nullable|integer',
            'payment_method_id' => 'nullable|integer',
            'selectedProblems' => 'nullable|array',
            'selectedProblems.*' => 'exists:problems,id',
            'user_note'    => 'nullable|string',
            'admin_note'   => 'nullable|string',
            'images'       => 'nullable',
            'images.*'     => 'image|max:2048',
            'videos'       => 'nullable',
            'videos.*'     => 'mimes:mp4,avi,mov,wmv,webm|max:10240',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        $payload['problems'] = $payload['selectedProblems'];
        unset($payload['selectedProblems']);
        
        // Add media files to payload
        if ($this->images) {
            $payload['images'] = $this->images;
        }
        if ($this->videos) {
            $payload['videos'] = $this->videos;
        }
        
        if ($this->model->id) {
            UpdateOrderAction::run($this->model, $payload);
            
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('order.model')]),
                redirectTo: route('admin.order.index')
            );
        } else {
            StoreOrderAction::run($payload);
            
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('order.model')]),
                redirectTo: route('admin.order.index')
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
            $this->existingImages = $this->model->getMedia('images')->map(function($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'name' => $media->name,
                    'file_name' => $media->file_name
                ];
            })->toArray();
            
            $this->success('Image deleted successfully');
        }
    }

    public function deleteVideo($mediaId): void
    {
        $media = $this->model->getMedia('videos')->find($mediaId);
        if ($media) {
            $media->delete();
            
            // Refresh the model to clear any cached media collections
            $this->model->refresh();
            
            // Update the existing videos array
            $this->existingVideos = $this->model->getMedia('videos')->map(function($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'name' => $media->name,
                    'file_name' => $media->file_name
                ];
            })->toArray();
            
            $this->success('Video deleted successfully');
        }
    }

    public function removeNewImage($index): void
    {
        if (isset($this->images[$index])) {
            $this->removedNewImages[] = $index;
            $this->images = collect($this->images)->filter(function($image, $key) {
                return !in_array($key, $this->removedNewImages);
            })->values()->toArray();
            $this->success('New image removed');
        }
    }

    public function removeNewVideo($index): void
    {
        if (isset($this->videos[$index])) {
            $this->removedNewVideos[] = $index;
            $this->videos = collect($this->videos)->filter(function($video, $key) {
                return !in_array($key, $this->removedNewVideos);
            })->values()->toArray();
            $this->success('New video removed');
        }
    }

    public function render(): View
    {
        // Format status options with badge information
        $statusOptions = collect(OrderStatusEnum::formatedCases())->map(function ($status) {
            $statusEnum = OrderStatusEnum::from($status['id']);
            return [
                'value' => $status['id'],
                'label' => $status['title'],
                'badge' => [
                    'text' => $status['title'],
                    'color' => $statusEnum->color()
                ]
            ];
        })->toArray();

        return view('livewire.admin.pages.order.order-update-or-create', [
            'edit_mode'          => $this->model->id,
            'statusOptions'      => $statusOptions,
            'users'              => User::all(['id', 'name']),
            'brands'             => Brand::all(),
            'devices'            => Device::all(),
            'addresses'          => Address::all(['id', 'title']),
            'paymentMethods'     => PaymentMethod::all(),
            'problems'           => Problem::all(),
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.order.index'), 'label' => trans('general.page.index.title', ['model' => trans('order.model')])],
                ['label' => $this->model->id ? trans('general.page.edit.title', ['model' => trans('order.model')]) : trans('general.page.create.title', ['model' => trans('order.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.order.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
