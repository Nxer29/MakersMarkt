<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Products</p>
                <h2 class="mt-1 font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
                    Create product
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    Voeg een nieuw product toe met alle verplichte specificaties.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('products.portfolio') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">
                    My portfolio
                </a>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 dark:bg-indigo-600 dark:hover:bg-indigo-700">
                    Back to catalog
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('products.store') }}" class="space-y-6">
                @csrf

                @if($errors->any())
                    <div class="rounded-xl border border-red-200 dark:border-red-900/40 bg-red-50 dark:bg-red-900/20 p-4 text-red-800 dark:text-red-200">
                        <p class="font-semibold">Check je invoer:</p>
                        <ul class="list-disc ml-5 mt-2 text-sm">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Basisinformatie</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            Naam, type en beschrijving van je product.
                        </p>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Naam <span class="text-red-500">*</span></label>
                            <input name="name" value="{{ old('name') }}" placeholder="Bijv. Handgemaakte houten kom"
                                   class="mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 255 tekens.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Type <span class="text-red-500">*</span></label>
                            <input name="type" value="{{ old('type') }}" placeholder="Bijv. decor, kitchen, jewelry"
                                   class="mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Gebruik een duidelijke categorie.</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Beschrijving <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="5" placeholder="Vertel kort wat het is en voor wie..."
                                      class="mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Prijs <span class="text-red-500">*</span></label>
                            <input name="price" value="{{ old('price') }}" placeholder="Bijv. 49.99"
                                   class="mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Vul een geldig bedrag in, bijv. 49.99</p>
                        </div>


                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Specificaties</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            Deze velden zijn verplicht (US-12).
                        </p>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Materiaalgebruik <span class="text-red-500">*</span></label>
                            <textarea name="material" rows="3" placeholder="Bijv. eikenhout, natuurlijke olie-afwerking..."
                                      class="mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">{{ old('material') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Productietijd <span class="text-red-500">*</span></label>
                            <input name="production_time" value="{{ old('production_time') }}" placeholder="Bijv. 2-3 dagen"
                                   class="mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Complexiteit <span class="text-red-500">*</span></label>
                            <input name="complexity" value="{{ old('complexity') }}" placeholder="Bijv. low / medium / high"
                                   class="mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Duurzaamheid <span class="text-red-500">*</span></label>
                            <textarea name="durability" rows="3" placeholder="Bijv. waterbestendig, onderhoudstips..."
                                      class="mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">{{ old('durability') }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Unieke eigenschappen <span class="text-red-500">*</span></label>
                            <textarea name="unique_features" rows="4" placeholder="Wat maakt dit product uniek?"
                                      class="mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">{{ old('unique_features') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </a>
                    <button class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm">
                        Save product
                    </button>
                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Producten worden standaard <span class="font-semibold">niet geverifieerd</span> (verified=false) totdat een moderator controleert.
                </p>
            </form>
        </div>
    </div>
</x-app-layout>
