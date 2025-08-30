<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            顧客一覧
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- フラッシュメッセージ --}}
                @if (session('success'))
                    <div class="mb-4 p-4 text-green-800 bg-green-100 border border-green-300 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('customers.create') }}" 
                   class="mb-4 inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded">
                    ＋ 新規登録
                </a>

                <table class="min-w-full border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">ID</th>
                            <th class="border px-4 py-2 text-left">名前</th>
                            <th class="border px-4 py-2 text-left">メール</th>
                            <th class="border px-4 py-2 text-left">電話</th>
                            <th class="border px-4 py-2 text-left">住所</th>
                            <th class="border px-4 py-2 text-center">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $customer->id }}</td>
                                <td class="border px-4 py-2">{{ $customer->name }}</td>
                                <td class="border px-4 py-2">{{ $customer->email }}</td>
                                <td class="border px-4 py-2">{{ $customer->phone }}</td>
                                <td class="border px-4 py-2">{{ $customer->address }}</td>
                                <td class="border px-4 py-2 text-center space-x-2">
                                    <a href="{{ route('customers.edit', $customer) }}" 
                                       class="text-blue-500 hover:underline">編集</a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline"
                                                onclick="return confirm('本当に削除しますか？')">
                                            削除
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
