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

    public function mount(Order $order): void
    {
        $this->model = $order;
        if ($this->model->id) {
            $this->order_number = $this->model->order_number;
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
        }
    }

    protected function rules(): array
    {
        return [
            'order_number' => 'required|string|unique:orders,order_number,' . $this->model->id,
            'status'       => 'required|string',
            'total'        => 'required|integer',
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
                ['label' => trans('general.page.create.title', ['model' => trans('order.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.order.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
