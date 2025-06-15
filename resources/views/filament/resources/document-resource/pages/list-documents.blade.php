<x-filament-panels::page>
    @php
        $areaId = request()->query('tableFilters')['area']['value'] ?? null;
        $area = $areaId ? \App\Models\Area::find($areaId) : null;
        $areaName = $area ? $area->name . ' - Student' : 'All Documents';
    @endphp

    <x-filament::section class="p-6">
        <x-slot name="heading">{{ $areaName }}</x-slot>

        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center space-x-4">
                <x-filament::input.wrapper>
                    <x-filament::input type="text" wire:model.debounce.500ms="tableSearch" placeholder="Search documents..." />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model="tableFilters.parameter">
                        <option value="">All Parameters</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                    </x-filament::input.select>
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model="tableFilters.year">
                        <option value="">All Years</option>
                        @foreach (\App\Models\Document::select('year')->distinct()->pluck('year') as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>

            <x-filament::button
                tag="a"
                href="{{ route('filament.admin.resources.documents.create') }}"
                color="primary"
            >
                Add New Document
            </x-filament::button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if ($records && $records->count() > 0)
                @foreach ($records as $record)
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold mb-2">{{ $record->title }}</h3>
                        <p class="text-sm text-gray-600 mb-1">Area: {{ $record->area->name }}</p>
                        <p class="text-sm text-gray-600 mb-1">Parameter: {{ $record->parameter }}</p>
                        <p class="text-sm text-gray-600 mb-1">Year: {{ $record->year }}</p>
                        <p class="text-sm text-gray-600 mb-1">Type: {{ $record->type }}</p>
                        <p class="text-sm text-gray-600 mb-4">Section: {{ $record->section ?? 'N/A' }}</p>
                        <div class="flex justify-between">
                            <x-filament::button
                                tag="a"
                                href="{{ \Illuminate\Support\Facades\Storage::url($record->path) }}"
                                target="_blank"
                                color="info"
                                outlined
                            >
                                View File
                            </x-filament::button>
                            <div class="flex space-x-2">
                                <x-filament::button
                                    tag="a"
                                    href="{{ route('filament.admin.resources.documents.edit', $record) }}"
                                    color="success"
                                    outlined
                                >
                                    Edit
                                </x-filament::button>
                                <form action="{{ route('filament.admin.resources.documents.destroy', $record) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-filament::button type="submit" color="danger" outlined>
                                        Delete
                                    </x-filament::button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500">No documents found.</p>
            @endif
        </div>

        @if ($records)
            <div class="mt-6">
                {{ $records->links() }}
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>