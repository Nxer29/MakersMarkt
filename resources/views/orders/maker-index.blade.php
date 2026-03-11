<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Maker</p>
                <h2 class="mt-1 font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
                    Orders for my products
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    Alleen jij (als maker) kan deze statuses wijzigen. De koper ziet dit direct in zijn overzicht.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('products.portfolio') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">
                    My portfolio
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $statusLabels = [
            'nieuw' => 'Nieuw',
            'in_productie' => 'In productie',
            'verzonden' => 'Verzonden',
            'geweigerd_terugbetaald' => 'Geweigerd, terugbetaling verzonden',
        ];

        $statusBadge = function (?string $status) {
            return match ($status) {
                'verzonden' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                'in_productie' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200',
                'geweigerd_terugbetaald' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
                default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100',
            };
        };
    @endphp

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 dark:border-green-900/40 bg-green-50 dark:bg-green-900/20 p-4 text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-xl border border-red-200 dark:border-red-900/40 bg-red-50 dark:bg-red-900/20 p-4 text-red-800 dark:text-red-200">
                    <p class="font-semibold">Check je invoer:</p>
                    <ul class="list-disc ml-5 mt-2 text-sm">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Bestellingen</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $orders->total() }} items</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr class="text-left">
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">Order</th>
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">Product</th>
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">Buyer</th>
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">Current status</th>
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">Update status</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($orders as $order)
                            <tr class="align-top hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                <td class="py-3 px-6 text-gray-900 dark:text-gray-100 font-medium">
                                    #{{ $order->id }}
                                    <div class="text-xs text-gray-500 dark:text-gray-400 font-normal">
                                        {{ $order->created_at->format('Y-m-d H:i') }}
                                    </div>
                                </td>

                                <td class="py-3 px-6">
                                    <div class="text-gray-900 dark:text-gray-100 font-medium">
                                        {{ $order->product?->name ?? 'Unknown product' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Product ID: {{ $order->product_id }}
                                    </div>
                                </td>

                                <td class="py-3 px-6 text-gray-700 dark:text-gray-200">
                                    {{ $order->buyer?->name ?? $order->buyer_id }}
                                </td>

                                <td class="py-3 px-6">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $statusBadge($order->status) }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>

                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        Note: {{ $order->status_note ?? '—' }}
                                    </div>
                                </td>

                                <td class="py-3 px-6">
                                    <form method="POST" action="{{ route('orders.status.update', $order) }}" class="space-y-2">
                                        @csrf
                                        @method('PATCH')

                                        <select name="status"
                                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="in_productie" @selected(old('status', $order->status) === 'in_productie')>in productie</option>
                                            <option value="verzonden" @selected(old('status', $order->status) === 'verzonden')>verzonden</option>
                                            <option value="geweigerd_terugbetaald" @selected(old('status', $order->status) === 'geweigerd_terugbetaald')>geweigerd, terugbetaling verzonden</option>
                                        </select>

                                        <input name="status_note"
                                               value="{{ old('status_note') }}"
                                               maxlength="255"
                                               placeholder="Korte statusbeschrijving (optioneel)"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" />

                                        <button class="w-full inline-flex items-center justify-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm">
                                            Update
                                        </button>

                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Tip: houd de note kort (max 255).
                                        </p>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 px-6 text-gray-600 dark:text-gray-300">
                                    Geen orders gevonden voor jouw producten.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 sm:p-6">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
