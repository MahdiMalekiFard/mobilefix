<div class="">
    <div class="appointment">
        <div class="container-fluid">
            <div class="appointment-form">
                <!-- Success Modal will be shown when order is submitted -->

                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" x-data="{ countdown: 6, show: true }" x-show="show" x-init="
                        let timer = setInterval(() => {
                            countdown--;
                            if (countdown <= 0) {
                                show = false;
                                clearInterval(timer);
                            }
                        }, 1000);
                    ">
                        {{ session('error') }}
                        <div class="float-end">
                            <small class="text-muted">Automatisches Schließen in <span x-text="countdown"></span>s</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" x-data="{ countdown: 8, show: true }" x-show="show" x-init="
                        let timer = setInterval(() => {
                            countdown--;
                            if (countdown <= 0) {
                                show = false;
                                clearInterval(timer);
                            }
                        }, 1000);
                    ">
                        <strong>Bitte beheben Sie die folgenden Fehler:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <div class="float-end">
                            <small class="text-muted">Automatisches Schließen in <span x-text="countdown"></span>s</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form wire:submit.prevent="submit" enctype="multipart/form-data">
                    <div class="row" wire:loading.class="opacity-50" wire:target="submit">
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Namen eingeben" wire:model="name" required wire:loading.attr="disabled" wire:target="submit">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="E-Mail eingeben" wire:model="email" required wire:loading.attr="disabled" wire:target="submit">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Telefon eingeben" wire:model="phone" required wire:loading.attr="disabled" wire:target="submit">
                            </div>
                        </div>
                        <!-- Brand select from database -->
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <select class="form-select" name="brand" id="brand-select" wire:model="brand" required onchange="filterModelsByBrand()" wire:loading.attr="disabled" wire:target="submit">
                                    <option value="">Marke wählen</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Device/Model select, dynamically filtered by brand -->
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <select class="form-select" name="model" id="model-select" wire:model="model" required wire:loading.attr="disabled" wire:target="submit">
                                    <option value="">Gerät auswählen</option>
                                    @foreach($brands as $brand)
                                        @foreach($brand->devices as $device)
                                            <option value="{{ $device->id }}" data-brand="{{ $brand->id }}" style="display:none;">{{ $device->title }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Problems dropdown selection -->
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group"
                                 x-data="{ selectedProblems: @entangle('problems') }">
                                <div class="dropdown" style="position: relative;">
                                    <button class="form-select dropdown-toggle text-start"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside"
                                            aria-expanded="false"
                                            style="background: white; border-color: #ced4da;"
                                            wire:loading.attr="disabled"
                                            wire:target="submit">
                                        <span
                                            x-text="
                                            selectedProblems.length === 0
                                              ? 'Wählen Sie Probleme'
                                              : (selectedProblems.length === 1
                                                  ? '1 Problem ausgewählt'
                                                  : `${selectedProblems.length} Probleme ausgewählt`)
                                          ">
                                        </span>
                                    </button>

                                    <div class="dropdown-menu"
                                         style="width: 100%; max-height: 200px; overflow-y: auto; padding: 10px; z-index: 1050;">
                                        @foreach($all_problems as $problem)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       value="{{ $problem?->id }}"
                                                       id="problem_{{ $problem?->id }}"
                                                       x-model="selectedProblems">
                                                <label class="form-check-label" for="problem_{{ $problem->id }}">
                                                    {{ $problem?->title }} ({{ $problem?->price_range }})
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Videos -->
                        <div class="col-lg-3 col-md-6" wire:key="req-videos">
                            <div class="form-group">
                                <div class="file-upload-wrapper"
                                     x-data="{ count: 0 }"
                                     x-effect="count = Array.isArray($wire.videos) ? $wire.videos.length : 0">
                                    <input type="file"
                                           class="form-control"
                                           name="videos[]"
                                           id="videos"
                                           x-ref="videosInput"
                                           wire:model="videos"
                                           multiple
                                           accept="video/*"
                                           @change="count = $event.target.files?.length || 0"
                                           wire:loading.attr="disabled"
                                           wire:target="submit"/>

                                    <label for="videos" class="file-upload-label">
                                        <i class="fas fa-file"></i>
                                        <span x-text="count ? `Videos hochladen (${count} ausgewählt)` : 'Videos hochladen'">
                                          Videos hochladen
                                        </span>
                                    </label>

                                    <!-- Uploading state -->
                                    <div class="mt-1 small text-muted" wire:loading wire:target="videos">
                                        <i class="fas fa-spinner fa-spin me-1"></i> Videos werden hochgeladen …
                                    </div>

                                    <!-- Validation -->
                                    @error('videos.*')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                    <!-- Clear native input after Livewire resets -->
                                    <div x-effect="if (Array.isArray($wire.videos) && $wire.videos.length === 0) { $refs.videosInput.value = '' }"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="col-lg-3 col-md-6" wire:key="req-images">
                            <div class="form-group">
                                <div class="file-upload-wrapper"
                                     x-data="{ count: 0 }"
                                     x-effect="count = Array.isArray($wire.images) ? $wire.images.length : 0">
                                    <input type="file"
                                           class="form-control"
                                           name="images[]"
                                           id="images"
                                           x-ref="imagesInput"
                                           wire:model="images"
                                           multiple
                                           accept="image/*"
                                           @change="count = $event.target.files?.length || 0"
                                           wire:loading.attr="disabled"
                                           wire:target="submit"/>

                                    <label for="images" class="file-upload-label">
                                        <i class="fas fa-file"></i>
                                        <span x-text="count ? `Bilder hochladen (${count} ausgewählt)` : 'Bilder hochladen'">
                                          Bilder hochladen
                                        </span>
                                    </label>

                                    <!-- Uploading state -->
                                    <div class="mt-1 small text-muted" wire:loading wire:target="images">
                                        <i class="fas fa-spinner fa-spin me-1"></i> Bilder werden hochgeladen …
                                    </div>

                                    <!-- Validation -->
                                    @error('images.*')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                    <!-- Clear native input after Livewire resets -->
                                    <div x-effect="if (Array.isArray($wire.images) && $wire.images.length === 0) { $refs.imagesInput.value = '' }"></div>
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
                                    if (option.value === '') {
                                        option.style.display = '';
                                        option.selected = true;
                                    } else if (option.getAttribute('data-brand') === selectedBrand) {
                                        option.style.display = '';
                                    } else {
                                        option.style.display = 'none';
                                    }
                                }
                                // Reset model select to placeholder
                                modelSelect.value = '';
                            }
                        </script>
                    </div>

                    <div class="row mt-3"
                         x-data="{ uploading:false, desc:@entangle('description') }"
                         x-on:livewire-upload-start.window="uploading = true"
                         x-on:livewire-upload-finish.window="uploading = false"
                         x-on:livewire-upload-error.window="uploading = false">

                        <div class="col-lg-6">
                            <div class="form-group">
                                <button type="button"
                                        :class="{ 'opacity-50 cursor-not-allowed': uploading }"
                                        class="description-btn w-100"
                                        data-bs-toggle="modal"
                                        data-bs-target="#descriptionModal"
                                        :disabled="uploading"
                                        :title="uploading ? 'Bitte warten – Upload läuft' : ''"
                                        :style="(desc && desc.trim().length)
                                                    ? 'background:#28a745;border-color:#28a745;color:#fff'
                                                    : ''"
                                        wire:loading.attr="disabled"
                                        wire:target="submit">
                                    <template x-if="uploading">
                                        <span><i class="fas fa-spinner fa-spin"></i> Upload läuft …</span>
                                    </template>
                                    <template x-if="!uploading">
                                          <span>
                                                <span wire:loading.remove wire:target="submit">
                                                    <i :class="(desc && desc.trim().length) ? 'fas fa-check' : 'fas fa-edit'"></i>
                                                    <span x-text="(desc && desc.trim().length)
                                                                    ? 'Beschreibung hinzugefügt'
                                                                    : 'Beschreibung hinzufügen'">
                                                    </span>
                                                </span>
                                                <span wire:loading wire:target="submit">
                                                    <i class="fas fa-spinner fa-spin"></i> Wird übermittelt …
                                                </span>
                                          </span>
                                    </template>
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <button type="submit"
                                        class="theme-btn theme-btn2 w-100"
                                        :disabled="uploading"
                                        :class="{ 'opacity-50 cursor-not-allowed': uploading }"
                                        wire:loading.attr="disabled"
                                        wire:target="submit"
                                >
                                    <template x-if="uploading">
                                        <span><i class="fas fa-spinner fa-spin me-1"></i> Upload läuft …</span>
                                    </template>
                                    <template x-if="!uploading">
                                        <span>
                                            <span wire:loading.remove wire:target="submit">
                                                <i class="fas fa-tools"></i> Reparatur anfordern
                                            </span>
                                            <span wire:loading wire:target="submit">
                                                <i class="fas fa-spinner fa-spin me-1"></i> Wird übermittelt …
                                            </span>
                                        </span>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Description Modal Start -->
    <div class="modal fade" id="descriptionModal" tabindex="-1"
         aria-labelledby="descriptionModalLabel" aria-hidden="true"
         wire:ignore.self
         x-data="{
        desc: @entangle('description'),
        draft: '',
        init() {
              const el = document.getElementById('descriptionModal');
              // When opening: copy committed value into draft
              el.addEventListener('show.bs.modal', () => { this.draft = this.desc || '' });
              // When fully hidden: clear draft (optional)
              el.addEventListener('hidden.bs.modal', () => { this.draft = '' });
            }
        }"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="descriptionModalLabel">
                        <i class="fas fa-edit"></i> Beschreiben Sie Ihr Problem
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="problemDescription" class="form-label">
                            Bitte geben Sie detaillierte Informationen zum Problem an:
                        </label>
                        <textarea class="form-control" id="problemDescription" rows="6"
                                  x-model="draft"
                                  placeholder="Beschreiben Sie das Problem …"></textarea>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Je detaillierter Ihre Beschreibung …
                        </small>
                    </div>
                </div>

                <div class="modal-footer">
                    <!-- Cancel = discard current draft AND clear committed value -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            @click="desc = ''">
                        <i class="fas fa-times"></i> Stornieren
                    </button>

                    <!-- Save = commit draft -->
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                            @click="desc = draft">
                        <i class="fas fa-save"></i> Beschreibung speichern
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Description Modal End -->

    <!-- Success Modal Start -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title" id="successModalLabel">
                        <i class="fas fa-check-circle me-2"></i> Bestellung erfolgreich übermittelt!
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="enableForm()"></button>
                </div>
                <div class="modal-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-mobile-alt text-primary" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-success mb-4">Vielen Dank, dass Sie sich für unseren Reparaturservice entschieden haben!</h4>
                    <p class="lead mb-4">Ihre Reparaturanfrage wurde erfolgreich übermittelt. Bitte speichern Sie Ihren Tracking-Code für zukünftige Referenzzwecke.</p>

                    <div class="tracking-code-container mb-4">
                        <div class="card bg-light border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-barcode me-2"></i>Ihr Tracking-Code</h6>
                            </div>
                            <div class="card-body">
                                <h3 class="text-primary mb-2 fw-bold" id="trackingCode" style="font-family: 'Courier New', monospace; letter-spacing: 2px; user-select: all; cursor: text;">
                                    {{ $trackingCode ?? '' }}
                                </h3>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-outline-primary btn-sm" onclick="copyTrackingCode(event)" title="In die Zwischenablage kopieren">
                                        <i class="fas fa-copy me-1"></i> Code kopieren
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="selectTrackingCode(event)" title="Text zum manuellen Kopieren auswählen">
                                        <i class="fas fa-mouse-pointer me-1"></i> Wählen
                                    </button>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Sie können den obigen Code auch manuell auswählen und kopieren
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Wichtig:</strong> Bitte bewahren Sie diesen Trackingcode sicher auf. Sie benötigen ihn zur Überprüfung Ihres Reparaturstatus und zur Abholung.
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <p class="text-muted mb-3">Wir werden Sie innerhalb von 24 Stunden kontaktieren, um Ihre Reparaturdetails zu bestätigen.</p>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-primary btn-lg px-4" data-bs-dismiss="modal" onclick="enableForm()">
                        <i class="fas fa-check me-2"></i> Ich habe meinen Tracking-Code gespeichert
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Success Modal End -->

    <!-- Overlay for disabled form -->
    <div id="formOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.5); z-index: 1040;">
        <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center text-white">
                <i class="fas fa-lock fa-3x mb-3"></i>
                <h4>Bitte schließen Sie das Erfolgsmodal, um fortzufahren</h4>
            </div>
        </div>
    </div>

    <style>
        /* Success Modal Styles */
        .tracking-code-container .card {
            border-width: 2px !important;
        }

        .tracking-code-container h3 {
            font-size: 2.5rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        #formOverlay {
            backdrop-filter: blur(3px);
        }

        /* Form disabled state */
        .appointment-form.disabled {
            pointer-events: none;
            opacity: 0.6;
        }

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
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
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

        /* Problems dropdown: selected items text only */
        .dropdown-menu .form-check-input:checked + .form-check-label {
            color: #00B6B1;
        }

        /* Problems dropdown: align checkbox with label */
        .dropdown-menu .form-check {
            display: flex;
            align-items: center;
            padding-left: 0;
        }

        .dropdown-menu .form-check-input {
            float: none;
            margin: 0 8px 0 0;
            vertical-align: middle;
        }

        .dropdown-menu .form-check-label {
            margin: 0;
            vertical-align: middle;
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
                addDescButton.innerHTML = '<i class="fas fa-check"></i> Beschreibung hinzugefügt';
                addDescButton.style.background = '#28a745';
                addDescButton.style.borderColor = '#28a745';
                addDescButton.style.color = 'white';
                addDescButton.setAttribute('data-description-added', 'true');

                // Change save button to show saving state
                saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Speichern...';
                saveButton.disabled = true;

                // Simulate save process
                setTimeout(() => {
                    saveButton.innerHTML = '<i class="fas fa-check"></i> Gespeichert!';
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
                saveButton.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Fügen Sie zuerst eine Beschreibung hinzu';
                saveButton.style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';

                setTimeout(() => {
                    saveButton.innerHTML = originalText;
                    saveButton.style.background = '';
                }, 2000);
            }
        }

        // Auto-resize textarea and form submission handler
        document.addEventListener('DOMContentLoaded', function () {
            const textarea = document.getElementById('problemDescription');
            if (textarea) {
                textarea.addEventListener('input', function () {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            }

            // Handle form submission to reset description button
            const form = document.querySelector('.appointment-form form');
            if (form) {
                form.addEventListener('submit', function () {
                    const addDescButton = document.querySelector('[data-bs-target="#descriptionModal"]');
                    if (addDescButton && addDescButton.getAttribute('data-description-added') === 'true') {
                        addDescButton.innerHTML = '<i class="fas fa-edit"></i> Beschreibung hinzufügen';
                        addDescButton.style.background = '';
                        addDescButton.style.borderColor = '';
                        addDescButton.style.color = '';
                        addDescButton.removeAttribute('data-description-added');
                    }
                });
            }
        });

        // Success modal functions
        function showSuccessModal(trackingCode) {
            // Update tracking code in modal
            document.getElementById('trackingCode').textContent = trackingCode;

            // Disable form
            disableForm();

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('successModal'));
            modal.show();
        }

        function copyTrackingCode(event) {
            const trackingCode = document.getElementById('trackingCode').textContent;
            const button = event.target.closest('button');
            const originalText = button.innerHTML;

            // Function to show success feedback
            function showSuccess() {
                button.innerHTML = '<i class="fas fa-check me-1"></i> Kopiert!';
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-success');

                setTimeout(function () {
                    button.innerHTML = originalText;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-outline-primary');
                }, 2000);
            }

            // Try modern clipboard API first
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(trackingCode).then(function () {
                    showSuccess();
                }).catch(function (err) {
                    console.log('Clipboard API failed, trying fallback method:', err);
                    fallbackCopyTextToClipboard(trackingCode, showSuccess);
                });
            } else {
                // Fallback for older browsers or non-secure contexts
                fallbackCopyTextToClipboard(trackingCode, showSuccess);
            }
        }

        function fallbackCopyTextToClipboard(text, successCallback) {
            const textArea = document.createElement('textarea');
            textArea.value = text;

            // Avoid scrolling to bottom
            textArea.style.top = '0';
            textArea.style.left = '0';
            textArea.style.position = 'fixed';
            textArea.style.opacity = '0';

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    successCallback();
                } else {
                    throw new Error('Der Kopierbefehl war nicht erfolgreich');
                }
            } catch (err) {
                console.error('Fallback: Could not copy text: ', err);
                // Show manual copy dialog
                prompt('Kopieren fehlgeschlagen. Bitte kopieren Sie diesen Tracking-Code manuell:', text);
            } finally {
                document.body.removeChild(textArea);
            }
        }

        function selectTrackingCode(event) {
            const trackingElement = document.getElementById('trackingCode');

            // Create a range object
            const range = document.createRange();
            range.selectNodeContents(trackingElement);

            // Clear any existing selections
            const selection = window.getSelection();
            selection.removeAllRanges();

            // Add the new range to selection
            selection.addRange(range);

            // Optional: Show feedback
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check me-1"></i> Ausgewählt!';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-success');

            setTimeout(function () {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 1500);
        }

        function disableForm() {
            // Show overlay
            document.getElementById('formOverlay').classList.remove('d-none');

            // Add disabled class to form container
            const appointmentForm = document.querySelector('.appointment-form');
            if (appointmentForm) {
                appointmentForm.classList.add('disabled');
            }

            // Disable all form elements
            const form = document.querySelector('.appointment-form form');
            if (form) {
                const inputs = form.querySelectorAll('input, select, textarea, button');
                inputs.forEach(function (input) {
                    input.disabled = true;
                });
            }
        }

        function enableForm() {
            // Hide overlay
            document.getElementById('formOverlay').classList.add('d-none');

            // Remove disabled class from form container
            const appointmentForm = document.querySelector('.appointment-form');
            if (appointmentForm) {
                appointmentForm.classList.remove('disabled');
            }

            // Enable all form elements
            const form = document.querySelector('.appointment-form form');
            if (form) {
                const inputs = form.querySelectorAll('input, select, textarea, button');
                inputs.forEach(function (input) {
                    input.disabled = false;
                });
            }

            @this.
            call('modalClosed');
        }

        // Listen for Livewire events to show success modal
        window.addEventListener('show-success-modal', event => {
            showSuccessModal(event.detail.trackingCode);
        });
    </script>
</div>
