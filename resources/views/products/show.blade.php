<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Product</p>
                <h2 class="mt-1 font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
                    {{ $product->name }}
                </h2>

                <div class="mt-2 flex flex-wrap gap-2">
                    <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-700 px-2.5 py-1 text-xs font-medium text-gray-800 dark:text-gray-100">
                        {{ $product->type }}
                    </span>

                    @if($product->verified)
                        <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/40 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-200">
                            Verified
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-yellow-100 dark:bg-yellow-900/40 px-2.5 py-1 text-xs font-medium text-yellow-800 dark:text-yellow-200">
                            Pending verification
                        </span>
                    @endif

                    @if($product->flagged)
                        <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/40 px-2.5 py-1 text-xs font-medium text-red-800 dark:text-red-200">
                            Flagged
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Catalog
                </a>
                <a href="{{ route('products.portfolio') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">
                    My portfolio
                </a>
                <a href="{{ route('products.edit', $product) }}"
                   class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Beschrijving</h3>
                        <p class="mt-2 text-gray-700 dark:text-gray-200">
                            {{ $product->description }}
                        </p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Specificaties</h3>

                        <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="rounded-xl bg-gray-50 dark:bg-gray-900/30 p-4">
                                <dt class="text-gray-500 dark:text-gray-400">Materiaalgebruik</dt>
                                <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100">{{ $product->material }}</dd>
                            </div>

                            <div class="rounded-xl bg-gray-50 dark:bg-gray-900/30 p-4">
                                <dt class="text-gray-500 dark:text-gray-400">Productietijd</dt>
                                <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100">{{ $product->production_time }}</dd>
                            </div>

                            <div class="rounded-xl bg-gray-50 dark:bg-gray-900/30 p-4">
                                <dt class="text-gray-500 dark:text-gray-400">Complexiteit</dt>
                                <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100">{{ $product->complexity }}</dd>
                            </div>

                            <div class="rounded-xl bg-gray-50 dark:bg-gray-900/30 p-4">
                                <dt class="text-gray-500 dark:text-gray-400">Duurzaamheid</dt>
                                <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100">{{ $product->durability }}</dd>
                            </div>

                            <div class="sm:col-span-2 rounded-xl bg-gray-50 dark:bg-gray-900/30 p-4">
                                <dt class="text-gray-500 dark:text-gray-400">Unieke eigenschappen</dt>
                                <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100 whitespace-pre-line">
                                    {{ $product->unique_features }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Maker</h3>
                        <p class="mt-2 text-gray-700 dark:text-gray-200">
                            <span class="text-gray-500 dark:text-gray-400">Naam:</span>
                            <span class="font-medium">{{ $product->maker?->name ?? 'Onbekend' }}</span>
                        </p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Status</h3>
                        <div class="mt-2 space-y-2 text-sm text-gray-700 dark:text-gray-200">
                            <p>
                                <span class="text-gray-500 dark:text-gray-400">Verified:</span>
                                <span class="font-medium">{{ $product->verified ? 'Yes' : 'No' }}</span>
                            </p>
                            <p>
                                <span class="text-gray-500 dark:text-gray-400">Flagged:</span>
                                <span class="font-medium">{{ $product->flagged ? 'Yes' : 'No' }}</span>
                            </p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
