<x-filament::page class="bg-transparent text-black dark:text-white px-0">
    <div class="container mx-auto max-w-screen-xl px-4 py-16">
        <div class="flex flex-col lg:flex-row gap-8 justify-center">

            <!-- Profile Card (centered) -->
            <x-filament::card class="w-full lg:w-96 bg-gray-50 dark:bg-gray-800 p-10 text-center rounded-2xl ring-1 ring-gray-200 dark:ring-gray-700 shadow-md dark:shadow-2xl flex flex-col items-center justify-center hover:shadow-[0_20px_50px_rgba(8,_112,_184,_0.7)] transition-all duration-300">
                <div class="mx-auto mb-4 w-40 h-40 rounded-full bg-gray-700 flex items-center justify-center">
                    <x-heroicon-o-user-circle class="h-22 w-22 text-black dark:text-gray-300" />
                </div>
                <h1 class="text-4xl font-bold text-gray-200 mb-2">____________________________________</h1>
                <h2 class="text-2xl font-semibold mt-8 mb-4 text-center">{{ $data['name'] ?? auth()->user()->name }}</h2>
                <p class="text-gray-400 text-center mt-2">{{ $data['email'] ?? auth()->user()->email }}</p>
                <p class="text-gray-400 text-center mt-2">{{ auth()->user()->role }}</p>
            </x-filament::card>

            <!-- Account Info Card -->
            <x-filament::card class="w-full lg:w-[90rem] bg-gray-50 dark:bg-gray-800 p-10 rounded-2xl ring-1 ring-gray-200 dark:ring-gray-700 shadow-md dark:shadow-2xl hover:shadow-[0_20px_50px_rgba(8,_112,_184,_0.7)] transition-all duration-300">
                <h3 class="text-3xl font-bold mb-8">My Profile Account</h3>
                <p class="text-gray-400 mb-4">Manage your profile settings and preferences.</p>
                <p class="text-black-400 mb-2">_________________________________________________________________________</p>
                <h6 class="text-lg font-semibold mb-4">Account Information:</h6>

                <form wire:submit.prevent="{{ $editing ? 'save' : 'edit' }}">
                    <div class="flex flex-col space-y-6 mt-6">

                        <!-- Name Field -->
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Name</label>
                            @if($editing)
                                <input type="text" wire:model.defer="data.name" 
                                    class="w-full md:w-[60rem] rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-black dark:text-white">
                                @error('data.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            @else
                                <p class="text-xl font-medium mt-2">{{ $data['name'] ?? auth()->user()->name }}</p>
                            @endif
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Email</label>
                            @if($editing)
                                <input type="email" wire:model.defer="data.email" 
                                    class="w-full md:w-[60rem] rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-black dark:text-white">
                                @error('data.email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            @else
                                <p class="text-xl font-medium mt-2">{{ $data['email'] ?? auth()->user()->email }}</p>
                            @endif
                        </div>

                        @if($editing)
                            <!-- Current Password -->
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Current Password</label>
                                <div class="relative flex items-center gap-3 w-full md:w-[60rem]" x-data="{ show: false }">
                                    <input 
                                        :type="show ? 'text' : 'password'" 
                                        wire:model.defer="data.current_password"
                                        class="w-full rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 
                                               bg-gray-100 dark:bg-gray-700 
                                               border-gray-300 dark:border-gray-600 
                                               text-black dark:text-white"
                                    >
                                    <button 
                                        type="button" 
                                        x-on:click="show = !show" 
                                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none"
                                    >
                                        <template x-if="show">
                                            <x-heroicon-o-eye-slash class="h-5 w-5" />
                                        </template>
                                        <template x-if="!show">
                                            <x-heroicon-o-eye class="h-5 w-5" />
                                        </template>
                                    </button>
                                </div>
                                @error('data.current_password')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">New Password</label>
                                <div class="relative flex items-center gap-3 w-full md:w-[60rem]" x-data="{ show: false }">
                                    <input 
                                        :type="show ? 'text' : 'password'" 
                                        wire:model.defer="data.new_password"
                                        class="w-full rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 
                                               bg-gray-100 dark:bg-gray-700 
                                               border-gray-300 dark:border-gray-600 
                                               text-black dark:text-white"
                                    >
                                    <button 
                                        type="button" 
                                        x-on:click="show = !show" 
                                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none"
                                    >
                                        <template x-if="show">
                                            <x-heroicon-o-eye-slash class="h-5 w-5" />
                                        </template>
                                        <template x-if="!show">
                                            <x-heroicon-o-eye class="h-5 w-5" />
                                        </template>
                                    </button>
                                </div>
                                @error('data.new_password') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Confirm New Password -->
                            <div class="mb-12">
                                <label class="block text-sm text-gray-400 mb-1">Confirm New Password</label>
                                <div class="relative flex items-center gap-3 w-full md:w-[60rem]" x-data="{ show: false }">
                                    <input 
                                        :type="show ? 'text' : 'password'" 
                                        wire:model.defer="data.new_password_confirmation"
                                        class="w-full rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 
                                               bg-gray-100 dark:bg-gray-700 
                                               border-gray-300 dark:border-gray-600 
                                               text-black dark:text-white"
                                    >
                                    <button 
                                        type="button" 
                                        x-on:click="show = !show" 
                                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none"
                                    >
                                        <template x-if="show">
                                            <x-heroicon-o-eye-slash class="h-5 w-5" />
                                        </template>
                                        <template x-if="!show">
                                            <x-heroicon-o-eye class="h-5 w-5" />
                                        </template>
                                    </button>
                                </div>
                                @error('data.new_password_confirmation')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <p class="text-black-400 mb-2">_________________________________________________________________________</p>

                    <!-- Buttons -->
                    <div class="text-right mt-8">
                        @if($editing)
                            <x-filament::button size="lg" color="secondary" class="px-8 py-3 mr-2" wire:click="cancel">
                                Cancel
                            </x-filament::button>
                            <x-filament::button size="lg" color="primary" class="px-8 py-3" type="submit">
                                Save
                            </x-filament::button>
                        @else
                            <x-filament::button size="lg" color="primary" class="px-8 py-3" wire:click="edit">
                                Edit Profile
                            </x-filament::button>
                        @endif
                    </div>
                </form>
            </x-filament::card>
        </div>
    </div>
</x-filament::page>
