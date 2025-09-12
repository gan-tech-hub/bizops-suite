<x-app-layout>
    <x-slot name="header">
        <h2>予約編集</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('reservations.update', $reservation) }}">
                    @csrf
                    @method('PUT')

                    <!-- タイトル -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">タイトル</label>
                        <input type="text" name="title" 
                               value="{{ old('title', $reservation->title) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <!-- ラベル色 -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">ラベル色</label>
                        <input type="color" name="color" 
                               value="{{ old('color', $reservation->color) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <!-- 開始日時 -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">開始日時</label>
                        <input type="datetime-local" name="start" 
                               value="{{ old('start', $reservation->start->format('Y-m-d\TH:i')) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <!-- 終了日時 -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">終了日時</label>
                        <input type="datetime-local" name="end" 
                               value="{{ old('end', $reservation->end->format('Y-m-d\TH:i')) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <!-- 場所 -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">場所</label>
                        <input type="text" name="location" 
                               value="{{ old('location', $reservation->location) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <!-- 担当者 -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">担当者名</label>
                        <input type="text" name="staff" 
                               value="{{ old('staff', $reservation->staff) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <!-- 顧客名 -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">顧客名</label>
                        <input type="text" name="customer" 
                               value="{{ old('customer', $reservation->customer) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <!-- 詳細 -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">詳細</label>
                        <textarea name="description" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $reservation->description) }}</textarea>
                    </div>

                    <!-- ボタン群 -->
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">更新</button>
                        <a href="{{ route('reservations.view') }}" 
                           class="bg-gray-500 text-white px-4 py-2 rounded">戻る</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
