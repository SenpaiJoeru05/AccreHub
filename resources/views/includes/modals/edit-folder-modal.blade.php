<div x-data="{ modalOpen: false, folder: {} }"
     x-show="modalOpen"
     x-on:open-modal.window="if ($event.detail.modal === 'edit-folder') { modalOpen = true; folder = $event.detail.folder; $wire.startEditing(folder.id, folder.type) }"
     x-on:close-modal.window="modalOpen = false"
     x-on:folder-updated.window="modalOpen = false"
     class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center z-50 p-4">
    
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl w-full max-w-3xl max-h-[80vh] p-6 md:p-8 ring-1 ring-gray-200 dark:ring-gray-700 overflow-auto"
         @click.away="modalOpen = false">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        Edit <span x-text="folder.type ? folder.type.charAt(0).toUpperCase() + folder.type.slice(1) : 'Folder'"></span>
                    </h3>
                </div>
                <button type="button" x-on:click="modalOpen = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="updateFolder">
            <div class="space-y-5">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Name</label>
                    <input type="text" 
                           wire:model="editFolderName"
                           class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                    @error('editFolderName') 
                        <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>


                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Description (Optional)</label>
                    <textarea wire:model="editFolderDescription"
                              rows="3"
                              class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"></textarea>
                    @error('editFolderDescription') 
                        <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>
            </div>

            <!-- Modal footer -->
            <div class="mt-6 flex justify-end gap-3">
            <button type="button"
                    wire:click="confirmDeleteFolder"
                    class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                <x-heroicon-o-trash class="w-5 h-5 mr-2" />
                Delete
            </button>
            <button type="button" 
                    x-on:click="modalOpen = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                Cancel
            </button>
            <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                Update
            </button>
        </div>
        </form>
    </div>
</div>