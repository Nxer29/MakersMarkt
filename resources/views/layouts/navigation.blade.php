<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    @php
        $cart = session('cart', []);
        $cartCount = 0;
        foreach ($cart as $row) {
            $cartCount += (int)($row['qty'] ?? 1);
        }
    @endphp
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"/>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Catalog + CRUD --}}
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        {{ __('Products') }}
                    </x-nav-link>

                    {{-- My orders --}}
                    <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                        {{ __('Orders') }}
                    </x-nav-link>

                    {{-- Notifications --}}
                    <x-nav-link :href="route('notifications.page')" :active="request()->routeIs('notifications.*')">
                        {{ __('Notifications') }}
                    </x-nav-link>

                    @role('admin')
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                        {{ __('Admin') }}
                    </x-nav-link>
                    @endrole
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <span class="text-sm text-gray-600 dark:text-gray-300">
                    Krediet: € {{ number_format(Auth::user()->wallet_credit ?? 0, 2, ',', '.') }}
                </span>

                {{-- Cart icon --}}
                <a href="{{ route('cart.index') }}"
                   class="relative inline-flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                   aria-label="Winkelwagen">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.2 6H19M7 13l-2-8m14 0l-2 8M9 21a1 1 0 100-2 1 1 0 000 2zm10 0a1 1 0 100-2 1 1 0 000 2z"/>
                    </svg>

                    @if(($cartCount ?? 0) > 0)
                        <span class="absolute -top-2 -right-2 inline-flex items-center justify-center text-xs font-bold leading-none
                         text-white bg-red-600 rounded-full h-5 min-w-5 px-1">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                {{-- Profile dropdown --}}
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->username ?? Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- Theme toggle --}}
                        <button
                            type="button"
                            onclick="
                                const isDark = document.documentElement.classList.toggle('dark');
                                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                                const label = isDark ? 'Light mode' : 'Dark mode';
                                const pill = isDark ? 'On' : 'Off';
                                const el = document.getElementById('theme-toggle-label');
                                const pl = document.getElementById('theme-toggle-pill');
                                if (el) el.textContent = label;
                                if (pl) pl.textContent = pill;
                            "
                            class="w-full text-left flex items-center justify-between gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                        >
                            <span class="flex items-center gap-2">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 dark:bg-gray-700">
                                    <svg class="h-4 w-4 text-gray-700 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 15a5 5 0 1 1 0-10 5 5 0 0 1 0 10Z"/>
                                        <path fill-rule="evenodd" d="M10 1.5a.75.75 0 0 1 .75.75v1a.75.75 0 0 1-1.5 0v-1A.75.75 0 0 1 10 1.5ZM10 16.75a.75.75 0 0 1 .75.75v1a.75.75 0 0 1-1.5 0v-1a.75.75 0 0 1 .75-.75ZM3.72 3.72a.75.75 0 0 1 1.06 0l.707.707a.75.75 0 0 1-1.06 1.06l-.707-.707a.75.75 0 0 1 0-1.06Zm10.793 10.793a.75.75 0 0 1 1.06 0l.707.707a.75.75 0 0 1-1.06 1.06l-.707-.707a.75.75 0 0 1 0-1.06ZM1.5 10a.75.75 0 0 1 .75-.75h1a.75.75 0 0 1 0 1.5h-1A.75.75 0 0 1 1.5 10Zm13.75-.75a.75.75 0 0 1 0 1.5h-1a.75.75 0 0 1 0-1.5h1ZM3.72 16.28a.75.75 0 0 1 0-1.06l.707-.707a.75.75 0 1 1 1.06 1.06l-.707.707a.75.75 0 0 1-1.06 0ZM14.513 5.487a.75.75 0 0 1 0-1.06l.707-.707a.75.75 0 0 1 1.06 1.06l-.707.707a.75.75 0 0 1-1.06 0Z" clip-rule="evenodd"/>
                                    </svg>
                                </span>

                                <span class="flex flex-col leading-tight">
                                    <span class="font-medium" id="theme-toggle-label">
                                        <script>
                                            document.write(document.documentElement.classList.contains('dark') ? 'Light mode' : 'Dark mode');
                                        </script>
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        Switch appearance
                                    </span>
                                </span>
                            </span>

                            <span id="theme-toggle-pill" class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-700 px-2.5 py-1 text-xs font-medium text-gray-700 dark:text-gray-200">
                                <script>
                                    document.write(document.documentElement.classList.contains('dark') ? 'On' : 'Off');
                                </script>
                            </span>
                        </button>

                        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        {{-- ✅ Admin-only tools in dropdown --}}
                        @role('admin')
                        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                        <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Admin tools
                        </div>

                        <x-dropdown-link :href="route('moderation.users.index')">
                            Users (verify)
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('moderation.search.index')">
                            Moderation search
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('admin.dashboard')">
                            Admin dashboard
                        </x-dropdown-link>
                        @endrole

                        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
