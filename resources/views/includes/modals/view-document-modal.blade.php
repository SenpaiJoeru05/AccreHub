<div x-data="{ open: false, document: {} }"
     x-on:open-modal.window="if ($event.detail.modal === 'view-document') { open = true; document = $event.detail.document }"
     x-on:close-modal.window="open = false"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50"
     role="dialog"
     aria-modal="true">
    
    <!-- Overlay -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-gray-900/75">
    </div>

    <!-- Modal -->
    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative min-h-screen flex items-center justify-center p-4">
        
        <div class="relative w-full max-w-2xl bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="flex items-start justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    <span x-text="document.title"></span>
                </h3>
                <div class="flex items-center gap-2">
                    <!-- Edit Button -->
                    <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            x-on:click="$dispatch('open-modal', { 
                                modal: 'edit-document',
                                document: document
                            }); open = false">
                        <x-heroicon-o-pencil class="w-5 h-5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" />
                    </button>
                    <!-- Close Button -->
                    <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            x-on:click="open = false">
                        <x-heroicon-o-x-mark class="w-5 h-5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" />
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="p-4 space-y-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Year</p>
                    <p class="text-base text-gray-900 dark:text-gray-100" x-text="document.year"></p>
                </div>
                
                <template x-if="document.description">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Description</p>
                        <p class="text-base text-gray-900 dark:text-gray-100" x-text="document.description"></p>
                    </div>
                </template>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                <button x-on:click="open = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Close
                </button>
                <a :href="document.path"
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4 mr-2" />
                    Download
                </a>
            </div>
        </div>
    </div>
</div>