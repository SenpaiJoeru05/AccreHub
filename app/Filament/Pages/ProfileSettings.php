<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class ProfileSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static string $view = 'filament.pages.profile-settings';
    protected static ?string $navigationLabel = 'Settings';
    protected static bool $shouldRegisterNavigation = true;

    public bool $editing = false;

    public ?array $data = [];

    protected $messages = [
        'data.name.required' => 'The name field is required.',
        'data.email.required' => 'The email field is required.',
        'data.email.email' => 'Please enter a valid email address.',
        'data.email.unique' => 'This email is already taken.',
        'data.current_password.required' => 'Current password is required to change your password.',
        'data.current_password.current_password' => 'The current password is incorrect.',
        'data.new_password.confirmed' => 'The new password confirmation does not match.',
    ];

    public function mount(): void
    {
        $user = auth()->user();
        $this->data = [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => '',
            'new_password' => '',
            'new_password_confirmation' => '',
        ];
    }

    public function save(): void
{
    $user = auth()->user();

    $this->validate([
        'data.name' => ['required', 'string', 'max:255'],
        'data.email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        'data.current_password' => [
            Rule::requiredIf(fn () => filled($this->data['new_password'])),
            function ($attribute, $value, $fail) use ($user) {
                if (filled($this->data['new_password']) && !Hash::check($value, $user->password)) {
                    $fail('The current password is incorrect.');
                }
            },
        ],
        'data.new_password' => [
            'nullable',
            'string',
            Password::defaults(),
            'confirmed'
        ],
    ], $this->messages);

    $updatedFields = [];

    if ($this->data['name'] !== $user->name) {
        $user->name = $this->data['name'];
        $updatedFields[] = 'name';
    }

    if ($this->data['email'] !== $user->email) {
        $user->email = $this->data['email'];
        $updatedFields[] = 'email';
    }

    if (filled($this->data['new_password'])) {
        $user->password = Hash::make($this->data['new_password']);
        $updatedFields[] = 'password';
    }

    $user->save();

    // Reset editing state and sensitive data
    $this->editing = false;
    $this->data['current_password'] = '';
    $this->data['new_password'] = '';
    $this->data['new_password_confirmation'] = '';

    // Generate a detailed success notification
    $notificationMessage = 'Profile updated successfully.';

    if (in_array('name', $updatedFields)) {
        $notificationMessage = 'Your name was updated successfully.';
    }

    if (in_array('email', $updatedFields)) {
        $notificationMessage = 'Your email was updated successfully.';
    }

    if (in_array('password', $updatedFields)) {
        $notificationMessage = 'Your password was updated successfully.';
    }

    // Notify with a more detailed message
    Notification::make()
        ->title($notificationMessage)
        ->success()
        ->send();
}


    public function edit(): void
    {
        $this->editing = true;
    }

    public function cancel(): void
    {
        $this->editing = false;
        $this->mount();
    }
}
