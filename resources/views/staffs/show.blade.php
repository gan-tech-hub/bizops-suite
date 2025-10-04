<!-- resources/views/staffs/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            担当者詳細
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 担当者情報 -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">担当者情報</h3>
                <p><strong>ID：</strong> {{ $user->id }}</p>
                <p><strong>名前：</strong> {{ $user->name }}</p>
                <p><strong>メール：</strong> {{ $user->email }}</p>
                <p><strong>役職：</strong> {{ $user->position ?? '未設定' }}</p>
                <p><strong>権限：</strong> {{ $user->role == 'admin' ? '管理者' : '一般' }}</p>
            </div>

            {{-- フラッシュメッセージ --}}
            @if (session('success'))
                <div id="flash-message" 
                    class="text-green-800 bg-green-100 border border-green-300 px-4 py-2 rounded flex justify-between items-center transition-all duration-1000 ease-in-out overflow-hidden">
                    <span>{{ session('success') }}</span>
                    <span id="countdown" class="text-sm opacity-80 ml-4">5s</span>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const flashMessage = document.getElementById("flash-message");
                        const countdownEl = document.getElementById("countdown");

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
                                    }, 1000); // duration と合わせる
                                }
                            }, 1000);
                        }
                    });
                </script>
            @endif
            
            <!-- 顧客一覧 -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">担当顧客</h3>

                @if($user->customers->isEmpty())
                    <p>担当顧客は登録されていません。</p>
                @else
                    <table class="table-fixed w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">名前</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">メール</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">電話番号</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">住所</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">操作</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($user->customers as $customer)
                                <tr>
                                    <td class="px-6 py-4">{{ $customer->id }}</td>
                                    <td class="px-6 py-4">{{ $customer->name }}</td>
                                    <td class="px-6 py-4">{{ $customer->email ?? '未登録' }}</td>
                                    <td class="px-6 py-4">{{ $customer->phone ?? '未登録' }}</td>
                                    <td class="px-6 py-4">{{ $customer->address ?? '未登録' }}</td>
                                    <td class="px-6 py-4 text-center space-x-2">
                                        <a href="{{ route('customers.edit', $customer->id) }}" class="text-blue-500 hover:underline">編集</a>
                                        <form action="{{ route('customers.destroy', $customer->id) }}" 
                                            method="POST" 
                                            onsubmit="return confirm('本当に削除しますか？');"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                                            <button type="submit" class="text-red-500 hover:underline">
                                                削除
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- 予約一覧 -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">担当予約</h3>

                @if($user->reservations->isEmpty())
                    <p>担当予約は登録されていません。</p>
                @else
                    <table class="table-fixed w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">タイトル</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ラベル色</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">開始日時</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">終了日時</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">場所</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">顧客名</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">詳細</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">操作</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($user->reservations as $reservation)
                                <tr>
                                    <td class="px-6 py-4">{{ $reservation->title }}</td>
                                    <td class="px-6 py-4">
                                        <p class="flex items-center gap-2">
                                            <span class="rounded-md border border-gray-300 shadow-sm w-5 h-5" style="background-color: {{ $reservation->color }};"></span>
                                            {{ $reservation->color }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">{{ $reservation->start }}</td>
                                    <td class="px-6 py-4">{{ $reservation->end }}</td>
                                    <td class="px-6 py-4">{{ $reservation->location }}</td>
                                    <td class="px-6 py-4">{{ $reservation->customer ? $reservation->customer->name : '未設定' }}</td>
                                    <td class="px-6 py-4">{{ $reservation->description }}</td>
                                    <td class="px-6 py-4 text-center space-x-2">
                                        <a href="{{ route('reservations.edit', $reservation->id) }}" class="text-blue-500 hover:underline">編集</a>
                                        <form action="{{ route('reservations.destroy', $reservation->id) }}" 
                                            method="POST" 
                                            onsubmit="return confirm('本当に削除しますか？');"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                                            <button type="submit" class="text-red-500 hover:underline">
                                                削除
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
