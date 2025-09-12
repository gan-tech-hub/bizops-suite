import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

function formatDateForInput(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

async function updateReservationDate(info) {
    const formEl = document.createElement('form');
    formEl.method = 'POST';
    formEl.action = `/reservations/${info.event.id}`;
    formEl.innerHTML = `
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
        <input type="hidden" name="start" value="${formatDateForInput(info.event.start)}">
        <input type="hidden" name="end" value="${info.event.end ? formatDateForInput(info.event.end) : ''}">
    `;
    document.body.appendChild(formEl);
    formEl.submit();
}

document.addEventListener('DOMContentLoaded', function() {
    let currentEvent = null;
    const calendarEl = document.getElementById('calendar');

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        selectable: true,
        editable: true,
        locale: 'ja',

        // events: APIで取得
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
                    staff: event.staff || '',
                    customer: event.customer || '',
                    description: event.description || '',
                }));
                successCallback(events);
            } catch (error) {
                console.error("イベント取得エラー:", error);
                failureCallback(error);
            }
        },

        // 新規追加（モーダル内フォームは /api/reservations へ POST）
        select: function(info) {
            const modal = document.getElementById('createReservationModal');
            const form = document.getElementById('createReservationForm');
            const cancelBtn = document.getElementById('createCancelBtn');

            form.start.value = info.startStr ? info.startStr.slice(0,16) : '';
            form.end.value = info.endStr ? info.endStr.slice(0,16) : '';

            modal.classList.remove('hidden');

            cancelBtn.onclick = () => {
                modal.classList.add('hidden');
                form.reset();
            };

            form.onsubmit = async (e) => {
                e.preventDefault();

                const formEl = document.createElement('form');
                formEl.method = 'POST';
                formEl.action = '/reservations';
                formEl.innerHTML = `
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                    <input type="hidden" name="title" value="${form.title.value}">
                    <input type="hidden" name="color" value="${form.color.value}">
                    <input type="hidden" name="start" value="${form.start.value}">
                    <input type="hidden" name="end" value="${form.end.value}">
                    <input type="hidden" name="location" value="${form.location.value}">
                    <input type="hidden" name="staff" value="${form.staff.value}">
                    <input type="hidden" name="customer" value="${form.customer.value}">
                    <input type="hidden" name="description" value="${form.description.value}">
                `;
                document.body.appendChild(formEl);
                formEl.submit();
            };
        },

        // イベントクリック（モーダル表示）
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
            startEl.textContent = info.event.start ? info.event.start.toLocaleString('ja-JP', { hour12: false }) : '';
            endEl.textContent = info.event.end ? info.event.end.toLocaleString('ja-JP', { hour12: false }) : '';
            locationEl.textContent = info.event.extendedProps.location || '未設定';
            staffEl.textContent = info.event.extendedProps.staff || '未設定';
            customerEl.textContent = info.event.extendedProps.customer || '未設定';
            descriptionEl.textContent = info.event.extendedProps.description || 'なし';

            modal.classList.remove('hidden');

            closeBtn.onclick = () => modal.classList.add('hidden');

            // 削除（APIへDELETE → 成功したらリダイレクト）
            deleteBtn.onclick = async () => {
                if (!currentEvent) return;
                if (!confirm('このイベントを削除しますか？')) return;

                // ✅ 動的フォームでDELETEリクエスト送信
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/reservations/${currentEvent.id}`;
                form.innerHTML = `
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                `;
                document.body.appendChild(form);
                form.submit();
            };

            // 編集へ遷移
            editBtn.onclick = () => {
                if (!currentEvent) return;
                window.location.href = `/reservations/${currentEvent.id}/edit`;
            };
        },

        // ドラッグで日時変更（API PUT を叩いて、成功したらリダイレクト）
        eventDrop: function(info) {
            if (!confirm('予約を移動してよろしいですか？')) {
                info.revert();
                return;
            }

            (async () => {
                await updateReservationDate(info);
            })();
        },

        // リサイズ（終了日時変更）
        eventResize: function(info) {
            if (!confirm('予約期間を変更してよろしいですか？')) {
                info.revert();
                return;
            }

            (async () => {
                await updateReservationDate(info);
            })();
        },

        // dateClick（クリックで新規モーダル）
        dateClick: function(info) {
            const modal = document.getElementById('createReservationModal');
            const startInput = document.querySelector('#createReservationForm input[name="start"]');
            const endInput = document.querySelector('#createReservationForm input[name="end"]');

            const clickedDate = new Date(info.date);
            const now = new Date();
            clickedDate.setHours(now.getHours(), now.getMinutes(), 0, 0);

            const startStr = formatDateForInput(clickedDate);
            const endDate = new Date(clickedDate.getTime() + 60 * 60 * 1000);
            const endStr = formatDateForInput(endDate);

            startInput.value = startStr;
            endInput.value = endStr;

            modal.classList.remove('hidden');

            // 新規予約作成
            form.onsubmit = (e) => {
                e.preventDefault();

                const formEl = document.createElement('form');
                formEl.method = 'POST';
                formEl.action = '/reservations';
                formEl.innerHTML = `
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                    <input type="hidden" name="title" value="${form.title.value}">
                    <input type="hidden" name="color" value="${form.color.value}">
                    <input type="hidden" name="start" value="${form.start.value}">
                    <input type="hidden" name="end" value="${form.end.value}">
                    <input type="hidden" name="location" value="${form.location.value}">
                    <input type="hidden" name="staff" value="${form.staff.value}">
                    <input type="hidden" name="customer" value="${form.customer.value}">
                    <input type="hidden" name="description" value="${form.description.value}">
                `;
                document.body.appendChild(formEl);
                formEl.submit();
            };
        },
    });

    calendar.render();
});
