<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
                Moderatie — zoeken in teksten
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Zoek in productbeschrijvingen en reviews om ongepaste taal te vinden.
            </p>
        </div>
    </x-slot>

    @php
        $snippet = function (?string $text, string $q) {
            $text = (string) $text;
            $q = (string) $q;

            if ($q === '' || $text === '') return mb_substr($text, 0, 160);

            $pos = mb_stripos($text, $q);
            if ($pos === false) return mb_substr($text, 0, 160);

            $start = max(0, $pos - 60);
            $part = mb_substr($text, $start, 180);

            return ($start > 0 ? '…' : '') . $part . (mb_strlen($text) > ($start + 180) ? '…' : '');
        };
    @endphp

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl p-4 sm:p-6">
                <form method="GET" class="flex flex-col sm:flex-row gap-3 sm:items-center">
                    <input name="q" value="{{ $q }}"
                           placeholder="Zoekwoord… (bijv. scheldwoord)"
                           class="w-full sm:w-96 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" />
                    <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                        Zoek
                    </button>
                </form>
            </div>

            @if($q !== '')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Productbeschrijvingen --}}
                    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                        <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                Productbeschrijvingen ({{ $productResults->count() }})
                            </h3>
                        </div>

                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($productResults as $p)
                                <div class="p-4 sm:p-6">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">
                                            #{{ $p->id }} — {{ $p->name }}
                                        </div>

                                        <a href="{{ route('products.show', $p) }}"
                                           class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                            Bekijk product
                                        </a>
                                    </div>

                                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $snippet($p->description, $q) }}
                                    </p>
                                </div>
                            @empty
                                <div class="p-4 sm:p-6 text-sm text-gray-600 dark:text-gray-300">
                                    Geen matches in productbeschrijvingen.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Reviews --}}
                    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                        <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                Reviews ({{ $reviewResults->count() }})
                            </h3>
                        </div>

                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($reviewResults as $r)
                                @php
                                    $product = $r->order?->product;
                                    $buyer = $r->order?->buyer;
                                @endphp

                                <div class="p-4 sm:p-6">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">
                                            Review #{{ $r->id }}
                                            <span class="text-xs font-normal text-gray-500 dark:text-gray-400">
                                                (rating: {{ $r->rating }})
                                            </span>
                                        </div>

                                        @if($product)
                                            <a href="{{ route('products.show', $product) }}"
                                               class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                                Bekijk product
                                            </a>
                                        @endif
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Product: {{ $product?->name ?? ($r->order?->product_id ?? 'Unknown') }}
                                        — Buyer: {{ $buyer?->name ?? ($r->order?->buyer_id ?? 'Unknown') }}
                                        — Created: {{ optional($r->created_at)->format('Y-m-d H:i') }}
                                    </div>

                                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $snippet($r->comment, $q) }}
                                    </p>
                                </div>
                            @empty
                                <div class="p-4 sm:p-6 text-sm text-gray-600 dark:text-gray-300">
                                    Geen matches in reviews.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            @else
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    Vul een zoekwoord in om resultaten te zien.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
