{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ダッシュボード
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 挨拶 & 日時 -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl p-6">
                <h1 class="text-2xl font-bold mb-2">ようこそ、{{ Auth::user()->name }} さん！</h1>
                <p id="dashboard-date" class="text-lg font-semibold"></p>
                <p id="dashboard-time" class="text-2xl font-mono text-indigo-600 dark:text-indigo-300"></p>
            </div>

            <!-- 機能リンクカード -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <a href="{{ route('customers.index') }}" 
                   class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold mb-2">👤 顧客管理</h3>
                    <p class="text-gray-600 dark:text-gray-400">顧客情報の登録・編集・検索ができます</p>
                </a>

                <a href="{{ route('reservations.view') }}" 
                   class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold mb-2">📅 予約管理</h3>
                    <p class="text-gray-600 dark:text-gray-400">予約の登録・確認・編集ができます</p>
                </a>
            </div>

            <!-- お知らせ欄 -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md">
                <h3 class="text-lg font-semibold mb-2">📢 お知らせ</h3>
                <p class="text-gray-600 dark:text-gray-400">ここにシステムのお知らせを表示できます。</p>
            </div>
        </div>
    </div>

    <!-- 時計スクリプト -->
    <script>
        function updateDashboardDateTime() {
            const now = new Date();
            const days = ['日','月','火','水','木','金','土'];
            const dateStr = `${now.getFullYear()}年${now.getMonth()+1}月${now.getDate()}日(${days[now.getDay()]})`;
            const timeStr = now.toLocaleTimeString('ja-JP',{hour12:false});
            document.getElementById('dashboard-date').textContent = dateStr;
            document.getElementById('dashboard-time').textContent = timeStr;
        }
        updateDashboardDateTime();
        setInterval(updateDashboardDateTime,1000);
    </script>
</x-app-layout>
