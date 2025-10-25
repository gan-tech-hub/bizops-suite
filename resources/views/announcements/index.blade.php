<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">過去のお知らせ</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($announcements->isEmpty())
                    <p>お知らせはありません。</p>
                @else
                    <ul class="space-y-4">
                        @foreach($announcements as $a)
                            <li class="border-b pb-3">
                                <a href="{{ route('announcements.show', $a->id) }}" class="text-lg font-medium text-blue-600 hover:underline">
                                    {{ $a->title }}
                                </a>
                                <div class="text-xs text-gray-500">
                                    {{ $a->created_at->timezone('Asia/Tokyo')->format('Y/m/d H:i') }}
                                    @if($a->user)
                                        — {{ $a->user->name }}
                                    @endif
                                </div>
                                <p class="mt-2 text-sm text-gray-700">{!! nl2br(e(Str::limit($a->body, 200))) !!}</p>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-4">
                        {{ $announcements->links() }}
                    </div>
                @endif
                <div class="mt-4">
                    <a href="{{ url()->previous() }}" class="text-sm text-blue-600 hover:underline">一覧へ戻る</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
