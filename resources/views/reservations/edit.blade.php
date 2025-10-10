<x-app-layout>
    <x-slot name="header">
        <h2>予約編集</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <!-- エラーメッセージ（Bladeのバリデーション用） -->
                @if ($errors->any())
                    <div class="text-red-800 bg-red-100 border border-red-300 px-4 py-2 rounded mb-3">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div id="editerrorMsg" class="text-red-800 bg-red-100 border border-red-300 px-4 py-2 rounded mb-3 hidden"></div>
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
                        <select name="staff_id" id="staff_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- 担当者を選択 --</option>
                            @foreach($staffs as $s)
                                <option value="{{ $s->id }}"
                                    {{ old('staff_id', $reservation->staff_id ?? '') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 顧客名 -->
                    <div class="mb-4">
                        <select name="customer_id" id="customer_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- 顧客を選択 --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}"
                                    {{ old('customer_id', $reservation->customer_id ?? '') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 詳細 -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">詳細</label>
                        <textarea name="description" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $reservation->description) }}</textarea>
                    </div>

                    <!-- ボタン群 -->
                    <div class="flex justify-end space-x-2">
                        <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">更新</button>
                        <a href="{{ url()->previous() }}" 
                           class="bg-gray-500 text-white px-4 py-2 rounded">キャンセル</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
