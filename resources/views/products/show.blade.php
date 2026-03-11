<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ $product->name }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Type: <span class="font-medium">{{ $product->type }}</span>
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded">
                    ← terug
                </a>
                <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 bg-indigo-600 text-white rounded">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Beschrijving</h2>
            <p class="mt-2 text-gray-700 dark:text-gray-200">
                {{ $product->description }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Specificaties</h2>

                <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Materiaalgebruik</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $product->material }}</dd>
                    </div>

                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Productietijd</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $product->production_time }}</dd>
                    </div>

                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Complexiteit</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $product->complexity }}</dd>
                    </div>

                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Duurzaamheid</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $product->durability }}</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-gray-500 dark:text-gray-400">Unieke eigenschappen</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100 whitespace-pre-line">
                            {{ $product->unique_features }}
                        </dd>
                    </div>
                </dl>
            </div>

            <aside class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Maker</h2>

                <p class="mt-3 text-gray-700 dark:text-gray-200">
                    <span class="text-gray-500 dark:text-gray-400">Naam:</span>
                    <span class="font-medium">{{ $product->maker?->name ?? 'Onbekend' }}</span>
                </p>
            </aside>
        </div>

    </div>
</x-app-layout>
