<label
    x-data="mxThemeToggle({
    light:      @js($light),
    dark:       @js($dark),
    lightClass: @js($lightClass),
    darkClass:  @js($darkClass),
    reloadMode: @js($mode),        // 'tinymce' | 'always' | 'never'
    reloadSel:  @js($selector),     // extra selector for detection
  })"
    x-init="init()"
    @click.prevent="toggle()"
    class="swap swap-rotate cursor-pointer"
    wire:ignore
>
    <input type="checkbox" class="sr-only peer" :checked="theme === dark" />

    <x-mary-icon x-ref="sun" name="o-sun" class="swap-on" />
    <x-mary-icon x-ref="moon" name="o-moon" class="swap-off"  />
</label>

@once
    <script>
        // Apply stored theme ASAP on first paint (prevents flash)
        (function () {
            try {
                const t = localStorage.getItem('mx-theme');
                const c = localStorage.getItem('mx-class');
                if (t) document.documentElement.setAttribute('data-theme', t);
                if (c) {
                    document.documentElement.classList.remove('light','dark');
                    document.documentElement.classList.add(c);
                }
            } catch (_) {}
        })();
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            window.mxThemeToggle = (opts) => ({
                // config
                light: opts.light, dark: opts.dark,
                lightClass: opts.lightClass, darkClass: opts.darkClass,
                reloadMode: opts.reloadMode || 'tinymce',
                reloadSel:  opts.reloadSel  || '.tox-tinymce',

                // state
                theme: 'light',
                klass: 'light',
                initialized: false,

                init() {
                    // load from storage or system preference
                    try {
                        const t = localStorage.getItem('mx-theme');
                        const c = localStorage.getItem('mx-class');
                        if (t && c) {
                            this.theme = t; this.klass = c;
                        } else {
                            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                            this.theme = prefersDark ? this.dark : this.light;
                            this.klass = prefersDark ? this.darkClass : this.lightClass;
                        }
                    } catch (_) {}

                    this.apply(false); // don't reload on init
                    this.initialized = true;
                },

                apply(emit = true) {
                    // set <html> attributes
                    document.documentElement.setAttribute('data-theme', this.theme);
                    document.documentElement.classList.remove(this.lightClass, this.darkClass);
                    document.documentElement.classList.add(this.klass);

                    // persist
                    try {
                        localStorage.setItem('mx-theme', this.theme);
                        localStorage.setItem('mx-class', this.klass);
                    } catch (_) {}

                    // custom event for anything else in your app
                    if (emit) {
                        document.dispatchEvent(new CustomEvent('mx:theme-changed', {
                            bubbles: true,
                            detail: { theme: this.theme, class: this.klass }
                        }));
                    }
                },

                toggle() {
                    if (!this.initialized) return;
                    this.theme = (this.theme === this.light) ? this.dark : this.light;
                    this.klass = (this.theme === this.light) ? this.lightClass : this.darkClass;
                    this.apply(true);
                    this.maybeReload();
                },

                // Reload only when page has TinyMCE (DOM detection; no tinymce.editors)
                pageHasTinyMCE() {
                    return !!(
                        document.querySelector(this.reloadSel) ||
                        document.querySelector('.tox-tinymce, .tox-edit-area__iframe') ||
                        document.querySelector('textarea.tox-target, textarea[data-editor="tinymce"], textarea.tinymce, textarea[id*="tiny"], textarea[name="body"]')
                    );
                },

                maybeReload() {
                    if (this.reloadMode === 'never')  return;
                    if (this.reloadMode === 'always') { window.location.reload(); return; }
                    if (this.reloadMode === 'tinymce' && this.pageHasTinyMCE()) {
                        window.location.reload();
                    }
                },
            });
        });
    </script>
@endonce
