<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ✅ NEW: read-only profiel overzicht (ticket) --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Account overzicht
                    </h3>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Dit zijn je huidige gegevens zoals ze in het systeem staan.
                    </p>

                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Gebruikersnaam</dt>
                            <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</dd>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Rol</dt>
                            <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $user->role ?? 'buyer' }}</dd>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Verificatiestatus (account)</dt>
                            <dd class="font-medium text-gray-900 dark:text-gray-100">
                                {{ ($user->verified ?? false) ? 'Verified' : 'Not verified' }}
                            </dd>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Winkelkrediet</dt>
                            <dd class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $user->store_credit ?? 0 }}
                            </dd>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">E-mail geverifieerd</dt>
                            <dd class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $user->email_verified_at ? 'Ja' : 'Nee' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Breeze standaard: update profiel --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Breeze standaard: update password --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Breeze standaard: delete account --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
