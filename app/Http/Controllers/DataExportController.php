<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use ZipArchive;

class DataExportController extends Controller
{
    public function index()
    {
        return view('data.index');
    }

    public function export($type, $format)
    {
        switch ($type) {
            case 'customers':
                $data = Customer::all();
                break;
            case 'reservations':
                $data = Reservation::all();
                break;
            case 'staffs':
                $data = User::all();
                break;
            case 'all':
                $data = [
                    'customers' => Customer::all(),
                    'reservations' => Reservation::all(),
                    'staffs' => User::all(),
                ];
                break;
            default:
                abort(404);
        }

        if ($format === 'csv') {
            if ($type === 'all') {
                return $this->exportAllCsv(); 
            }else{
                return $this->exportCsv($data, $type); 
            }
        } elseif ($format === 'pdf') {
            if ($type === 'all') {
                return $this->exportAllPdf(); 
            }else{
                return $this->exportPdf($data, $type);
            }
        } else {
            abort(400);
        }
    }

    private function exportCsv($data, $type)
    {
        $filename = "{$type}_" . now()->format('Ymd_His') . ".csv";
        $handle = fopen('php://temp', 'r+');

        if (isset($data[0])) {
            // ヘッダー
            fputcsv($handle, array_map(function ($value) {
                return mb_convert_encoding($value, 'SJIS-win', 'UTF-8');
            }, array_keys($data[0]->toArray())));

            // データ行
            foreach ($data as $row) {
                $array = $row->toArray();

                // 日付フィールドを見つけて整形
                foreach ($array as $key => $value) {
                    if (
                        (str_ends_with($key, '_at') || $key === 'start' || $key === 'end') &&
                        !empty($value)
                    ) {
                        try {
                            $array[$key] = \Carbon\Carbon::parse($value)
                                ->setTimezone('Asia/Tokyo')
                                ->format('Y/m/d H:i');
                        } catch (\Exception $e) {
                            // parseできない場合はそのまま
                        }
                    }
                }

                // 文字コード変換（SJIS-win）
                fputcsv($handle, array_map(function ($value) {
                    return mb_convert_encoding((string)$value, 'SJIS-win', 'UTF-8');
                }, $array));
            }
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=Shift_JIS')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    private function exportAllCsv()
    {
        $zip = new ZipArchive();
        $zipFile = tempnam(sys_get_temp_dir(), 'all_data_') . '.zip';
        $zip->open($zipFile, ZipArchive::CREATE);

        $types = [
            'customers' => Customer::all(),
            'reservations' => Reservation::all(),
            'staffs' => User::all(),
        ];

        foreach ($types as $type => $data) {
            $csv = $this->generateCsvContent($data); // exportCsv() の CSV 生成部分を関数化
            $zip->addFromString("{$type}.csv", $csv);
        }

        $zip->close();

        return response()->download($zipFile, 'all_data_csv_' . now()->format('Ymd_His') . '.zip')->deleteFileAfterSend(true);
    }

    // CSV 生成部分（exportCsv() からレスポンス部分を除外して関数化）
    private function generateCsvContent($data)
    {
        $handle = fopen('php://temp', 'r+');
        if (isset($data[0])) {
            fputcsv($handle, array_keys($data[0]->toArray()));
            foreach ($data as $row) {
                $rowArray = $row->toArray();
                foreach ($rowArray as $key => $value) {
                    if ((str_ends_with($key, '_at') || $key === 'start' || $key === 'end') && !empty($value)) {
                        $rowArray[$key] = \Carbon\Carbon::parse($value)
                            ->setTimezone('Asia/Tokyo')
                            ->format('Y/m/d H:i');
                    }
                }
                fputcsv($handle, $rowArray);
            }
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return "\xEF\xBB\xBF" . $csv; // UTF-8 BOM
    }

    private function exportPdf($data, $type)
    {
        $pdf = Pdf::loadView('data.pdf', compact('data', 'type'))
                ->setPaper('a4', 'landscape');

        return $pdf->download("{$type}_" . now()->format('Ymd_His') . ".pdf");
    }

    private function exportAllPdf()
    {
        $dataList = [
            'customers' => Customer::all(),
            'reservations' => Reservation::all(),
            'staffs' => User::all(),
        ];

        $pdfFiles = [];

        foreach ($dataList as $type => $data) {
            $pdf = Pdf::loadView('data.pdf', compact('type', 'data'))->setPaper('a4', 'landscape');
            $path = storage_path("app/public/{$type}.pdf");
            $pdf->save($path);
            $pdfFiles[] = $path;
        }

        // ZIP化
        $zipPath = storage_path('app/public/all_data_pdf_' . now()->format('Ymd_His') . '.zip');
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach ($pdfFiles as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
