<x-app-layout>
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Notifications</h1>

            <div class="mt-6 space-y-3">
                @foreach($notifications as $n)
                    <div class="p-4 rounded border {{ $n->is_read ? 'opacity-60' : '' }}">
                        <div class="font-medium">{{ $n->message }}</div>
                        <div class="text-xs text-gray-500">{{ $n->created_at }}</div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
