<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Users verificatie
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800 text-left">
                        <th class="py-2 text-gray-700 dark:text-gray-300">Naam</th>
                        <th class="py-2 text-gray-700 dark:text-gray-300">Email</th>
                        <th class="py-2 text-gray-700 dark:text-gray-300">Verified</th>
                        <th class="py-2 text-gray-700 dark:text-gray-300">Actie</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $u)
                        <tr class="border-b border-gray-200 dark:border-gray-800">
                            <td class="py-2 text-gray-700 dark:text-gray-300">{{ $u->name }}</td>
                            <td class="py-2 text-gray-700 dark:text-gray-300">{{ $u->email }}</td>
                            <td class="py-2 text-gray-700 dark:text-gray-300">{{ $u->verified ? 'Ja' : 'Nee' }}</td>
                            <td class="py-2 text-gray-700 dark:text-gray-300">
                                <form method="POST" action="{{ route('admin.users.verified', $u) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="verified" value="{{ $u->verified ? 0 : 1 }}">
                                    <button class="px-3 py-2 rounded bg-indigo-600 text-white">
                                        {{ $u->verified ? 'Unverify' : 'Verify' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
