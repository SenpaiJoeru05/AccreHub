<div class="relative">
    <!-- Loading Spinner -->
    <div wire:loading class="fixed top-0 left-0 w-full h-1 bg-primary-500 animate-pulse z-50"></div>

    <!-- Breadcrumbs -->
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2 md:space-x-4 bg-white dark:bg-gray-900 px-4 py-2 rounded-lg border border-gray-600 dark:border-gray-700">
            @foreach ($breadcrumbs as $index => $crumb)
                <li class="inline-flex items-center">
                    @if ($crumb['id'])
                        @if ($loop->first)
                            <a href="#"
                               wire:click.prevent="
                                   $set('parameter_id', null);
                                   $set('section_id', null);
                                   $set('subfolder_ids', []);
                               "
                               class="inline-flex items-center text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors">
                                <x-heroicon-o-home class="w-4 h-4 mr-1.5" />
                                {{ $crumb['name'] }}
                            </a>
                        @else
                            <a href="#"
                               wire:click.prevent="$set('{{ $crumb['type'] === 'parameter' ? 'parameter_id' : ($crumb['type'] === 'section' ? 'section_id' : 'subfolder_ids.' . ($index - 2)) }}', {{ $crumb['id'] }})"
                               class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors">
                                {{ $crumb['name'] }}
                            </a>
                        @endif
                    @else
                        <span class="text-sm font-semibold text-primary-800 dark:text-primary-200">{{ $crumb['name'] }}</span>
                    @endif
                    @if (!$loop->last)
                        <x-heroicon-o-chevron-right class="w-4 h-4 mx-1.5 text-gray-400" />
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>

    @if($parameter_id)
        <!-- Parameters/Sections View -->
        <div class="space-y-6">
            <!-- Folders Section -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ $section_id ? 'Subfolders' : 'Sections' }}
                    </h2>
                    <button
                        x-data
                        x-on:click="$dispatch('open-modal', { modal: 'create-folder' })"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 dark:bg-primary-700 dark:hover:bg-primary-600 transition-colors">
                        <x-heroicon-o-folder-plus class="w-4 h-4 mr-2" />
                        Add {{ $section_id ? 'Subfolder' : 'Section' }}
                    </button>
                </div>

                <!-- Folders Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse ($folders as $folder)
                        <div
                            class="relative bg-white dark:bg-gray-800 rounded-lg border border-gray-600 dark:border-gray-700
                                   hover:border-primary-500 dark:hover:border-primary-500 transition-colors group cursor-pointer"
                            wire:click="$set(
                                '{{ $parameter_id
                                    ? ($section_id
                                        ? 'subfolder_ids.' . count($subfolder_ids)
                                        : 'section_id')
                                    : 'parameter_id' }}',
                                {{ $folder->id }}
                            )"
                        >
                            {{-- Edit Menu --}}
                            <button
                                class="absolute top-2 right-2 p-1 text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 z-20 pointer-events-auto"
                                x-on:click.stop="$dispatch('open-modal', {
                                    modal: 'edit-folder',
                                    folder: {
                                        id: {{ $folder->id }},
                                        type: '{{ $parameter_id ? ($section_id ? 'subfolder' : 'section') : 'parameter' }}',
                                        name: @js($folder->name),
                                        slug: @js($folder->slug),
                                        description: @js($folder->description)
                                    }
                                })"
                            >
                                <x-heroicon-o-ellipsis-vertical class="w-5 h-5" />
                            </button>

                            {{-- Folder Content --}}
                            <div class="flex items-center p-4">
                                {{-- Left spacer --}}
                                <div class="w-4"></div>

                                {{-- Folder icon --}}
                                <x-heroicon-o-folder class="w-8 h-8 text-primary-500 dark:text-primary-400 flex-shrink-0" />

                                {{-- Right spacer --}}
                                <div class="w-4"></div>

                                {{-- Name & timestamp --}}
                                <div class="min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">
                                        {{ $folder->name }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Modified {{ $folder->updated_at?->diffForHumans() ?? 'Never' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-6 text-gray-500 dark:text-gray-400">
                            <x-heroicon-o-folder class="w-10 h-10 mx-auto mb-2 text-gray-400 dark:text-gray-500" />
                            <p class="text-sm">No {{ $section_id ? 'subfolders' : 'sections' }} found</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Documents Section -->
            <div class="pt-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Documents</h2>
                    <button
                        x-data
                        x-on:click="$dispatch('open-modal', { modal: 'upload-document' })"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium border-primary-600 text-white bg-primary-600 rounded-lg hover:bg-primary-600 focus:ring-2 focus:ring-primary-500 dark:bg-primary-700 dark:hover:bg-primary-600 transition-colors">
                        <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                        Upload Document
                    </button>
                </div>

                <!-- Search and Filters -->
        <div class="flex flex-wrap md:flex-nowrap gap-4 items-center mb-4">
            <!-- Search field -->
            <div class="flex items-center flex-1 bg-white dark:bg-gray-800 border-2 border-gray-600 dark:border-primary-700 rounded-md shadow-sm hover:shadow-sm transition group">
                <div class="flex items-center justify-center w-10 h-full">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400 dark:text-gray-500"/>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search documents..."
                    class="flex-1 pr-4 py-2 bg-transparent text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-0"
                />
            </div>

            <!-- Year filter -->
            <div class="flex-1 w-full">
                <select wire:model.live="year"
                        class="w-full py-2 border-2 border-gray-600 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-primary-500 transition">
                    <option value="">All Years</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>

                <!-- Documents Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse ($documents as $document)
                    <div wire:key="document-{{ $document->id }}">
                        @include('includes.document-cards', ['document' => $document])
                    </div>
                    @empty
                        <div class="col-span-full text-center py-6 text-gray-500 dark:text-gray-400">
                            <x-heroicon-o-folder class="w-10 h-10 mx-auto mb-2 text-gray-400 dark:text-gray-500" />
                            <p class="text-sm">No documents found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        <!-- Initial Parameters View -->
        <div>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Parameters</h2>
                <button wire:click="$dispatch('open-modal', { modal: 'create-folder' })"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 dark:bg-primary-700 dark:hover:bg-primary-600 transition-colors">
                    <x-heroicon-o-folder-plus class="w-4 h-4 mr-2" />
                    Add Parameter
                </button>
            </div>

            <!-- Parameters Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse ($folders as $folder)
                    <div
                        class="relative bg-white dark:bg-gray-800 rounded-lg border border-gray-600 dark:border-gray-700
                               hover:border-primary-300 dark:hover:border-primary-500 transition-colors cursor-pointer"
                        wire:click="$set('parameter_id', {{ $folder->id }})"
                    >
                        {{-- Edit Menu --}}
                        <button
                            wire:click.stop="$dispatch('open-modal', {
                                modal: 'edit-folder',
                                folder: {
                                    id: {{ $folder->id }},
                                    type: 'parameter',
                                    name: @js($folder->name),
                                    slug: @js($folder->slug),
                                    description: @js($folder->description)
                                }
                            })"
                            class="absolute top-2 right-2 z-10 p-1 text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 pointer-events-auto"
                            aria-label="Edit {{ $folder->name }}"
                        >
                            <x-heroicon-o-ellipsis-vertical class="w-5 h-5" />
                        </button>

                        {{-- Folder Content --}}
                        <div class="flex items-center p-4">
                            {{-- Left spacer --}}
                            <div class="w-4"></div>

                            {{-- Folder icon --}}
                            <x-heroicon-o-folder class="w-8 h-8 text-primary-500 dark:text-primary-400 flex-shrink-0" />

                            {{-- Right spacer --}}
                            <div class="w-4"></div>

                            {{-- Folder name & meta --}}
                            <div class="min-w-0">
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">
                                    {{ $folder->name }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Modified {{ $folder->updated_at?->diffForHumans() ?? 'Never' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-6 text-gray-500 dark:text-gray-400">
                        <x-heroicon-o-folder class="w-10 h-10 mx-auto mb-2 text-gray-400 dark:text-gray-500" />
                        <p class="text-sm">No parameters found</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- Include modals -->
    @include('includes.modals.view-document-modal')
    @include('includes.modals.edit-document-modal')
    @include('includes.modals.edit-folder-modal')
    @include('includes.modals.create-folder-modal')
    @include('includes.modals.upload-document-modal')
</div>
