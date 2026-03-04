<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">My Orders</h1>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="border-b text-left">
                        <th class="py-2">Order</th>
                        <th class="py-2">Product</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Note</th>
                        <th class="py-2">Created</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b">
                            <td class="py-2">#{{ $order->id }}</td>
                            <td class="py-2">{{ $order->product?->name }}</td>
                            <td class="py-2">{{ $order->status }}</td>
                            <td class="py-2">{{ $order->status_note }}</td>
                            <td class="py-2">{{ $order->created_at }}</td>
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
