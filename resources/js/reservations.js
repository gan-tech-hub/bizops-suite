import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, timeGridPlugin, interactionPlugin ],
        initialView: 'dayGridMonth',
        selectable: true,
        editable: true,
        events: '/api/reservations', // 予約データを取得（API経由）

        // ✅ 日付をドラッグ選択した時（新規予約作成）
        select: function(info) {
            const title = prompt('予約タイトルを入力してください:');
            if (title) {
                fetch('/reservations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        title: title,
                        start: info.startStr,
                        end: info.endStr,
                    }),
                })
                .then(response => response.json())
                .then(event => {
                    calendar.addEvent(event); // 画面にも即時反映
                })
                .catch(error => console.error('Error:', error));
            }
            calendar.unselect();
        },

        // ✅ イベントクリック（詳細ページへ遷移）
        eventClick: function(info) {
            if (confirm('この予約を編集しますか？')) {
                window.location.href = `/reservations/${info.event.id}`;
            }
        },

        // ✅ イベントをドラッグ＆ドロップした時（開始・終了日時変更）
        eventDrop: function(info) {
            if (!confirm('予約を移動してよろしいですか？')) {
                info.revert();
                return;
            }
            fetch(`/reservations/${info.event.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    start: info.event.start.toISOString(),
                    end: info.event.end ? info.event.end.toISOString() : null,
                }),
            }).catch(error => {
                console.error('Error:', error);
                info.revert();
            });
        },

        // ✅ イベントをリサイズ（終了日時を伸ばしたり縮めたり）
        eventResize: function(info) {
            if (!confirm('予約期間を変更してよろしいですか？')) {
                info.revert();
                return;
            }
            fetch(`/reservations/${info.event.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    start: info.event.start.toISOString(),
                    end: info.event.end ? info.event.end.toISOString() : null,
                }),
            }).catch(error => {
                console.error('Error:', error);
                info.revert();
            });
        },
    });

    calendar.render();
});
