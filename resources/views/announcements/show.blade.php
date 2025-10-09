<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ $announcement->title }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <div class="text-sm text-gray-500 mb-4">
                    {{ $announcement->created_at->timezone('Asia/Tokyo')->format('Y/m/d H:i') }}
                    @if($announcement->user)
                        — {{ $announcement->user->name }}
                    @endif
                </div>

                <div class="prose">
                    {!! nl2br(e($announcement->body)) !!}
                </div>

                <div class="mt-4">
                    <a href="{{ url()->previous() }}" class="text-sm text-blue-600 hover:underline">一覧へ戻る</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
