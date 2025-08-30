<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ユーザー編集
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block">名前</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border p-2">
                </div>

                <div class="mb-4">
                    <label class="block">メールアドレス</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border p-2">
                </div>

                <div class="mb-4">
                    <label class="block">役職</label>
                    <input type="text" name="position" value="{{ old('position', $user->position) }}" class="w-full border p-2">
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">更新</button>
            </form>
        </div>
    </div>
</x-app-layout>
