<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                    Inkomende bestellingen
                </h1>
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    Alleen jouw producten
                </div>
            </div>
            @if(session('success'))
                <div class="mt-4 p-3 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mt-4 p-3 bg-red-100 text-red-800 rounded">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="border-b text-left">
                        <th class="py-2 text-gray-700 dark:text-gray-300">Order</th>
                        <th class="py-2 text-gray-700 dark:text-gray-300">Product</th>
                        <th class="py-2 text-gray-700 dark:text-gray-300">Buyer</th>
                        <th class="py-2 text-gray-700 dark:text-gray-300">Status</th>
                        <th class="py-2 text-gray-700 dark:text-gray-300">Notitie</th>
                        <th class="py-2 text-gray-700 dark:text-gray-300">Actie</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b align-top">
                            <td class="py-2 text-gray-700 dark:text-gray-300">#{{ $order->id }}</td>
                            <td class="py-2 text-gray-700 dark:text-gray-300">{{ $order->product?->name }}</td>
                            <td class="py-2 text-gray-700 dark:text-gray-300">{{ $order->buyer?->name }}</td>
                            <td class="py-2 text-gray-700 dark:text-gray-300">
                                <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-2">{{ $order->status_note }}</td>

                            <td class="py-2">
                                <form method="POST"
                                      action="{{ route('maker.orders.status', $order) }}"
                                      class="flex flex-col gap-2 w-72"
                                      onsubmit="return confirm('Weet je zeker dat je deze wijziging wilt uitvoeren?');">
                                    @csrf
                                    @method('PATCH')

                                    <select name="status"
                                            class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                        <option value="nieuw" @selected($order->status === 'nieuw')>nieuw</option>
                                        <option value="in_productie" @selected($order->status === 'in_productie')>in productie</option>
                                        <option value="verzonden" @selected($order->status === 'verzonden')>verzonden</option>
                                        <option value="geweigerd_terugbetaald" @selected($order->status === 'geweigerd_terugbetaald')>
                                            geweigerd, terugbetaling verzonden
                                        </option>
                                    </select>

                                    <input name="status_note"
                                           value="{{ old('status_note') }}"
                                           placeholder="Optionele reden (max 255)"
                                           class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" />

                                    <button class="px-3 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                        Update status
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
