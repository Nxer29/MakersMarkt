<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

            <div class="flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Products</h1>
                <a href="{{ route('products.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">
                    + Add product
                </a>
            </div>

            @if(session('success'))
                <div class="mt-4 p-3 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="border-b text-left">
                        <th class="py-2">Name</th>
                        <th class="py-2">Type</th>
                        <th class="py-2">Maker</th>
                        <th class="py-2">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr class="border-b">
                            <td class="py-2">
                                <a class="text-indigo-600 hover:underline" href="{{ route('products.show', $product) }}">
                                    {{ $product->name }}
                                </a>
                            </td>
                            <td class="py-2">{{ $product->type }}</td>
                            <td class="py-2">{{ $product->maker?->name ?? $product->maker_id }}</td>
                            <td class="py-2 flex gap-3">
                                <a class="text-indigo-600 hover:underline" href="{{ route('products.show', $product) }}">View</a>
                                <a class="text-indigo-600 hover:underline" href="{{ route('products.edit', $product) }}">Edit</a>

                                <form method="POST" action="{{ route('products.destroy', $product) }}"
                                      onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
