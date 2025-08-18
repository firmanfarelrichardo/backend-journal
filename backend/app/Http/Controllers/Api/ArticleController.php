<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|min:3',
        ]);

        $keyword = $request->input('keyword');

        $articles = Article::query()
            ->where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('description', 'LIKE', "%{$keyword}%")
            ->orWhere('creator1', 'LIKE', "%{$keyword}%")
            ->orderBy('date', 'desc')
            ->paginate(10);

        return response()->json($articles);
    }
}