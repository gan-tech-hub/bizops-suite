<!-- resources/views/users/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            担当者新規登録
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">名前</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border px-3 py-2 rounded mt-1">
                        @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">権限</label>
                        <select name="role" class="w-full border px-3 py-2 rounded mt-1">
                            <option value="general">一般</option>
                            <option value="admin">管理者</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">役職</label>
                        <input type="text" name="position" value="{{ old('position') }}" class="w-full border px-3 py-2 rounded mt-1">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">メールアドレス</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border px-3 py-2 rounded mt-1">
                        @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">パスワード</label>
                        <input type="password" name="password" class="w-full border px-3 py-2 rounded mt-1">
                        @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">パスワード（確認）</label>
                        <input type="password" name="password_confirmation" class="w-full border px-3 py-2 rounded mt-1">
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                            登録
                        </button>
                        <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">
                            キャンセル
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
