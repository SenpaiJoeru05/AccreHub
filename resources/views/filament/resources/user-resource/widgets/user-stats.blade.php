
{{-- filepath: resources/views/filament/resources/user-resource/widgets/user-stats.blade.php --}}
@php
    use App\Models\User;
@endphp

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
        <div class="text-lg font-bold">{{ User::count() }}</div>
        <div class="text-gray-500">Total Users</div>
    </div>
    <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
        <div class="text-lg font-bold">{{ User::where('role', 'admin')->count() }}</div>
        <div class="text-gray-500">Admins</div>
    </div>
    <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
        <div class="text-lg font-bold">{{ User::where('role', 'faculty')->count() }}</div>
        <div class="text-gray-500">Faculty</div>
    </div>
</div>