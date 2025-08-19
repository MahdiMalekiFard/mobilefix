@php
    use App\Enums\BooleanEnum;
    use App\Enums\PageTypeEnum;
    use App\Helpers\Constants;
@endphp
<div class="">
    <form wire:submit="submit" enctype="multipart/form-data">
        <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div class="col-span-2 grid grid-cols-1 gap-4">
                <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                    <div class="grid grid-cols-1 gap-4"
                         x-data="pageEditor({
                            type: @entangle('type').live,
                            body: @entangle('body').defer,

                            // pass the initial server values explicitly (prevents empty init)
                            initialBody: @js($body ?? ''),
                            aboutValue: @js(\App\Enums\PageTypeEnum::ABOUT_US->value),

                            config: {
                                plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount autoresize',
                                menubar: 'file edit view insert format tools table help',
                                toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code fullscreen',
                                base_url: '/assets/js/tinymce',
                                suffix: '.min',
                                statusbar: true,
                                license_key: 'gpl',
                                branding: false,
                            }
                        })"
                         x-init="init()"
                    >
                        <x-input :label="trans('validation.attributes.title')"
                                 wire:model.blur="title" required
                        />
                        <x-select
                            :label="trans('validation.attributes.type')"
                            wire:model="type"
                            :options="PageTypeEnum::formatedCases()"
                            option-value="value"
                            option-label="label"
                            required
                        />

                        {{-- About Us -> textarea (render only when needed) --}}
                        <template x-if="isAbout">
                            <div>
                                <x-textarea
                                    :label="trans('validation.attributes.body')"
                                    x-model="body"
                                    @input.debounce.300ms="$wire.set('body', body)"
                                    required
                                />
                            </div>
                        </template>

                        {{-- Other types -> TinyMCE (render only when needed) --}}
                        <template x-if="!isAbout">
                            <div wire:ignore>
                                <textarea x-ref="tinyHost" id="tiny-body"></textarea>
                            </div>
                        </template>

                    </div>
                </x-card>

                <x-card :title="trans('page.media')" shadow separator progress-indicator="submit">
                    <x-admin.shared.file-input :label="trans('order.images')"
                                               wire:model="images"
                                               multiple
                                               accept="image/*"
                    />

                    <!-- Existing Images -->
                    @if(count($existingImages) > 0)
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Existing Images</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($existingImages as $image)
                                    <div class="relative group">
                                        <img src="{{ $image['url'] }}"
                                             alt="{{ $image['name'] }}"
                                             class="w-full h-32 object-cover rounded border cursor-pointer hover:opacity-80 transition-opacity"
                                             onclick="openImageModal('{{ $image['url'] }}', '{{ $image['name'] }}')">
                                        <button type="button"
                                                wire:click="deleteImage({{ $image['id'] }})"
                                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                                                title="Delete image">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b">
                                            <span class="truncate block">{{ $image['name'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- New Images Preview -->
                    @if($images)
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">New Images</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($images as $index => $image)
                                    <div class="relative group">
                                        <img src="{{ $image->temporaryUrl() }}"
                                             alt="Preview"
                                             class="w-full h-32 object-cover rounded border cursor-pointer hover:opacity-80 transition-opacity"
                                             onclick="openImageModal('{{ $image->temporaryUrl() }}', '{{ $image->getClientOriginalName() }}')">
                                        <button type="button"
                                                wire:click="removeNewImage({{ $index }})"
                                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                                                title="Remove image">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b">
                                            <span class="truncate block">{{ $image->getClientOriginalName() }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </x-card>
            </div>

            <div class="col-span-1">
                <div class="sticky top-20">
                    <x-card :title="trans('general.page_sections.seo_options')" shadow separator progress-indicator="submit">
                        <x-admin.shared.seo-options class="lg:grid-cols-1"/>
                    </x-card>
                </div>
            </div>
        </div>

        <x-admin.shared.form-actions/>
    </form>

    <!-- Image Modal (Outside the form to prevent form submission) -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
        <div class="relative max-w-5xl max-h-full w-full h-full flex items-center justify-center">
            <button type="button" onclick="closeImageModal(event)" class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
            <div class="relative w-full h-full flex items-center justify-center">
                <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded shadow-2xl">
                <div class="absolute bottom-4 left-4 text-white text-sm bg-black bg-opacity-70 px-3 py-2 rounded-lg backdrop-blur-sm">
                    <span id="modalImageName" class="font-medium"></span>
                </div>
            </div>
            <div class="absolute bottom-4 right-4 text-white text-xs bg-black bg-opacity-50 px-2 py-1 rounded">
                Press ESC to close
            </div>
        </div>
    </div>

    <script>
        function pageEditor({ type, body, initialBody, aboutValue, config }) {
            return {
                type, body, initialBody, aboutValue, config, editor: null,
                get isAbout() { return String(this.type) === String(this.aboutValue); },

                init() {
                    // If Alpine's 'body' is empty at first paint, seed it from the server
                    if (this.body == null || this.body === '') {
                        this.body = this.initialBody || '';
                    }

                    this.$nextTick(() => this._toggle());
                    this.$watch('type', () => this._toggle());

                    // If body shows up later (update mode), push it into the editor
                    this.$watch('body', (val) => this._loadIntoEditor(val));

                    // After any Livewire DOM morph, try again (covers hydration races)
                    if (window.Livewire?.hook) {
                        Livewire.hook('morph.updated', () => this._loadIntoEditor(this.body));
                        Livewire.hook('message.processed', () => this._loadIntoEditor(this.body));
                    }

                    // On SPA navigations
                    document.addEventListener('livewire:navigated', () => {
                        this._loadIntoEditor(this.body);
                        this._toggle();
                    });
                },

                _toggle() {
                    if (this.isAbout) {
                        // Leaving rich editor → show textarea with PLAIN TEXT
                        this._destroyTiny(true);                 // HTML -> text
                    } else {
                        // leaving textarea → ensure minimal HTML, then init AFTER it's visible
                        if (this.body && this.isPlainText(this.body)) {
                            this.body = this.textToHtml(this.body);
                            this.$wire.set('body', this.body);
                        }
                        this.$nextTick(() => this._initTiny());
                    }
                },

                _initTiny() {
                    if (this.editor || !window.tinymce || !this.$refs.tinyHost) return;

                    tinymce.init({
                        selector: '#tiny-body', // stable id
                        ...this.config,
                        setup: (ed) => {
                            this.editor = ed;

                            ed.on('init', () => {
                                // set whatever we currently have; may be empty first
                                this._loadIntoEditor(this.body);
                                // try again on next tick (in case hydration lands right after)
                                setTimeout(() => this._loadIntoEditor(this.body), 0);
                            });

                            // Throttle pushes to Livewire to prevent rerender churn
                            let pushTimer = null;
                            ed.on('change keyup input undo redo', () => {
                                const html = ed.getContent();
                                this.body = html;

                                clearTimeout(pushTimer);
                                pushTimer = setTimeout(() => {
                                    this.$wire.set('body', html); // will trigger updatedBody()
                                }, 200);
                            });
                        },
                    });
                },

                // convertOnExit: when true, convert HTML -> plain text (for About Us textarea)
                _destroyTiny(convertOnExit = false) {
                    // If editor is not mounted, still ensure body consistency
                    if (!this.editor) {
                        if (convertOnExit && this.body) this.body = this.htmlToText(this.body);
                        return;
                    }

                    // capture last content
                    const html = this.editor.getContent();
                    const value = convertOnExit ? this.htmlToText(html) : html;

                    this.body = value;
                    this.$wire.set('body', value);

                    // destroy via TinyMCE API (safer if selector-based)
                    const inst = tinymce.get(this.editor.id);
                    if (inst) inst.remove();
                    this.editor = null;

                    if (this.$refs.tinyHost) this.$refs.tinyHost.value = '';
                },

                // ---- helpers ----
                _loadIntoEditor(val) {
                    if (!this.editor || !window.tinymce) return;
                    const ed = tinymce.get(this.editor.id);
                    if (!ed) return;
                    const target = val || '';
                    if (ed.getContent() !== target) ed.setContent(target);
                },

                isPlainText(val) { return val && stripTags(val) === val; },

                htmlToText(html) {
                    // Normalize block endings to line breaks, then strip tags and decode entities.
                    // 1) convert common block closers to \n to preserve paragraphs/lists a bit
                    const withBreaks = html
                        .replace(/<\/(p|div|li|h[1-6])>/gi, '\n')
                        .replace(/<br\s*\/?>/gi, '\n');

                    // 2) strip tags using a temporary element (also decodes entities)
                    const tmp = document.createElement('div');
                    tmp.innerHTML = withBreaks;
                    let text = tmp.textContent || tmp.innerText || '';

                    // 3) collapse whitespace and trim
                    text = text.replace(/\r?\n\s*\n+/g, '\n\n'); // compact multiple blank lines
                    text = text.replace(/[ \t]+\n/g, '\n');      // trim line-end spaces
                    text = text.replace(/[ \t]{2,}/g, ' ');      // collapse runs of spaces
                    return text.trim();
                },

                textToHtml(text) {
                    if (!text) return '';
                    // Escape then convert newlines to <br> (TinyMCE will normalize)
                    const esc = text
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;');
                    return esc.replace(/\r?\n/g, '<br>');
                },
            }
        }

        // small global helper for isPlainText
        function stripTags(s){const t=document.createElement('div');t.innerHTML=s;return t.textContent||t.innerText||'';}

        function openImageModal(imageUrl, imageName) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('modalImageName').textContent = imageName;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal(event) {
            // Prevent any form submission
            if (event) {
                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();
            }
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close image modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal(e);
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal(e);
            }
        });

        // Prevent form submission when clicking modal elements
        document.getElementById('imageModal').addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Additional safety: prevent any form submission from modals
        document.getElementById('imageModal').addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        });
    </script>
</div>
