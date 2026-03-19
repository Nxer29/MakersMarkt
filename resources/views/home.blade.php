<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'MakersMarkt') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        (function () {
            const stored = localStorage.getItem('theme'); // 'light' | 'dark' | null
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = stored ?? (prefersDark ? 'dark' : 'light');

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>

<body class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">

<header class="border-b border-gray-200 dark:border-gray-800 bg-white/70 dark:bg-gray-950/70 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold">
                MM
            </div>
            <div class="font-semibold tracking-tight">
                MakersMarkt
            </div>
        </div>

        <nav class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900">
                    Inloggen
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                    Registreren
                </a>
            @endauth
        </nav>
    </div>
</header>

<main>
    {{-- Hero --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
            <div>
                <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">Handgemaakt • Lokaal • Uniek</p>
                <h1 class="mt-3 text-4xl sm:text-5xl font-bold tracking-tight">
                    Koop en verkoop handgemaakte producten op één plek.
                </h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                    MakersMarkt verbindt makers en kopers. Ontdek unieke items, steun kleine makers en bestel veilig met winkelkrediet.
                </p>

                <div class="mt-7 flex flex-wrap gap-3">
                    @auth
                        <a href="{{ route('products.index') }}" class="px-5 py-3 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                            Bekijk producten
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-3 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                            Bekijk producten
                        </a>
                    @endauth

                    <a href="{{ route('register') }}" class="px-5 py-3 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900">
                        Word maker
                    </a>
                </div>

                <div class="mt-8 grid sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
                        <div class="font-semibold">Veilig betalen</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Met winkelkrediet & automatische transfer.</div>
                    </div>
                    <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
                        <div class="font-semibold">Unieke items</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Handgemaakt door echte makers.</div>
                    </div>
                    <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
                        <div class="font-semibold">Transparant</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Bestellingen en logs altijd terug te vinden.</div>
                    </div>
                </div>
            </div>

            {{-- Decorative card --}}
            <div class="relative">
                <div class="absolute -inset-4 bg-gradient-to-tr from-indigo-500/20 via-purple-500/20 to-pink-500/20 blur-2xl"></div>
                <div class="relative p-6 sm:p-8 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow">
                    <h2 class="text-xl font-semibold">Waarom MakersMarkt?</h2>
                    <ul class="mt-4 space-y-3 text-gray-700 dark:text-gray-300">
                        <li>• Voeg producten toe aan je winkelwagen</li>
                        <li>• Stort krediet via je profiel</li>
                        <li>• Bestel direct: koper → maker transfer</li>
                        <li>• Status “nieuw” + logging met datum/tijd</li>
                    </ul>

                    <div class="mt-6 p-4 rounded-xl bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800">
                        <div class="text-sm text-gray-600 dark:text-gray-300">Tip</div>
                        <div class="font-semibold">Log in om je winkelwagen en krediet te gebruiken.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-semibold">Klaar om te starten?</h3>
                <p class="text-gray-600 dark:text-gray-300">Maak een account aan en ontdek handgemaakte producten.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('register') }}" class="px-5 py-3 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                    Registreren
                </a>
                <a href="{{ route('login') }}" class="px-5 py-3 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900">
                    Inloggen
                </a>
            </div>
        </div>
    </section>
</main>

<footer class="py-10 text-center text-sm text-gray-500 dark:text-gray-400">
    © {{ date('Y') }} MakersMarkt
</footer>
</body>
</html>
