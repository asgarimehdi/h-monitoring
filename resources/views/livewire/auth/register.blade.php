<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $n_code = '';

    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
//            'f_name' => ['required', 'string', 'max:255'],
//            'l_name' => ['required', 'string', 'max:255'],
            'n_code' => ['required', 'string', 'digits:10', 'unique:' . User::class . ',n_code'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="Create an account" description="Enter your details below to create your account" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->


        <!-- Email Address -->
        <flux:input
            wire:model="n_code"
            id="n_code"
            :label="__('n_code')"
            type="text"
            name="n_code"
            required
            autocomplete="n_code"
            placeholder="1234567890"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            id="password"
            :label="__('Password')"
            type="password"
            name="password"
            required
            autocomplete="new-password"
            placeholder="Password"
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            id="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            name="password_confirmation"
            required
            autocomplete="new-password"
            placeholder="Confirm password"
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        Already have an account?
        <flux:link :href="route('login')" wire:navigate>Log in</flux:link>
    </div>
</div>
