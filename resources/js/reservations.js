import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

// helpers
function formatDateForInput(date) {
    if (!date) return '';
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

function formatDateForDisplay(date) {
    if (!date) return '';
    const year = date.getUTCFullYear();
    const month = String(date.getUTCMonth() + 1).padStart(2, '0');
    const day = String(date.getUTCDate()).padStart(2, '0');
    const hours = String(date.getUTCHours()).padStart(2, '0');
    const minutes = String(date.getUTCMinutes()).padStart(2, '0');
    return `${year}/${month}/${day} ${hours}:${minutes}`;
}

/**
 * 動的に form を作って submit するユーティリティ
 * action: URL, method: POST/PUT/DELETE/GET (GET rarely used), data: object (key:value)
 */
function submitForm(action, method = 'POST', data = {}) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = action;
    form.style.display = 'none';

    // CSRF
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const inputToken = document.createElement('input');
    inputToken.type = 'hidden';
    inputToken.name = '_token';
    inputToken.value = token;
    form.appendChild(inputToken);

    if (method !== 'POST') {
        const m = document.createElement('input');
        m.type = 'hidden';
        m.name = '_method';
        m.value = method;
        form.appendChild(m);
    }

    Object.entries(data).forEach(([k, v]) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = k;
        input.value = v ?? '';
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}

document.addEventListener('DOMContentLoaded', function() {
    let currentEvent = null;
    const calendarEl = document.getElementById('calendar');

    const calendar = new Calendar(calendarEl, {
        timeZone: 'Asia/Tokyo',
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        selectable: true,
        editable: true,
        locale: 'ja',

        // イベント表示の時間フォーマット
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },

        // events: APIで取得（読み取りはJSONで）
        events: async function(fetchInfo, successCallback, failureCallback) {
            try {
                const response = await fetch('/api/reservations');
                const data = await response.json();
                const events = data.map(event => ({
                    id: event.id,
                    title: event.title,
                    color: event.color || '#3788d8',
                    start: event.start,
                    end: event.end,
                    location: event.location || '',
                    staff_id: event.staff_id || '',
                    staff_name: event.staff_name || '',
                    customer_name: event.customer_name || '',
                    description: event.description || '',
                    customer_id: event.customer_id || null,
                }));
                successCallback(events);
            } catch (error) {
                console.error("イベント取得エラー:", error);
                failureCallback(error);
            }
        },

        // 日付範囲選択（新規モーダル表示）
        select: function(info) {
            const modal = document.getElementById('createReservationModal');
            const form = document.getElementById('createReservationForm');
            const cancelBtn = document.getElementById('createCancelBtn');
            const errorMsgEl = document.getElementById('errorMsg');

            // フォーム要素を明示的に取得
            const titleEl = form.querySelector('input[name="title"]');
            const colorEl = form.querySelector('input[name="color"]');
            const startEl = form.querySelector('input[name="start"]');
            const endEl = form.querySelector('input[name="end"]');
            const locationEl = form.querySelector('input[name="location"]');
            const staffIDEl = form.querySelector('select[name="staff_id"]');
            const customerIdEl = form.querySelector('select[name="customer_id"]')
            const descriptionEl = form.querySelector('textarea[name="description"]');

            startEl.value = formatDateForInput(info.start);
            const enddate = new Date(info.end.getTime() - 60 * 60 * 1000 * 24);
            endEl.value = formatDateForInput(enddate);

            modal.classList.remove('hidden');

            cancelBtn.onclick = () => {
                errorMsgEl.classList.add('hidden');
                modal.classList.add('hidden');
                form.reset();
            };

            // submit は動的 form で送る（リダイレクト + flash を狙う）
            form.onsubmit = (e) => {
                e.preventDefault();
                
                const title = titleEl.value.trim();
                const start = new Date(startEl.value);
                const end = new Date(endEl.value);

                let errors = [];

                // バリデーション
                if (!title) {
                    errors.push("タイトルは必須です。");
                }

                if (start >= end) {
                    errors.push("終了日時は開始日時より後に設定してください。");
                }

                // エラー表示処理
                if (errors.length > 0) {
                    errorMsgEl.innerHTML = `
                        <ul class="list-disc list-inside text-sm">
                            ${errors.map(msg => `<li>${msg}</li>`).join("")}
                        </ul>
                    `;
                    errorMsgEl.classList.remove('hidden');
                    return;
                } else {
                    errorMsgEl.classList.add('hidden');
                    errorMsgEl.innerHTML = "";
                }

                errorMsgEl.classList.add('hidden');

                // 必要フィールドを集める
                const payload = {
                    title: titleEl.value,
                    color: colorEl.value,
                    start: startEl.value,
                    end: endEl.value,
                    location: locationEl.value,
                    staff_id: staffIDEl.value,
                    customer_id: customerIdEl ? customerIdEl.value : '',
                    description: descriptionEl.value,
                };
                submitForm('/reservations', 'POST', payload);
            };

            calendar.unselect();
        },

        // イベントクリック（モーダル）
        eventClick: function(info) {
            currentEvent = info.event;

            const modal = document.getElementById('reservationModal');
            const titleEl = document.getElementById('modalTitle');
            const colorEl = document.getElementById('modalColor');
            const startEl = document.getElementById('modalStart');
            const endEl = document.getElementById('modalEnd');
            const locationEl = document.getElementById('modalLocation');
            const staffEl = document.getElementById('modalStaff');
            const customerEl = document.getElementById('modalCustomer');
            const descriptionEl = document.getElementById('modalDescription');
            const closeBtn = document.getElementById('modalClose');
            const deleteBtn = document.getElementById('deleteEventBtn');
            const editBtn = document.getElementById('editEventBtn');

            titleEl.textContent = info.event.title;
            colorEl.textContent = info.event.backgroundColor;
            colorEl.innerHTML = `
                <p class="flex items-center gap-2">
                    <strong>ラベル色:</strong>
                    <span class="rounded-md border border-gray-300 shadow-sm w-5 h-5" style="background-color: ${info.event.backgroundColor};"></span>
                    <span>${info.event.backgroundColor}</span>
                </p>
            `;
            startEl.textContent = formatDateForDisplay(info.event.start);
            endEl.textContent = formatDateForDisplay(info.event.end);
            locationEl.textContent = info.event.extendedProps.location || '未設定';

            // 顧客リンク（customer_id があればリンク）
            if (info.event.extendedProps.customer_id) {
                customerEl.innerHTML = `
                    <a href="/customers/${info.event.extendedProps.customer_id}" class="text-blue-600 hover:underline">
                        ${info.event.extendedProps.customer_name || '未設定'}
                    </a>
                `;
            } else {
                customerEl.textContent = info.event.extendedProps.customer_name || '未設定';
            }

            staffEl.innerHTML = info.event.extendedProps.staff_name || '未設定';

            descriptionEl.textContent = info.event.extendedProps.description || 'なし';

            modal.classList.remove('hidden');

            closeBtn.onclick = () => modal.classList.add('hidden');

            // 削除は動的 form で送信（redirect + flash）
            deleteBtn.onclick = () => {
                if (!currentEvent) return;
                if (!confirm('このイベントを削除しますか？')) return;
                submitForm(`/reservations/${currentEvent.id}`, 'DELETE', {});
            };

            // 編集ボタンは編集ページへ遷移
            editBtn.onclick = () => {
                if (!currentEvent) return;
                window.location.href = `/reservations/${currentEvent.id}/edit`;
            };
        },

        // ドラッグで日時変更（フォーム送信で PUT）
        eventDrop: function(info) {
            if (!confirm('予約を移動してよろしいですか？')) {
                info.revert();
                return;
            }

            // title と customer_id は検証で必要なら送る（存在しなければ空文字）
            const title = info.event.title || '';
            const customerId = info.event.extendedProps.customer_id || '';
            const staffId = info.event.extendedProps.staff_id || '';

            const payload = {
                title: title,
                start: info.event.start ? info.event.start.toISOString() : '',
                end: info.event.end ? info.event.end.toISOString() : '',
                customer_id: customerId,
                staff_id: staffId,
            };
            submitForm(`/reservations/${info.event.id}`, 'PUT', payload);
        },

        // リサイズ（終了日時変更）
        eventResize: function(info) {
            if (!confirm('予約期間を変更してよろしいですか？')) {
                info.revert();
                return;
            }
            const title = info.event.title || '';
            const customerId = info.event.extendedProps.customer_id || '';
            const staffId = info.event.extendedProps.staff_id || '';

            const payload = {
                title: title,
                start: info.event.start ? info.event.start.toISOString() : '',
                end: info.event.end ? info.event.end.toISOString() : '',
                customer_id: customerId,
                staff_id: staffId,
            };
            submitForm(`/reservations/${info.event.id}`, 'PUT', payload);
        },

        // クリックで新規（単一クリック用）
        dateClick: function(info) {
            // 同じ create モーダルのロジックを流用
            const modal = document.getElementById('createReservationModal');
            const form = document.getElementById('createReservationForm');
            const startEl = form.querySelector('input[name="start"]');
            const endEl = form.querySelector('input[name="end"]');

            const clickedDate = new Date(info.date);
            const now = new Date();
            clickedDate.setHours(now.getHours(), now.getMinutes(), 0, 0);

            startEl.value = formatDateForInput(clickedDate);
            const endDate = new Date(clickedDate.getTime() + 60 * 60 * 1000);
            endEl.value = formatDateForInput(endDate);

            modal.classList.remove('hidden');

            // create form submit handled in form.onsubmit (see select)
        },
    });

    calendar.render();
});
