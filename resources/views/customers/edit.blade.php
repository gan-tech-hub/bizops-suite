<!-- resources/views/customers/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            顧客編集
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('customers.update', $customer->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">名前</label>
                        <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="w-full border px-3 py-2 rounded mt-1" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">メール</label>
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full border px-3 py-2 rounded mt-1" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">電話</label>
                        <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full border px-3 py-2 rounded mt-1">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">住所</label>
                        <input type="text" name="address" value="{{ old('address', $customer->address) }}" class="w-full border px-3 py-2 rounded mt-1">
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('customers.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                            キャンセル
                        </a>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            更新
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
