<?php

namespace App\Livewire\Web\Forms;

use App\Actions\Order\StoreOrderAction;
use Livewire\Component;
use Livewire\WithFileUploads;

class RequestRepairForm extends Component
{
    use WithFileUploads;

    // Form properties
    public $name;
    public $email;
    public $phone;
    public $brand;
    public $model;
    public $problems = [];
    public $description;
    public $videos = [];
    public $images = [];
    
    // Success modal properties
    public $orderNumber = null;

    public function render()
    {
        // Fetch only published brands with their published devices, and published problems
        $brands = \App\Models\Brand::where('published', true)
            ->with(['devices' => function ($query) {
                $query->where('published', true);
            }])
            ->get();

        $all_problems = \App\Models\Problem::where('published', true)->get();

        return view('livewire.web.forms.request-repair-form', [
            'brands' => $brands,
            'all_problems' => $all_problems,
        ]);
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'brand' => 'required|exists:brands,id',
            'model' => 'required|exists:devices,id',
            'problems' => 'required|array|min:1',
            'problems.*' => 'required|exists:problems,id',
            'description' => 'nullable|string|max:1000',
            'videos.*' => 'nullable|mimes:mp4,avi,mov,wmv|max:50000', // 50MB max per video
            'images.*' => 'nullable|image|max:5000', // 5MB max per image
        ];
    }

    public function submit()
    {
        // Validate the form data
        $data = $this->validate();

        try {
            // Create the order with pending status
            $order = app(StoreOrderAction::class)->handle($data);

            // Handle file uploads if any
            if (!empty($this->videos)) {
                foreach ($this->videos as $video) {
                    $order->addMedia($video->getRealPath())
                        ->usingName($video->getClientOriginalName())
                        ->toMediaCollection('videos');
                }
            }

            if (!empty($this->images)) {
                foreach ($this->images as $image) {
                    $order->addMedia($image->getRealPath())
                        ->usingName($image->getClientOriginalName())
                        ->toMediaCollection('images');
                }
            }

            // Store the order number for the modal
            $this->orderNumber = $order->order_number;
            
            // Emit event to show success modal
            $this->dispatch('show-success-modal', trackingCode: $order->order_number);
            
            // Reset form (but keep orderNumber for modal display)
            $this->reset(['name', 'email', 'phone', 'brand', 'model', 'problems', 'description', 'videos', 'images']);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit repair request. Please try again.');
            // Log the error for debugging
            \Log::error('Order creation failed: ' . $e->getMessage());
        }
    }
    
    public function modalClosed()
    {
        // Reset order number when modal is closed
        $this->orderNumber = null;
    }
}
