<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Akun</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ __('Profile') }}</h1>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl space-y-6">
        <div class="ui-card p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="ui-card p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="ui-card border-slate-100 p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.account-deletion-policy')
            </div>
        </div>
    </div>
</x-app-layout>
