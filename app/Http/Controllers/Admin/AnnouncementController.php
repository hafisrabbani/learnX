<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Announcement\AnnouncementService;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    protected $announcementService;
    public function __construct(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
    }

    public function index()
    {
        $announcements = $this->announcementService->all();
        return view('Admin.announcement.index', ['announcements' => $announcements]);
    }

    public function create(Request $request)
    {
        return $this->announcementService->create(['title' => $request->title, 'description' => $request->description])
            ? back()->with('error', 'proses gagal') : back()->with('success', 'berhasil menambah announcement');
    }

    public function delete(Request $request)
    {
        $this->announcementService->delete($request->id);
        return back()->with('success', 'berhasil menghapus announcement');
    }

    public function show(Request $request)
    {
        return $this->announcementService->find($request->id);
    }
}
