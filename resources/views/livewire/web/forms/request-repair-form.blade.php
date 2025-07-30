<div class="">
    <div class="appointment">
        <div class="container-fluid">
            <div class="appointment-form">
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form wire:submit.prevent="submit" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Enter Name" wire:model="name" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Enter Email" wire:model="email" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Enter Phone" wire:model="phone" required>
                            </div>
                        </div>
                        <!-- Brand select from database -->
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <select class="form-select" name="brand" id="brand-select" wire:model="brand" required onchange="filterModelsByBrand()">
                                    <option value="">Choose Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Device/Model select, dynamically filtered by brand -->
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <select class="form-select" name="model" id="model-select" wire:model="model" required>
                                    <option value="">Choose Model</option>
                                    @foreach($brands as $brand)
                                        @foreach($brand->devices as $device)
                                            <option value="{{ $device->id }}" data-brand="{{ $brand->id }}" style="display:none;">{{ $device->title }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Problem select from database -->
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <select class="form-select" name="problem" wire:model="problem" required>
                                    <option value="">Choose Problem</option>
                                    @foreach($all_problems as $problem)
                                        <option value="{{ $problem->id }}">{{ $problem->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="file-upload-wrapper">
                                    <input type="file" class="form-control" name="videos[]" id="videos" wire:model="videos" multiple accept="video/*">
                                    <label for="videos" class="file-upload-label">
                                        <i class="fas fa-file"></i> 
                                        @if(is_array($videos) && count($videos) > 0)
                                            Upload Videos ({{ count($videos) }} selected)
                                        @else
                                            Upload Videos
                                        @endif
                                    </label>
                                    @if(is_array($videos) && count($videos) > 0)
                                        <div class="file-preview mt-2">
                                            <small class="text-muted">Selected videos:</small>
                                            <div class="selected-files">
                                                @foreach($videos as $index => $video)
                                                    <span class="badge bg-primary me-1 mb-1">
                                                        <i class="fas fa-video"></i> Video {{ $index + 1 }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <div class="file-upload-wrapper">
                                    <input type="file" class="form-control" name="images[]" id="images" wire:model="images" multiple accept="image/*">
                                    <label for="images" class="file-upload-label">
                                        <i class="fas fa-file"></i> 
                                        @if(is_array($images) && count($images) > 0)
                                            Upload Images ({{ count($images) }} selected)
                                        @else
                                            Upload Images
                                        @endif
                                    </label>
                                    @if(is_array($images) && count($images) > 0)
                                        <div class="file-preview mt-2">
                                            <small class="text-muted">Selected images:</small>
                                            <div class="selected-files">
                                                @foreach($images as $index => $image)
                                                    <span class="badge bg-success me-1 mb-1">
                                                        <i class="fas fa-image"></i> Image {{ $index + 1 }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <script>
                            function filterModelsByBrand() {
                                var brandSelect = document.getElementById('brand-select');
                                var modelSelect = document.getElementById('model-select');
                                var selectedBrand = brandSelect.value;

                                // Hide all model options except the placeholder
                                for (var i = 0; i < modelSelect.options.length; i++) {
                                    var option = modelSelect.options[i];
                                    if (option.value === "") {
                                        option.style.display = "";
                                        option.selected = true;
                                    } else if (option.getAttribute('data-brand') === selectedBrand) {
                                        option.style.display = "";
                                    } else {
                                        option.style.display = "none";
                                    }
                                }
                                // Reset model select to placeholder
                                modelSelect.value = "";
                            }
                        </script>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <button type="button" class="description-btn w-100" data-bs-toggle="modal" data-bs-target="#descriptionModal">
                                    <i class="fas fa-edit"></i> Add Description
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <button type="submit" class="theme-btn theme-btn2 w-100">
                                    <i class="fas fa-tools"></i> Request Repair
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Description Modal Start -->
    <div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="descriptionModalLabel">
                        <i class="fas fa-edit"></i> Describe Your Problem
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="problemDescription" class="form-label">Please provide detailed information about the problem:</label>
                        <textarea class="form-control" id="problemDescription" name="description" wire:model="description" rows="6" 
                                    placeholder="Describe the issue you're experiencing with your device. Include when it started, what might have caused it, and any other relevant details..."></textarea>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            The more detailed your description, the better we can assist you with your repair needs.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveDescription()" data-bs-dismiss="modal">
                        <i class="fas fa-save"></i> Save Description
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Description Modal End -->

    <style>
        
        .file-upload-wrapper {
            position: relative;
        }
        
        .file-upload-wrapper input[type="file"] {
            opacity: 0;
            position: absolute;
            z-index: -1;
            width: 0.1px;
            height: 0.1px;
            overflow: hidden;
        }
        
        .file-upload-label {
            display: block;
            padding: 12px 20px;
            background: #fff;
            border: 2px dashed #00B6B1;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #00B6B1;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }
        
        .file-upload-label:hover {
            background: #f8f9fa;
            border-color: #03466E;
            color: #03466E;
        }
        
        .description-btn {
            display: block;
            padding: 12px 20px;
            background: #fff;
            border: 2px dashed #00B6B1 !important;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #00B6B1;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }
        
        .description-btn:hover {
            background: #f8f9fa;
            border-color: #03466E !important;
            color: #03466E;
        }
        
        .file-preview {
            margin-top: 0;
            font-size: 12px;
            color: #666;
            display: none;
        }
        
        .file-preview .file-count {
            background: rgba(0, 182, 177, 0.1);
            color: #00B6B1;
            padding: 6px 12px;
            border-radius: 6px;
            margin: 2px;
            display: inline-block;
            font-weight: 500;
            border: 1px solid rgba(0, 182, 177, 0.2);
        }
        
        .modal-content {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .modal-header {
            background: linear-gradient(135deg, #00B6B1 0%, #03466E 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        
        .modal-header .btn-close {
            filter: invert(1);
        }
        
        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 20px;
        }
        
        .modal-footer .btn {
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            min-width: 120px;
        }
        
        .modal-footer .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }
        
        .modal-footer .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        .modal-footer .btn-primary {
            background: linear-gradient(135deg, #00B6B1 0%, #03466E 100%);
            border: none;
            color: white;
        }
        
        .modal-footer .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, #03466E 0%, #00B6B1 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 182, 177, 0.3);
        }
        
        .modal-footer .btn-primary:disabled {
            opacity: 0.8;
            cursor: not-allowed;
            transform: none !important;
        }
        
        .fa-spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .theme-btn2.w-100 {
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
        }
        
        .description-btn.w-100 {
            padding: 13px 20px;
            font-size: 16px;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .appointment {
                padding: 30px 0;
            }
            
            .theme-btn2.w-100 {
                padding: 12px;
                font-size: 14px;
            }
            
            .description-btn.w-100 {
                padding: 12px 20px;
                font-size: 14px;
            }
        }
    </style>

    <script>
        function displayFileNames(inputId, previewId) {
            const input = document.getElementById(inputId);
            const label = document.querySelector(`label[for="${inputId}"]`);
            
            if (input.files.length > 0) {
                const fileCount = input.files.length;
                const fileText = fileCount === 1 ? 'file' : 'files';
                
                // Update the button label text
                label.innerHTML = `<i class="fas fa-file"></i> ${fileCount} ${fileText} selected`;
            } else {
                // Reset to original text when no files
                label.innerHTML = `<i class="fas fa-file"></i> Upload Files`;
            }
        }
        
        function saveDescription() {
            const description = document.getElementById('problemDescription').value;
            const saveButton = document.querySelector('#descriptionModal .btn-primary');
            const originalText = saveButton.innerHTML;
            
            if (description.trim()) {
                // Instantly update the main "Add Description" button
                const addDescButton = document.querySelector('[data-bs-target="#descriptionModal"]');
                addDescButton.innerHTML = '<i class="fas fa-check"></i> Description Added';
                addDescButton.style.background = '#28a745';
                addDescButton.style.borderColor = '#28a745';
                addDescButton.style.color = 'white';
                addDescButton.setAttribute('data-description-added', 'true');
                
                // Change save button to show saving state
                saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                saveButton.disabled = true;
                
                // Simulate save process
                setTimeout(() => {
                    saveButton.innerHTML = '<i class="fas fa-check"></i> Saved!';
                    saveButton.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                    
                    setTimeout(() => {
                        // Reset save button for next time
                        saveButton.innerHTML = originalText;
                        saveButton.disabled = false;
                        saveButton.style.background = '';
                    }, 1000);
                }, 1500);
            } else {
                // Show error state if no description
                saveButton.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Add Description First';
                saveButton.style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
                
                setTimeout(() => {
                    saveButton.innerHTML = originalText;
                    saveButton.style.background = '';
                }, 2000);
            }
        }
        
        // Auto-resize textarea and form submission handler
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('problemDescription');
            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            }
            
            // Handle form submission to reset description button
            const form = document.querySelector('.appointment-form form');
            if (form) {
                form.addEventListener('submit', function() {
                    const addDescButton = document.querySelector('[data-bs-target="#descriptionModal"]');
                    if (addDescButton && addDescButton.getAttribute('data-description-added') === 'true') {
                        addDescButton.innerHTML = '<i class="fas fa-edit"></i> Add Description';
                        addDescButton.style.background = '';
                        addDescButton.style.borderColor = '';
                        addDescButton.style.color = '';
                        addDescButton.removeAttribute('data-description-added');
                    }
                });
            }
        });
    </script>
</div>
