<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            データ管理
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div id="spacer" class="hidden mb-2"></div>
        <div id="flash-message" 
            class="hidden text-green-800 bg-green-100 border border-green-300 px-4 py-2 rounded flex justify-between items-center transition-all duration-1000 ease-in-out overflow-hidden">
            <span>ダウンロードしました</span>
            <span id="countdown" class="text-sm opacity-80 ml-4">3s</span>
        </div>

        @foreach (['customers' => '顧客情報', 'reservations' => '予約情報', 'staffs' => '担当者情報', 'all' => '全データ'] as $type => $label)
            <div class="bg-white p-4 shadow rounded">
                <h3 class="font-semibold mb-2">{{ $label }}</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('data.export', [$type, 'csv']) }}" 
                    class="download-btn px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" 
                    data-type="csv">CSV</a>
                    <a href="{{ route('data.export', [$type, 'pdf']) }}" 
                    class="download-btn px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600" 
                    data-type="pdf">PDF</a>
                </div>
            </div>
        @endforeach

        <script>
        document.querySelectorAll('.download-btn').forEach(btn => {
            btn.addEventListener('click', function(e){
                e.preventDefault(); // ページ遷移を一旦止める

                const flashMessage = document.getElementById('flash-message');
                const countdownEl = document.getElementById('countdown');
                const spacerEl = document.getElementById('spacer');

                flashMessage.classList.remove('hidden', 'opacity-0');
                spacerEl.classList.remove('hidden', 'opacity-0');

                let seconds = 3;
                countdownEl.textContent = seconds + "s";

                const interval = setInterval(() => {
                    seconds--;
                    countdownEl.textContent = seconds + "s";

                    if (seconds <= 0) {
                        clearInterval(interval);
                        flashMessage.classList.add('opacity-0');

                        setTimeout(() => {
                            flashMessage.classList.add('hidden')
                            spacerEl.classList.add('hidden')
                        }, 1000);
                    }
                }, 1000);

                // ダウンロードを開始
                window.location.href = btn.href;
            });
        });
        </script>
    </div>
</x-app-layout>
