<?php

namespace App\Http\Controllers\Backoffice;

use App\Commons\Controller\BaseController;
use App\Repositories\InboxRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InboxController extends BaseController
{
    public function __construct(
        protected InboxRepository $inboxRepository
    ) {}

    public function index()
    {
        try {
            // Menggunakan method paginate bawaan AppRepository
            $inbox = $this->inboxRepository->paginate(request());
            return view('backoffice.pages.inbox.index', compact('inbox'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal memuat data pesan.');
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->inboxRepository->delete($id);
            return redirect()->route('inbox.index')->with('success', 'Pesan berhasil dihapus');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal menghapus pesan: ' . $th->getMessage());
        }
    }
}
