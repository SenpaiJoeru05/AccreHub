<div x-data="{ 
    modalOpen: false,
    isDragging: false,
    files: [],
    resetForm() {
        this.files = [];
        if (this.$refs.fileInput) {
            this.$refs.fileInput.value = '';
            $wire.set('documentFile', null);
        }
        $wire.set('documentTitle', '');
        $wire.set('documentYear', '');
        $wire.set('documentDescription', '');
    }
}" 
x-show="modalOpen"
x-on:open-modal.window="if ($event.detail.modal === 'upload-document') { modalOpen = true; resetForm(); }"
x-on:close-modal.window="modalOpen = false"
x-on:document-uploaded.window="modalOpen = false"
class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center z-50 p-4">

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] ring-1 ring-gray-200 dark:ring-gray-700 flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Upload New Document</h3>
            <button x-on:click="modalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto">
            <form wire:submit.prevent="uploadDocument">
                <div class="w-full h-full flex flex-col md:flex-row items-stretch justify-center p-4 md:p-8 gap-8">
                    <!-- Left Panel - File Upload -->
                    <div class="md:w-1/2 flex items-center justify-center">
                        <div class="relative w-[340px] h-[320px] bg-white dark:bg-gray-900 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 flex items-center justify-center overflow-hidden"
                        style="height: 320px !important; width: 340px !important;">
                            
                           <!-- File Upload Placeholder (Visible when no file is selected and not uploading) -->
                            <div 
                                wire:loading.remove
                                wire:target="documentFile"
                                @if ($documentFile) style="display:none" @endif
                                class="flex flex-col items-center justify-center w-full h-full text-center transition-all"
                            >
                                <label class="cursor-pointer flex flex-col items-center justify-center w-full h-full">
                                    <span class="font-bold text-lg text-gray-900 dark:text-gray-100 mb-1">Click to upload</span>
                                    <input 
                                        type="file" 
                                        wire:model="documentFile" 
                                        x-ref="fileInput"
                                        class="sr-only"
                                        accept=".pdf,.docx,.pptx,.xlsx,.jpg,.jpeg,.png"
                                    >
                                    <span class="block text-gray-500 dark:text-gray-400 text-base mb-1">or drag and drop</span>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">PDF, DOCX, PPTX, XLSX, JPG/PNG (Max 10MB)</p>
                                </label>
                            </div>

                            <!-- Loading Indicator (Visible during file upload) -->
                            <div 
                                wire:loading
                                wire:target="documentFile"
                                class="absolute inset-0 z-20 bg-white/80 dark:bg-gray-900/80"
                            >
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="animate-spin h-8 w-8 text-primary-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                        <span class="text-primary-700 dark:text-primary-300 font-medium">Uploading...</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Uploaded File Display (Visible once file is uploaded) -->
                            @if ($documentFile && !session('livewire-uploading'))
                                <div class="absolute inset-0 flex flex-col items-center justify-center bg-white/90 dark:bg-gray-900/90 rounded-xl p-4 z-20 text-center">
                                    <svg class="w-10 h-10 text-green-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-gray-900 dark:text-gray-100 font-semibold text-base truncate max-w-[180px]">
                                        {{ is_string($documentFile) ? basename($documentFile) : ($documentFile->getClientOriginalName() ?? 'File attached') }}
                                    </span>
                                    <button type="button" wire:click="$set('documentFile', null)" class="mt-2 text-red-500 hover:text-red-700 rounded-full p-2 bg-white dark:bg-gray-800 shadow">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            @error('documentFile')
                                <p class="text-red-500 text-xs absolute bottom-2 left-2 right-2 text-center">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Panel - Form Inputs -->
                    <div class="md:w-1/2 flex flex-col justify-between min-h-[300px] space-y-6">
                        <!-- Title -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 dark:text-gray-100 mb-2">Document Title</label>
                            <input type="text" wire:model="documentTitle"
                                   class="w-full rounded-xl border border-primary-200 dark:border-primary-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400 px-5 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-base font-medium shadow-sm"
                                   placeholder="Enter document title">
                            @error('documentTitle')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Year -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 dark:text-gray-100 mb-2">Year</label>
                            <input type="number" wire:model="documentYear"
                                   class="w-full rounded-xl border border-primary-200 dark:border-primary-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400 px-5 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-base font-medium shadow-sm"
                                   placeholder="e.g. 2024">
                            @error('documentYear')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 dark:text-gray-100 mb-2">Description</label>
                            <textarea wire:model="documentDescription" rows="3"
                                      class="w-full rounded-xl border border-primary-200 dark:border-primary-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400 px-5 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-base font-medium shadow-sm"
                                      placeholder="Add a short description"></textarea>
                            @error('documentDescription')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-4 pt-4">
                            <button type="button" x-on:click="modalOpen = false"
                                    class="flex-1 px-5 py-3 text-base font-semibold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors shadow">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="flex-1 px-5 py-3 text-base font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-colors focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 shadow">
                                Upload
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
