<x-app-layout>
    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Create product</h1>

            <form method="POST" action="{{ route('products.store') }}" class="mt-6 space-y-4">
                @csrf

                <input class="w-full border rounded px-3 py-2" name="name" placeholder="Name" value="{{ old('name') }}">
                <textarea class="w-full border rounded px-3 py-2" name="description" placeholder="Description">{{ old('description') }}</textarea>

                <input class="w-full border rounded px-3 py-2" name="type" placeholder="Type" value="{{ old('type') }}">
                <textarea class="w-full border rounded px-3 py-2" name="material" placeholder="Material">{{ old('material') }}</textarea>

                <input class="w-full border rounded px-3 py-2" name="production_time" placeholder="Production time" value="{{ old('production_time') }}">
                <input class="w-full border rounded px-3 py-2" name="complexity" placeholder="Complexity" value="{{ old('complexity') }}">

                <textarea class="w-full border rounded px-3 py-2" name="durability" placeholder="Durability">{{ old('durability') }}</textarea>
                <textarea class="w-full border rounded px-3 py-2" name="unique_features" placeholder="Unique features">{{ old('unique_features') }}</textarea>

                @if($errors->any())
                    <div class="p-3 bg-red-100 text-red-800 rounded">
                        <ul class="list-disc ml-5">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
                    <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
