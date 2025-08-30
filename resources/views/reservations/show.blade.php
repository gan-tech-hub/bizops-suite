<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">予約詳細</h2>
    <form method="POST" action="{{ route('reservations.update', $reservation->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1">タイトル</label>
            <input type="text" name="title" value="{{ $reservation->title }}" class="border rounded w-full p-2">
        </div>
        <div class="mb-4">
            <label class="block mb-1">開始日時</label>
            <input type="datetime-local" name="start_datetime"
                value="{{ \Carbon\Carbon::parse($reservation->start_datetime)->format('Y-m-d\TH:i') }}"
                class="border rounded w-full p-2">
        </div>
        <div class="mb-4">
            <label class="block mb-1">終了日時</label>
            <input type="datetime-local" name="end_datetime"
                value="{{ \Carbon\Carbon::parse($reservation->end_datetime)->format('Y-m-d\TH:i') }}"
                class="border rounded w-full p-2">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">更新</button>
        </div>
    </form>
    <form method="POST" action="{{ route('reservations.destroy', $reservation->id) }}" class="mt-4">
        @csrf
        @method('DELETE')
        <button class="bg-red-500 text-white px-4 py-2 rounded">削除</button>
    </form>
</div>
