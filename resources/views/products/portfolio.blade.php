<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Maker</p>
                <h2 class="mt-1 font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
                    My portfolio
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    Alleen jouw producten. Nieuwe items zijn standaard niet geverifieerd.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Catalog
                </a>
                <a href="{{ route('products.create') }}"
                   class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm">
                    + Add product
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 dark:border-green-900/40 bg-green-50 dark:bg-green-900/20 p-4 text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ✅ Filters (nieuw): zelfde als catalogus maar voor portfolio --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Filters</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        Alleen jouw producten. Combineer filters (AND).
                    </p>
                </div>

                <div class="p-4 sm:p-6">
                    <form method="GET" action="{{ route('products.portfolio') }}" class="grid grid-cols-1 md:grid-cols-5 lg:grid-cols-6 gap-4 items-end">
                        {{-- Type --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Type</label>
                            <select name="type" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900/30">
                                <option value="">Alles</option>
                                @foreach(($types ?? []) as $type)
                                    <option value="{{ $type }}" @selected(request('type') == $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Material (like match) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Materiaal</label>
                            <input
                                type="text"
                                name="material"
                                value="{{ request('material') }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900/30"
                                placeholder="bv. hout"
                            >
                        </div>

                        {{-- Production time --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Productietijd</label>
                            <select name="production_time" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900/30">
                                <option value="">Alles</option>
                                @foreach(($productionTimes ?? []) as $pt)
                                    <option value="{{ $pt }}" @selected(request('production_time') == $pt)>{{ $pt }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ✅ NIEUW: Sortering (US-11) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Sorteren</label>
                            <select name="sort" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900/30">
                                <option value="">Nieuwste eerst</option>
                                <option value="name" @selected(request('sort') == 'name')>Naam</option>
                                <option value="created_at" @selected(request('sort') == 'created_at')>Aanmaakdatum</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Richting</label>
                            <select name="direction" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900/30">
                                <option value="asc" @selected(request('direction') == 'asc')>Oplopend</option>
                                <option value="desc" @selected(request('direction', 'desc') == 'desc')>Aflopend</option>
                            </select>
                        </div>

                        <div class="md:col-span-5 lg:col-span-6 flex flex-wrap gap-2 mt-2">
                            <button
                                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm">
                                Toepassen
                            </button>

                            <a href="{{ route('products.portfolio') }}"
                               class="inline-flex items-center justify-center rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Results (jouw originele tabel, ongewijzigd) --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Your products</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $products->total() }} items</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr class="text-left">
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">Name</th>
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">Type</th>
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">Status</th>
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">Actions</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                <td class="py-3 px-6">
                                    <a class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline" href="{{ route('products.show', $product) }}">
                                        {{ $product->name }}
                                    </a>
                                </td>

                                <td class="py-3 px-6">
                                    <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-700 px-2.5 py-1 text-xs font-medium text-gray-800 dark:text-gray-100">
                                        {{ $product->type }}
                                    </span>
                                </td>

                                <td class="py-3 px-6">
                                    <div class="flex flex-wrap gap-2">
                                        @if($product->verified)
                                            <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/40 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-200">
                                                Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-yellow-100 dark:bg-yellow-900/40 px-2.5 py-1 text-xs font-medium text-yellow-800 dark:text-yellow-200">
                                                Pending
                                            </span>
                                        @endif

                                        @if($product->flagged)
                                            <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/40 px-2.5 py-1 text-xs font-medium text-red-800 dark:text-red-200">
                                                Flagged
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-3 px-6">
                                    <div class="flex flex-wrap gap-3">
                                        <a class="text-indigo-600 dark:text-indigo-400 hover:underline" href="{{ route('products.edit', $product) }}">Edit</a>
                                        <form method="POST" action="{{ route('products.destroy', $product) }}"
                                              onsubmit="return confirm('Delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 px-6">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 dark:text-gray-300">No products yet.</p>
                                        <a href="{{ route('products.create') }}"
                                           class="mt-3 inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                            Create your first product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 sm:p-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
