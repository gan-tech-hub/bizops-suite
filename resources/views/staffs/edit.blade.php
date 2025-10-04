<!-- resources/views/staffs/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            担当者編集
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
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
                <form action="{{ route('staffs.update', $user->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">名前</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border px-3 py-2 rounded mt-1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">メール</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border px-3 py-2 rounded mt-1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">役職</label>
                        <input type="text" name="position" value="{{ old('position', $user->position) }}" class="w-full border px-3 py-2 rounded mt-1">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">権限</label>
                        <select name="role" id="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="general" {{ old('role', $user->role) === 'general' ? 'selected' : '' }}>一般</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>管理者</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                            更新
                        </button>
                        <a href="{{ url()->previous() }}" class="bg-gray-500 text-white px-4 py-2 rounded">
                            キャンセル
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
