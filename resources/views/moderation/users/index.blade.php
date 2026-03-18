<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
                Moderation — Users
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Zet account-verificatie aan/uit. Alleen moderators kunnen dit wijzigen.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 dark:border-green-900/40 bg-green-50 dark:bg-green-900/20 p-4 text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Accounts</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $users->total() }} users</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr class="text-left">
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">ID</th>
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">Name</th>
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">Email</th>
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">Role</th>
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">Verified</th>
                            <th class="py-3 px-6 font-medium text-gray-600 dark:text-gray-300">Action</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($users as $u)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                <td class="py-3 px-6 text-gray-700 dark:text-gray-200">#{{ $u->id }}</td>
                                <td class="py-3 px-6 text-gray-900 dark:text-gray-100 font-medium">{{ $u->name }}</td>
                                <td class="py-3 px-6 text-gray-700 dark:text-gray-200">{{ $u->email }}</td>
                                <td class="py-3 px-6 text-gray-700 dark:text-gray-200">{{ $u->role ?? '—' }}</td>

                                <td class="py-3 px-6">
                                    @if($u->verified)
                                        <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/40 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-200">
                                            Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-700 px-2.5 py-1 text-xs font-medium text-gray-800 dark:text-gray-100">
                                            Not verified
                                        </span>
                                    @endif
                                </td>

                                <td class="py-3 px-6">
                                    <form method="POST" action="{{ route('moderation.users.verify', $u) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')

                                        <input type="hidden" name="verified" value="{{ $u->verified ? 0 : 1 }}">

                                        <button class="inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-semibold
                                            {{ $u->verified
                                                ? 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700'
                                                : 'bg-indigo-600 text-white hover:bg-indigo-700'
                                            }}">
                                            {{ $u->verified ? 'Unverify' : 'Verify' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 sm:p-6">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
