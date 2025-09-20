<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            顧客詳細
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- 顧客情報 -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">顧客情報</h3>
                <p><strong>名前：</strong> {{ $customer->name }}</p>
                <p><strong>メール：</strong> {{ $customer->email }}</p>
                <p><strong>電話番号：</strong> {{ $customer->phone ?? '未登録' }}</p>
                <p><strong>住所：</strong> {{ $customer->address ?? '未登録' }}</p>
            </div>

            <!-- 予約一覧 -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">予約一覧</h3>

                @if($customer->reservations->isEmpty())
                    <p>予約はありません。</p>
                @else
                    <table class="table-fixed w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="w-1/7 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">タイトル</th>
                                <th class="w-1/7 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ラベル色</th>
                                <th class="w-1/7 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">開始</th>
                                <th class="w-1/7 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">終了</th>
                                <th class="w-1/7 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">場所</th>
                                <th class="w-1/7 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">担当者</th>
                                <th class="w-1/7 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">詳細</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($customer->reservations as $reservation)
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
                                    <td class="px-6 py-4">{{ $reservation->staff ?? '未設定' }}</td>
                                    <td class="px-6 py-4">{{ $reservation->description ?? '未設定' }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('reservations.edit', $reservation->id) }}" 
                                           class="text-blue-600 hover:underline">編集</a>
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
