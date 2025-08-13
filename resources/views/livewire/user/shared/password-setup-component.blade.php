<div class="">
    @if($showPasswordSetup)
        <!-- Prevent body scrolling when modal is open -->
        <style>
            body { overflow: hidden !important; }
            .modal-open { overflow: hidden !important; }
            .obligatory-modal { user-select: none; }
            .modal-backdrop {
                background: rgba(0, 0, 0, 0.4) !important;
                backdrop-filter: blur(3px);
                -webkit-backdrop-filter: blur(3px);
            }
            .modal-backdrop::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.2);
                pointer-events: none;
            }
            
            
        </style>
        
        <!-- JavaScript to handle body scroll and prevent escape -->
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('body-scroll', (event) => {
                    if (event.action === 'disable') {
                        document.body.style.overflow = 'hidden';
                        document.body.classList.add('modal-open', 'obligatory-modal');
                        
                        // Prevent all keyboard shortcuts that might escape the modal
                        document.addEventListener('keydown', preventEscape, true);
                        document.addEventListener('keyup', preventEscape, true);
                        
                        // Prevent right-click context menu
                        document.addEventListener('contextmenu', preventEscape, true);
                        
                        // Prevent F5, F11, Ctrl+R, Ctrl+Shift+R, Ctrl+W, Alt+F4
                        document.addEventListener('keydown', (e) => {
                            if (e.key === 'F5' || e.key === 'F11' || 
                                (e.ctrlKey && e.key === 'r') || 
                                (e.ctrlKey && e.shiftKey && e.key === 'R') ||
                                (e.ctrlKey && e.key === 'w') ||
                                (e.altKey && e.key === 'F4')) {
                                e.preventDefault();
                                e.stopPropagation();
                                return false;
                            }
                        }, true);
                        
                        // Prevent navigation away from the page
                        window.addEventListener('beforeunload', preventNavigation);
                        window.addEventListener('unload', preventNavigation);
                        
                        // Prevent any form submissions outside the modal
                        document.addEventListener('submit', preventOutsideSubmit, true);
                        
                        // Prevent any link clicks outside the modal
                        document.addEventListener('click', preventOutsideClick, true);
                        
                    } else if (event.action === 'enable') {
                        document.body.style.overflow = '';
                        document.body.classList.remove('modal-open', 'obligatory-modal');
                        
                        // Remove all event listeners
                        document.removeEventListener('keydown', preventEscape, true);
                        document.removeEventListener('keyup', preventEscape, true);
                        document.removeEventListener('contextmenu', preventEscape, true);
                        window.removeEventListener('beforeunload', preventNavigation);
                        window.removeEventListener('unload', preventNavigation);
                        document.removeEventListener('submit', preventOutsideSubmit, true);
                        document.removeEventListener('click', preventOutsideClick, true);
                    }
                });
                
                function preventEscape(e) {
                    // Prevent Escape key, Tab key navigation outside modal, and other escape methods
                    if (e.key === 'Escape' || e.key === 'Tab') {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                }
                
                function preventNavigation(e) {
                    // Prevent any navigation away from the page
                    e.preventDefault();
                    e.returnValue = 'You must set a password before proceeding.';
                    return 'You must set a password before proceeding.';
                }
                
                function preventOutsideSubmit(e) {
                    // Only allow form submission within the password setup modal
                    if (!e.target.closest('.password-setup-modal')) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                }
                
                function preventOutsideClick(e) {
                    // Only allow clicks within the password setup modal
                    if (!e.target.closest('.password-setup-modal')) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                }
            });
        </script>
        
        <!-- Semi-transparent modal overlay that shows background slightly -->
        <div class="fixed inset-0 z-[9999] modal-backdrop flex items-center justify-center p-4 pointer-events-auto password-setup-modal">
            <!-- Modal container with enhanced visibility -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 ease-out scale-100 opacity-100 border-2 border-red-200 dark:border-red-600 relative z-10">
                <!-- Modal header with warning styling -->
                <div class="bg-red-50 dark:bg-red-900/20 border-b border-red-200 dark:border-red-700 px-6 py-4 rounded-t-lg">
                    <div class="text-center">
                        <x-icon name="o-exclamation-triangle" class="w-12 h-12 text-red-600 dark:text-red-400 mx-auto mb-3" />
                        <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-2">
                            ⚠️ {{ trans('auth.set_password_for_your_account') }}
                        </h3>
                        <p class="text-sm text-red-700 dark:text-red-300 mb-2">
                            {{ trans('auth.you_can_not_enter_the_system_before_set_password') }}
                        </p>
                        <div class="bg-red-100 dark:bg-red-800/30 border border-red-200 dark:border-red-600 rounded p-2">
                            <p class="text-xs text-red-800 dark:text-red-200 font-medium">
                                🔒 This action is obligatory and cannot be skipped
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Modal body with form -->
                <div class="px-6 py-6">
                    <form wire:submit="setupPassword" class="space-y-4">
                        <div class="space-y-4">
                            <div>
                                <x-admin.shared.form-input
                                    :label="trans('auth.password')"
                                    type="password"
                                    wire:model="password"
                                    placeholder="Enter your password"
                                    :required="true"
                                    :error="$errors->first('password')"
                                />
                            </div>

                            <div>
                                <x-admin.shared.form-input
                                    :label="trans('auth.password_confirmation')"
                                    type="password"
                                    wire:model="password_confirmation"
                                    placeholder="Confirm your password"
                                    :required="true"
                                    :error="$errors->first('password_confirmation')"
                                />
                            </div>
                        </div>

                        <div class="pt-4">
                            <x-button
                                :label="trans('auth.confirm')"
                                class="btn-primary w-full"
                                type="submit"
                                spinner="setupPassword"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>