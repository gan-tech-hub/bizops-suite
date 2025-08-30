<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ログイン前 --}}
            @guest
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">
                        顧客と予約を、かんたんに管理。
                    </h1>
                    <p class="mb-6 text-gray-600 dark:text-gray-400">
                        このアプリでは、顧客情報・予約管理・日報管理を一元化できます。
                    </p>
                    <div class="flex space-x-4">
                        <a href="{{ route('register') }}" 
                           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            新規登録
                        </a>
                        <a href="{{ route('login') }}" 
                           class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                            ログイン
                        </a>
                    </div>
                </div>
            @endguest

            {{-- ログイン後 --}}
            @auth
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6">ようこそ、{{ Auth::user()->name }} さん！</h1>

                    <!-- 日付と時計カード -->
                    <div class="bg-white dark:bg-gray-700 shadow-md rounded-2xl p-6 max-w-md mb-6">
                        <p id="today-date" class="text-lg font-semibold text-gray-700 dark:text-gray-200"></p>
                        <p id="current-time" class="text-3xl font-mono mt-2 text-indigo-600 dark:text-indigo-300"></p>
                    </div>

                    <p class="text-gray-700 dark:text-gray-300">
                        ここはログイン後のTOPページです。アプリの使い方やお知らせを表示する場所にできます。
                    </p>
                </div>

                <!-- 時計スクリプト -->
                <script>
                    function updateDateTime() {
                        const now = new Date();

                        // 曜日リスト
                        const days = ['日', '月', '火', '水', '木', '金', '土'];

                        // 日付 (YYYY年MM月DD日(曜))
                        const dateStr = `${now.getFullYear()}年${now.getMonth() + 1}月${now.getDate()}日(${days[now.getDay()]})`;

                        // 時刻 (hh:mm:ss)
                        const timeStr = now.toLocaleTimeString('ja-JP', { hour12: false });

                        // 表示反映
                        document.getElementById('today-date').textContent = dateStr;
                        document.getElementById('current-time').textContent = timeStr;
                    }

                    // 初期表示 & 1秒ごと更新
                    updateDateTime();
                    setInterval(updateDateTime, 1000);
                </script>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">顧客数</h2>
                        <p class="text-2xl font-bold mt-2 text-gray-900 dark:text-white">123</p>
                    </div>

                    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">本日の予約</h2>
                        <p class="text-2xl font-bold mt-2 text-gray-900 dark:text-white">5件</p>
                    </div>
                </div>

                <div class="mt-8 flex space-x-4">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        ダッシュボードへ
                    </a>
                    <a href="#" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                        顧客一覧へ
                    </a>
                    <a href="#" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                        予約登録へ
                    </a>
                </div>
            @endauth

        </div>
    </div>
</x-app-layout>
