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
                            <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $user->role }}</dd>
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

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Wallet credit</h3>

                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Huidig krediet: <span class="font-semibold">{{ number_format(auth()->user()->wallet_credit ?? 0, 2, ',', '.') }}</span>
                    </div>

                    @if(session('success'))
                        <div class="mt-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('profile.deposit-credit') }}" class="mt-4 flex gap-3 items-end">
                        @csrf
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Bedrag</label>
                            <input name="amount" type="number" step="0.01" min="0.01"
                                   class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                   placeholder="10.00" required>
                            @error('amount')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="px-4 py-2 bg-indigo-600 text-white rounded">
                            Storten
                        </button>
                    </form>
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
