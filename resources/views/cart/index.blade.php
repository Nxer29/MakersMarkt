<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Winkelwagen</h1>

            @if(session('success'))
                <div class="mt-4 p-3 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mt-6 overflow-x-auto">
                @if(empty($items))
                    <p class="text-gray-700 dark:text-gray-200">Je winkelwagen is leeg.</p>
                @else
                    <table class="min-w-full text-sm">
                        <thead>
                        <tr class="border-b text-left">
                            <th class="py-2">Product</th>
                            <th class="py-2">Aantal</th>
                            <th class="py-2">Prijs</th>
                            <th class="py-2">Subtotaal</th>
                            <th class="py-2">Actie</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr class="border-b">
                                <td class="py-2">{{ $item['product']->name }}</td>
                                <td class="py-2">{{ $item['qty'] }}</td>
                                <td class="py-2">€ {{ number_format($item['unit_price'], 2, ',', '.') }}</td>
                                <td class="py-2">€ {{ number_format($item['line_total'], 2, ',', '.') }}</td>
                                <td class="py-2">
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                        <button class="text-red-600">Verwijder</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6 flex justify-end">
                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Totaal: € {{ number_format($total, 2, ',', '.') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
