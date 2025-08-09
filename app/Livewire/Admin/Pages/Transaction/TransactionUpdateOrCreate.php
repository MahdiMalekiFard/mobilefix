<?php

namespace App\Livewire\Admin\Pages\Transaction;

use App\Actions\Transaction\StoreTransactionAction;
use App\Actions\Transaction\UpdateTransactionAction;
use App\Models\Transaction;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class TransactionUpdateOrCreate extends Component
{
    use Toast;

    public Transaction   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Transaction $transaction): void
    {
        $this->model = $transaction;
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
            UpdateTransactionAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('transaction.model')]),
                redirectTo: route('admin.transaction.index')
            );
        } else {
            StoreTransactionAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('transaction.model')]),
                redirectTo: route('admin.transaction.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.transaction.transaction-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.transaction.index'), 'label' => trans('general.page.index.title', ['model' => trans('transaction.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('transaction.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.transaction.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
