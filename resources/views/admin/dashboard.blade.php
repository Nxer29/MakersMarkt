<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Admin dashboard
            </h2>

            <span class="text-sm text-gray-600 dark:text-gray-300">
                Ingelogd als: {{ Auth::user()->username ?? Auth::user()->name }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            {{-- KPI cards --}}
            <a href="{{ route('admin.users.index') }}"
                                  class="block p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-indigo-400 dark:hover:border-indigo-500 transition"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Geverifieerde Gebruikers</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $verifiedUsersCount }}
                        </div>
                    </div>

                    <div class="text-indigo-600 dark:text-indigo-400 text-sm font-medium">
                        Beheer →
                    </div>
                </div>
            </a>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
                    <div class="text-sm text-gray-600 dark:text-gray-300">Totaal producten</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalProducts }}</div>
                </div>

                <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
                    <div class="text-sm text-gray-600 dark:text-gray-300">Orders (7 dagen)</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $ordersLast7Days }}</div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Producten per type</h3>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Pie</span>
                    </div>
                    <div class="mt-4">
                        <canvas id="productsByTypeChart" height="140"></canvas>
                    </div>
                </div>

                 <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Bestellingen per dag (laatste 7 dagen)</h3>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Line</span>
                    </div>
                    <div class="mt-4">
                        <canvas id="ordersPerDayChart" height="140"></canvas>
                    </div>
                 </div>
            </div>
            </div>
        </div>

    <script>
        window.__ADMIN_DASHBOARD__ = {
            productsByType: @json($productsByType->map(fn($r) => ['type' => $r->type ?? 'onbekend', 'total' => (int) $r->total])),
            orderDays: @json($orderDays),
            ordersPerDay: @json($ordersPerDay),
        };
    </script>

    @vite(['resources/js/admin-dashboard.js'])
</x-app-layout>
