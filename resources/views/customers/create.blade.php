<!-- resources/views/customers/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            顧客作成
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('customers.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">名前</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border px-3 py-2 rounded mt-1" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">メール</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border px-3 py-2 rounded mt-1" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">電話</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border px-3 py-2 rounded mt-1">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">住所</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="w-full border px-3 py-2 rounded mt-1">
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">担当者</label>
                        <select name="staff_id" id="staff_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- 担当者を選択 --</option>
                            @foreach($staffs as $staff)
                                <option value="{{ $staff->id }}" {{ old('staff_id', $customer->staff_id ?? '') == $staff->id ? 'selected' : '' }}>
                                    {{ $staff->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('customers.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                            キャンセル
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            作成
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
