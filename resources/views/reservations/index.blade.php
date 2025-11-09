<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            予約管理
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    @if(session('success'))
                        <div id="flash-message" 
                            class="text-green-800 bg-green-100 border border-green-300 px-4 py-2 rounded flex justify-between items-center transition-all duration-1000 ease-in-out overflow-hidden">
                            <span>{{ session('success') }}</span>
                            <span id="countdown" class="text-sm opacity-80 ml-4">5s</span>
                        </div>
                        <div id="spacer" class="p-2"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const flashMessage = document.getElementById("flash-message");
                                const countdownEl = document.getElementById("countdown");
                                const spacerEl = document.getElementById("spacer");

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
                                                spacerEl.remove();
                                            }, 1000); // duration と合わせる
                                        }
                                    }, 1000);
                                }
                            });
                        </script>
                    @endif
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
    {{-- ✅ ViteでビルドしたJSを読み込む --}}
    @vite(['resources/js/app.js'])
    <!-- Reservation Modal -->
    <div id="reservationModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-96 p-6">
            <h2 id="modalTitle" class="text-lg font-semibold mb-4"></h2>
            <p><span id="modalColor"></span></p>
            <p><strong>開始:</strong> <span id="modalStart"></span></p>
            <p><strong>終了:</strong> <span id="modalEnd"></span></p>
            <p><strong>場所:</strong> <span id="modalLocation"></span></p>
            <p><strong>担当者名:</strong> <span id="modalStaff"></span></p>
            <p><strong>顧客名:</strong> <span id="modalCustomer"></span></p>
            <p><strong>詳細:</strong> <span id="modalDescription"></span></p>
            <div class="mt-4 flex justify-between">
                <button id="editEventBtn" class="bg-blue-500 text-white px-4 py-2 rounded">編集</button>
                <button id="deleteEventBtn" class="bg-red-500 text-white px-4 py-2 rounded">削除</button>
                <button id="modalClose" class="bg-gray-500 text-white px-4 py-2 rounded">閉じる</button>
            </div>
        </div>
    </div>
    <!-- モーダル (新規予約追加) -->
    <div id="createReservationModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
            <h2 class="text-lg font-bold mb-4">予約登録</h2>
            <div id="errorMsg" class="text-red-800 bg-red-100 border border-red-300 px-4 py-2 rounded mb-3 hidden"></div>
            <form id="createReservationForm" novalidate>
                <div class="mb-2">
                    <label class="block text-sm font-medium">タイトル</label>
                    <input type="text" name="title" class="w-full border rounded p-2">
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium">ラベル色</label>
                    <input type="color" name="color" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="#3788d8">
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium">開始</label>
                    <input type="datetime-local" name="start" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium">終了</label>
                    <input type="datetime-local" name="end" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium">場所</label>
                    <input type="text" name="location" class="w-full border rounded p-2">
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium">担当者</label>
                    <select name="staff_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- 担当者を選択 --</option>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}">
                                {{ $staff->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">顧客</label>
                    <select name="customer_id" id="customer_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- 顧客を選択 --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium">詳細</label>
                    <textarea name="description" class="w-full border rounded p-2"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">登録</button>
                    <button type="button" id="createCancelBtn" class="bg-gray-500 text-white px-4 py-2 rounded">キャンセル</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
