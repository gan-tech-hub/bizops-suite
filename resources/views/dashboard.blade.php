{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ダッシュボード
        </h2>
    </x-slot>

    <div class="py-6">
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
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-lg transition relative">
                    <h3 class="text-xl font-semibold mb-2">👤 顧客管理</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">
                        顧客情報の登録・編集ができます
                    </p>
                    <p class="text-sm text-gray-500">
                        担当顧客数：<span class="font-semibold text-indigo-600">{{ $customerCount }}</span> 件
                    </p>
                </a>

                <a href="{{ route('reservations.view') }}" 
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-lg transition relative">
                    <h3 class="text-xl font-semibold mb-2">📅 予約管理</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">
                        予約の登録・確認・編集ができます
                    </p>
                    <p class="text-sm text-gray-500">
                        本日の予約数：<span class="font-semibold text-indigo-600">{{ $todayReservationCount }}</span> 件
                    </p>
                </a>
            </div>

            {{-- フラッシュメッセージ --}}
            @if (session('success'))
                <div id="flash-message" 
                    class="text-green-800 bg-green-100 border border-green-300 px-4 py-2 rounded flex justify-between items-center transition-all duration-1000 ease-in-out overflow-hidden">
                    <span>{{ session('success') }}</span>
                    <span id="countdown" class="text-sm opacity-80 ml-4">5s</span>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const flashMessage = document.getElementById("flash-message");
                        const countdownEl = document.getElementById("countdown");

                        if (flashMessage && countdownEl) {
                            let seconds = 5; // 表示時間（秒）

                            const interval = setInterval(() => {
                                seconds--;
                                countdownEl.textContent = seconds + "s";

                                if (seconds <= 0) {
                                    clearInterval(interval);

                                    // フェードアウト＋高さ縮小
                                    flashMessage.classList.add("opacity-0");

                                    setTimeout(() => {
                                        flashMessage.remove(); // 完全に削除
                                    }, 1000); // duration と合わせる
                                }
                            }, 1000);
                        }
                    });
                </script>
            @endif

            <!-- お知らせ欄 -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium">📢 お知らせ</h3>

                    <!-- 管理者なら投稿ボタン表示 -->
                    @can('admin')
                        <button id="openAnnouncementModal" class="px-3 py-1 bg-blue-600 text-white rounded">
                            お知らせ投稿
                        </button>
                    @endcan
                </div>

                <!-- 最新10件 -->
                @if($announcements->isEmpty())
                    <p class="text-sm text-gray-500">まだお知らせはありません。</p>
                @else
                    <ul class="space-y-2">
                        @foreach($announcements as $a)
                            <li class="flex justify-between items-start">
                                <div>
                                    <a href="{{ route('announcements.show', $a->id) }}" class="text-sm font-medium text-gray-800 hover:underline">
                                        {{ Str::limit($a->title, 60) }}
                                    </a>
                                    <div class="text-xs text-gray-500">{{ $a->created_at->timezone('Asia/Tokyo')->format('Y/m/d H:i') }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="mt-4">
                    <a href="{{ route('announcements.index') }}" class="text-sm text-blue-600 hover:underline">すべて見る</a>
                </div>
            </div>

            <!-- 投稿モーダル（管理者用） -->
            <div id="announcementModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
                    <h2 class="text-lg font-bold mb-4">お知らせを投稿</h2>
                    <div id="errorMsg" class="text-red-800 bg-red-100 border border-red-300 px-4 py-2 rounded mb-3 hidden"></div>
                    <form id="announcementForm" method="POST" action="{{ route('announcements.store') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label class="block text-sm font-medium">タイトル</label>
                            <input type="text" id="title" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium">本文</label>
                            <textarea name="body" id="body" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                        </div>

                        <div class="flex justify-end gap-2 mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">投稿</button>
                            <button type="button" id="closeAnnouncementModal" class="px-4 py-2 bg-gray-400 text-white rounded">キャンセル</button>
                        </div>
                    </form>
                    <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        try {
                            // 1) まず要素を安全に取得する（idがなければ fallback で探す）
                            const modal = document.getElementById('announcementModal'); // optional
                            const form = document.getElementById('announcementForm') || (modal ? modal.querySelector('form') : document.querySelector('form[action$="/announcements"]'));
                            if (!form) {
                                console.warn('Announcement form not found. Make sure form has id="announcementForm" or exists in #announcementModal.');
                                return;
                            }

                            const titleEl = document.getElementById('title') || form.querySelector('input[name="title"]');
                            const bodyEl  = document.getElementById('body')  || form.querySelector('textarea[name="body"]');
                            const errorMsgEl = document.getElementById('errorMsg') || form.querySelector('#errorMsg');

                            if (!titleEl || !bodyEl) {
                                console.warn('Title or body input not found inside the announcement form.');
                                return;
                            }
                            if (!errorMsgEl) {
                                console.warn('Error message container (#errorMsg) not found. Creating one above the form.');
                                // create a container if missing
                                const div = document.createElement('div');
                                div.id = 'errorMsg';
                                div.className = 'text-red-800 bg-red-100 border border-red-300 px-4 py-2 rounded mb-3 hidden';
                                form.insertAdjacentElement('beforebegin', div);
                            }

                            // optional: prevent double submit
                            let submitting = false;

                            form.addEventListener('submit', (e) => {
                                e.preventDefault();

                                if (submitting) return;
                                submitting = true;

                                const errors = [];

                                const title = titleEl.value ? titleEl.value.trim() : '';
                                const body  = bodyEl.value ? bodyEl.value.trim() : '';

                                // ここでルールを入れる（サーバ側ルールと整合）
                                if (!title) {
                                    errors.push('タイトルは必須です。');
                                } else if (title.length > 100) {
                                    errors.push('タイトルは100文字以内で入力してください。');
                                }

                                if (!body) {
                                    errors.push('本文は必須です。');
                                } else if (body.length > 1000) {
                                    errors.push('本文は1000文字以内で入力してください。');
                                }

                                // エラーがあれば表示して中断
                                const errEl = document.getElementById('errorMsg');
                                if (errors.length > 0) {
                                    errEl.innerHTML = `
                                        <ul class="list-disc list-inside text-sm">
                                            ${errors.map(msg => `<li>${msg}</li>`).join('')}
                                        </ul>
                                    `;
                                    errEl.classList.remove('hidden');
                                    // フォーカスは最初のエラー箇所へ
                                    if (!title) titleEl.focus();
                                    else bodyEl.focus();
                                    submitting = false;
                                    return;
                                }

                                // エラーなし → 非表示にしてフォーム送信
                                errEl.classList.add('hidden');
                                errEl.innerHTML = '';

                                // 安全に submit を行う（他のsubmitハンドラがある場合は dispatchEvent を使う選択肢もある）
                                // ここではネイティブ submit() を使います
                                form.submit();
                            });
                        } catch (err) {
                            console.error('Announcement form script error:', err);
                        }
                    });
                    </script>
                </div>
            </div>

            <script>
            document.getElementById('openAnnouncementModal')?.addEventListener('click', () => {
                document.getElementById('announcementModal').classList.remove('hidden');
            });
            document.getElementById('closeAnnouncementModal')?.addEventListener('click', () => {
                document.getElementById('announcementModal').classList.add('hidden');
            });
            </script>
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
