{{-- filepath: resources/views/filament/resources/user-resource/preview-modal.blade.php --}}
<div class="p-8 rounded-2xl shadow-2xl bg-white dark:bg-gray-900 w-full max-w-2xl mx-auto">
    <div class="flex flex-col items-center mb-10">
        <div class="flex items-center justify-center h-20 w-20 rounded-full bg-primary-100 dark:bg-primary-900 mb-4">
            <x-heroicon-o-user class="h-12 w-12 text-primary-600 dark:text-primary-300" />
        </div>
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
        <span class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200 mt-2">
            {{ ucfirst($user->role) }}
        </span>
    </div>

    <div class="space-y-8">
        <div class="flex items-center gap-4">
            <x-heroicon-o-envelope class="h-7 w-7 text-primary-500 dark:text-primary-400" />
            <a href="mailto:{{ $user->email }}" class="text-lg text-gray-900 dark:text-gray-100 hover:underline">{{ $user->email }}</a>
        </div>

        <div class="flex items-center gap-4">
            <x-heroicon-o-map-pin class="h-7 w-7 text-primary-500 dark:text-primary-400" />
            <div class="flex flex-wrap gap-2">
                @forelse($user->areas as $area)
                    <span class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-base px-4 py-1 rounded">{{ $area->name }}</span>
                @empty
                    <span class="text-gray-400 dark:text-gray-500 text-base">None</span>
                @endforelse
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-heroicon-o-calendar class="h-7 w-7 text-primary-500 dark:text-primary-400" />
            <span class="text-base text-gray-500 dark:text-gray-400">Created:</span>
            <span class="text-base text-gray-900 dark:text-gray-100">{{ $user->created_at->format('M j, Y \a\t g:i A') }}</span>
        </div>

        <div class="flex items-center gap-4">
            <x-heroicon-o-clock class="h-7 w-7 text-primary-500 dark:text-primary-400" />
            <span class="text-base text-gray-500 dark:text-gray-400">Updated:</span>
            <span class="text-base text-gray-900 dark:text-gray-100">{{ $user->updated_at->format('M j, Y \a\t g:i A') }}</span>
        </div>
    </div>
</div>