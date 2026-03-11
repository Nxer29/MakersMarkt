<x-app-layout>
    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <div class="flex items-center justify-between gap-3">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Edit product</h1>
                <a href="{{ route('products.show', $product) }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded">Back</a>
            </div>

            <form method="POST" action="{{ route('products.update', $product) }}" class="mt-6 space-y-4">
                @csrf
                @method('PATCH')

                <input class="w-full border rounded px-3 py-2" name="name" value="{{ old('name', $product->name) }}">
                <textarea class="w-full border rounded px-3 py-2" name="description">{{ old('description', $product->description) }}</textarea>

                <input class="w-full border rounded px-3 py-2" name="type" value="{{ old('type', $product->type) }}">
                <textarea class="w-full border rounded px-3 py-2" name="material">{{ old('material', $product->material) }}</textarea>

                <input class="w-full border rounded px-3 py-2" name="production_time" value="{{ old('production_time', $product->production_time) }}">
                <input class="w-full border rounded px-3 py-2" name="complexity" value="{{ old('complexity', $product->complexity) }}">

                <textarea class="w-full border rounded px-3 py-2" name="durability">{{ old('durability', $product->durability) }}</textarea>
                <textarea class="w-full border rounded px-3 py-2" name="unique_features">{{ old('unique_features', $product->unique_features) }}</textarea>

                @if($errors->any())
                    <div class="p-3 bg-red-100 text-red-800 rounded">
                        <ul class="list-disc ml-5">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
                    <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-200 rounded">Back to list</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
