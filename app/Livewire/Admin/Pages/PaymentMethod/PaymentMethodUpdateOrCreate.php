<?php

namespace App\Livewire\Admin\Pages\PaymentMethod;

use App\Actions\PaymentMethod\StorePaymentMethodAction;
use App\Actions\PaymentMethod\UpdatePaymentMethodAction;
use App\Models\PaymentMethod;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class PaymentMethodUpdateOrCreate extends Component
{
    use Toast;

    public PaymentMethod   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(PaymentMethod $paymentMethod): void
    {
        $this->model = $paymentMethod;
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->description = $this->model->description;
            $this->published = $this->model->published->value;
        }
    }

    protected function rules(): array
    {
        return [
            'title'       => 'required|string',
            'description' => 'required|string',
            'published'   => 'required'
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdatePaymentMethodAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('paymentMethod.model')]),
                redirectTo: route('admin.paymentMethod.index')
            );
        } else {
            StorePaymentMethodAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('paymentMethod.model')]),
                redirectTo: route('admin.paymentMethod.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.paymentMethod.paymentMethod-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.paymentMethod.index'), 'label' => trans('general.page.index.title', ['model' => trans('paymentMethod.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('paymentMethod.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.paymentMethod.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
