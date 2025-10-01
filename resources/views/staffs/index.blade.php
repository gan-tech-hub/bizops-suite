<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            担当者管理
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- フラッシュメッセージ --}}
                @if (session('success'))
                    <div id="flash-message" 
                        class="text-green-800 bg-green-100 border border-green-300 px-4 py-2 rounded flex justify-between items-center transition-all duration-1000 ease-in-out overflow-hidden">
                        <span>{{ session('success') }}</span>
                        <span id="countdown" class="text-sm opacity-80 ml-4">5s</span>
                    </div>
                    <div id="spacer" class="p-2"></div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const flashMessage = document.getElementById("flash-message");
                            const countdownEl = document.getElementById("countdown");
                            const spacerEl = document.getElementById("spacer");

                            if (flashMessage && countdownEl) {
                                let seconds = 5; // 表示時間（秒）

                                const interval = setInterval(() => {
                                    seconds--;
                                    countdownEl.textContent = seconds + "s";

                                    if (seconds <= 0) {
                                        clearInterval(interval);

                                        // フェードアウト＋高さ縮小
                                        flashMessage.classList.add("opacity-0");

                                        setTimeout(() => {
                                            flashMessage.remove(); // 完全に削除
                                            spacerEl.remove();
                                        }, 1000); // duration と合わせる
                                    }
                                }, 1000);
                            }
                        });
                    </script>
                @endif

                <a href="{{ route('users.create') }}" 
                   class="mb-4 inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded">
                    ＋ 新規登録
                </a>

                <table class="min-w-full border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-center">ID</th>
                            <th class="border px-4 py-2 text-center">名前</th>
                            <th class="border px-4 py-2 text-center">権限</th>
                            <th class="border px-4 py-2 text-center">役職</th>
                            <th class="border px-4 py-2 text-center">メール</th>
                            <th class="border px-4 py-2 text-center">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staffs as $staff)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $staff->id }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('staffs.show', $staff) }}" class="text-blue-500 underline">
                                        {{ $staff->name }}
                                    </a>
                                </td>
                                <td class="border px-4 py-2">
                                    {{ $staff->role == 'admin' ? '管理者' : '一般' }}
                                </td>
                                <td class="border px-4 py-2">{{ $staff->position }}</td>
                                <td class="border px-4 py-2">{{ $staff->email }}</td>
                                <td class="border px-4 py-2 text-center space-x-2">
                                    <a href="{{ route('staffs.edit', $staff) }}" 
                                       class="text-blue-500 hover:underline">編集</a>
                                    <form action="{{ route('staffs.destroy', $staff) }}" method="POST" class="inline">
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
