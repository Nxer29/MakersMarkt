<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

<body class="min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">
<div class="min-h-screen flex items-center justify-center px-4 py-10">
    {{-- Background glow --}}
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-indigo-500/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-purple-500/20 blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative">
        {{-- Logo / title --}}
        <div class="flex items-center justify-center gap-3 mb-6">
            <div class="h-11 w-11 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow">
                MM
            </div>
            <div class="text-xl font-semibold tracking-tight">
                MakersMarkt
            </div>
        </div>

        {{-- Auth card --}}
        <div class="bg-white/90 dark:bg-gray-900/80 backdrop-blur border border-gray-200 dark:border-gray-800 shadow-sm rounded-2xl p-6 sm:p-8">
            {{ $slot }}
        </div>

        <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
            © {{ date('Y') }} MakersMarkt
        </p>
    </div>
</div>
</body>
</html>
