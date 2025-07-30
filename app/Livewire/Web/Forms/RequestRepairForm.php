<?php

namespace App\Livewire\Web\Forms;

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
    public $problem;
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

        $problems = \App\Models\Problem::where('published', true)->get();

        return view('livewire.web.forms.request-repair-form', [
            'brands' => $brands,
            'problems' => $problems,
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
            'problem' => 'required|exists:problems,id',
            'description' => 'nullable|string|max:1000',
            'videos.*' => 'nullable|mimes:mp4,avi,mov,wmv|max:50000', // 50MB max per video
            'images.*' => 'nullable|image|max:5000', // 5MB max per image
        ];
    }

    public function submit()
    {
        // Validate the form data
        $data = $this->validate();

        // Handle file uploads if needed
        // Store videos and images to storage
        // $this->videos and $this->images contain the uploaded files

        // For now, just show the data (you can replace this with actual save logic)
        session()->flash('success', 'Repair request submitted successfully!');
        
        // Reset form
        $this->reset();
        
        // Optionally redirect or emit an event
        // return redirect()->route('repair-success');
    }
}
