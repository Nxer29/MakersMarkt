<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Winkelwagen</h1>

            @if(session('success'))
                <div class="mt-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="mt-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <div class="mt-6 overflow-x-auto">
                @if(empty($items))
                    <p class="text-gray-700 dark:text-gray-200">Je winkelwagen is leeg.</p>
                @else
                    <table class="min-w-full text-sm">
                        <thead>
                        <tr class="border-b text-left">
                            <th class="py-2 text-gray-700 dark:text-gray-300">Product</th>
                            <th class="py-2 text-gray-700 dark:text-gray-300">Aantal</th>
                            <th class="py-2 text-gray-700 dark:text-gray-300">Prijs</th>
                            <th class="py-2 text-gray-700 dark:text-gray-300">Subtotaal</th>
                            <th class="py-2 text-gray-700 dark:text-gray-300">Actie</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $row)
                            <tr class="border-b">
                                <td class="py-2 text-gray-700 dark:text-gray-300">{{ $row['product']->name }}</td>
                                <td class="py-2 text-gray-700 dark:text-gray-300">{{ $row['qty'] }}</td>
                                <td class="py-2 text-gray-700 dark:text-gray-300">€ {{ number_format($row['unit'], 2, ',', '.') }}</td>
                                <td class="py-2 text-gray-700 dark:text-gray-300">€ {{ number_format($row['line'], 2, ',', '.') }}</td>
                                <td class="py-2 text-gray-700 dark:text-gray-300">
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $row['product']->id }}">
                                        <button class="text-red-600">Verwijder</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Totaal: € {{ number_format($total, 2, ',', '.') }}
                        </div>

                        <form method="POST" action="{{ route('cart.checkout') }}">
                            @csrf
                            <button class="px-4 py-2 bg-indigo-600 text-white rounded">
                                Bestellen (wallet credit)
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
