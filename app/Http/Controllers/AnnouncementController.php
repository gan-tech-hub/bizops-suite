<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    // 全件（過去のお知らせ）ページ（管理者/一般どちらでも閲覧可）
    public function index()
    {
        // ページネーション（例：15件/ページ）
        $announcements = Announcement::latest()->paginate(15);
        return view('announcements.index', compact('announcements'));
    }

    // 詳細ページ
    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    // 新規投稿（管理者のみ）。can:admin ミドルウェアをルートで付けます。
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        $announcement = Announcement::create([
            'user_id' => Auth::id(),
            'title'   => $request->title,
            'body'    => $request->body,
        ]);

        // 投稿後はダッシュボードに戻る（あるいはフラッシュ＋同ページ）
        return redirect()->back()->with('success', 'お知らせを投稿しました');
    }
}
