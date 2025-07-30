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

            session()->flash('success', 'Repair request submitted successfully! Your order number is: ' . $order->order_number);
            
            // Reset form
            $this->reset();
            
            // Optionally redirect or emit an event
            // return redirect()->route('repair-success');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit repair request. Please try again.');
            // Log the error for debugging
            \Log::error('Order creation failed: ' . $e->getMessage());
        }
    }
}
