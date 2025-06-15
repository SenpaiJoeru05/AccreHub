<div class="card bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-600 dark:border-gray-700 flex flex-col overflow-hidden"
     x-data>
    <!-- Preview Section -->
    <div class="relative h-32 bg-gray-50 dark:bg-gray-900 overflow-hidden">
        @php
            $extension = strtolower(pathinfo($document->path, PATHINFO_EXTENSION));
            $isPdf = $extension === 'pdf';
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            $filePath = Storage::url($document->path);
        @endphp

        @if ($isPdf)
            <div class="relative w-full h-full flex items-start justify-center">
                <canvas id="pdf-preview-{{ $document->id }}" 
                        class="w-full h-auto object-contain rounded-t-xl" 
                        data-file-path="{{ $filePath }}">
                </canvas>
                <div id="loading-{{ $document->id }}" 
                     class="absolute inset-0 flex items-center justify-center bg-gray-200 dark:bg-gray-700 bg-opacity-75 rounded-t-xl">
                    <svg class="animate-spin h-6 w-6 text-primary-500" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
                    </svg>
                </div>
            </div>
        @elseif ($isImage)
            <img src="{{ $filePath }}" 
                 alt="{{ $document->title }}" 
                 class="w-full h-full object-cover rounded-t-xl">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-t-xl">
                <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">No Preview</span>
            </div>
        @endif
    </div>

    <!-- Content Section -->
    <div class="p-4 flex flex-col flex-grow">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate" 
            title="{{ $document->title }}">
            {{ $document->title }}
        </h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Year: {{ $document->year }}</p>
        @if ($document->description)
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate" 
               title="{{ $document->description }}">
                {{ Str::limit($document->description, 50) }}
            </p>
        @endif
    </div>

    <!-- Actions Section -->
    <div class="p-4 pt-3 pb-4 flex justify-between items-center border-t border-gray-400 dark:border-gray-700 gap-x-3">
        <a href="{{ $filePath }}"
           target="_blank"
           class="inline-flex items-center px-4 py-2 text-sm font-semibold text-primary-600 dark:text-primary-400 bg-transparent border border-primary-600 dark:border-primary-400 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 shadow-sm hover:shadow-md transition-all duration-200">
            <x-heroicon-o-arrow-down-tray class="w-4 h-4 mr-4" />
            Download
        </a>
        <button class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500"
                x-on:click="$dispatch('open-modal', { 
                    modal: 'view-document', 
                    document: {
                        id: {{ $document->id }},
                        title: @js($document->title),
                        year: @js($document->year),
                        description: @js($document->description),
                        path: @js($filePath)
                    }
                })">
            <x-heroicon-o-eye class="w-4 h-4 mr-2" />
            View
        </button>
    </div>
</div>