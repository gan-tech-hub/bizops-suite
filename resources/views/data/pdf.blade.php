<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'NotoSansJP';
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path('fonts/NotoSansJP-Regular.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'NotoSansJP';
            font-style: bold;
            font-weight: bold;
            src: url('{{ storage_path('fonts/NotoSansJP-Bold.ttf') }}') format('truetype');
        }
        body {
            font-family: "NotoSansJP", sans-serif;
            font-size: 12pt;
        }
        h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #333;
            padding: 4px 6px;
        }
    </style>
</head>
<body>
    <h1>{{ $type }} データ一覧</h1>

    @php
        use Carbon\Carbon;
        // Eloquentコレクションを配列に変換
        $data = collect($data)->map(fn($item) => $item->toArray())->toArray();
    @endphp

    @if(empty($data))
        <p>データがありません。</p>
    @else
        <table>
            <thead>
                <tr>
                    @foreach(array_keys($data[0]) as $key)
                        <th>{{ $key }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        @foreach($row as $key => $value)
                            <td>
                                @if($key === 'role')
                                    {{ $value === 'admin' ? '管理者' : '一般' }}
                                @elseif(preg_match('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $value))
                                    {{ Carbon::parse($value)->timezone('Asia/Tokyo')->format('Y/m/d H:i') }}
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
