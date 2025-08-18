<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\JournalSource;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    /**
     * Mengambil statistik utama untuk halaman beranda.
     */
    public function index()
    {
        // Hitung total artikel dari tabel 'articles'
        $articleCount = Article::count();

        // Hitung total sumber jurnal dari tabel 'journal_sources'
        $journalCount = JournalSource::count();

        // Kembalikan data dalam format JSON
        return response()->json([
            'article_count' => $articleCount,
            'journal_count' => $journalCount,
            'publisher_count' => 4890, // Data statis sementara
            'faculty_count' => 8,      // Data statis sementara
        ]);
    }
}