<x-app-layout>
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Notifications</h1>

            <div class="mt-6 space-y-3">
                @forelse($notifications as $n)
                    <div class="p-4 rounded border {{ $n->is_read ? 'opacity-60' : 'border-indigo-300 bg-indigo-50/40 dark:bg-indigo-900/10' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $n->message }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $n->created_at->format('Y-m-d H:i') }}
                                </div>
                            </div>

                            @if(!$n->is_read)
                                <form method="POST" action="{{ route('notifications.read', $n) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700">
                                        Mark as read
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        Je hebt nog geen notificaties.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
