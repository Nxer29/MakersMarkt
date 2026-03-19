<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            @vite(['resources/css/app.css', 'resources/js/app.js'])

            <div class="text-sm text-gray-600 dark:text-gray-300">
                Welkom, {{ Auth::user()->username ?? Auth::user()->name }}.
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Hero --}}
            <section class="px-4 sm:px-0">
                <div class="grid lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                            Handgemaakt • Lokaal • Uniek
                        </p>

                        <h1 class="mt-3 text-4xl sm:text-5xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
                            Koop en verkoop handgemaakte producten op één plek.
                        </h1>

                        <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                            MakersMarkt verbindt makers en kopers. Ontdek unieke items, steun kleine makers en bestel veilig met winkelkrediet.
                        </p>

                        <div class="mt-7 flex flex-wrap gap-3">
                            <a href="{{ route('products.index') }}"
                               class="px-5 py-3 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                                Bekijk producten
                            </a>

                            <a href="{{ route('orders.index') }}"
                               class="px-5 py-3 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 text-gray-700 dark:text-gray-300">
                                Mijn bestellingen
                            </a>
                        </div>

                        <div class="mt-8 grid sm:grid-cols-3 gap-4">
                            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
                                <div class="font-semibold text-gray-700 dark:text-gray-300">Veilig betalen</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                    Met winkelkrediet & automatische transfer.
                                </div>
                            </div>

                            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
                                <div class="font-semibold text-gray-700 dark:text-gray-300">Unieke items</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                    Handgemaakt door echte makers.
                                </div>
                            </div>

                            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
                                <div class="font-semibold text-gray-700 dark:text-gray-300 ">Transparant</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                    Bestellingen en logs altijd terug te vinden.
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Decorative card --}}
                    <div class="relative">
                        <div class="absolute -inset-4 bg-gradient-to-tr from-indigo-500/20 via-purple-500/20 to-pink-500/20 blur-2xl"></div>

                        <div class="relative p-6 sm:p-8 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow">
                            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 ">Waarom MakersMarkt?</h2>

                            <ul class="mt-4 space-y-3 text-gray-700 dark:text-gray-300">
                                <li>• Voeg producten toe aan je winkelwagen</li>
                                <li>• Stort krediet via je profiel</li>
                                <li>• Bestel direct: koper → maker transfer</li>
                                <li>• Status “nieuw” + logging met datum/tijd</li>
                            </ul>

                            <div class="mt-6 p-4 rounded-xl bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800">
                                <div class="text-sm text-gray-600 dark:text-gray-300">Tip</div>
                                <div class="font-semibold text-gray-900 dark:text-gray-100">
                                    Check je winkelwagen en je krediet in de navigatiebalk bovenaan.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- CTA --}}
            <section class="mt-10 border-t border-gray-200 dark:border-gray-800 pt-8 px-4 sm:px-0">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Snel starten</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Ga direct naar je catalogus, bestellingen of meldingen.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('products.index') }}"
                           class="px-5 py-3 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                            Producten
                        </a>

                        <a href="{{ route('notifications.page') }}"
                           class="px-5 py-3 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 text-gray-900 dark:text-gray-100">
                            Meldingen
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
