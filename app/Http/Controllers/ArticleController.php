<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
{
    $articles = Article::with('category', 'user')->latest()->paginate(10);
    return view('articles.index', compact('articles'));
}

public function create()
{
    $categories = Category::all();
    return view('articles.create', compact('categories'));
}

public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'content' => 'required',
        'category_id' => 'required',
    ]);

    Article::create([
        'title' => $request->title,
        'content' => $request->content,
        'category_id' => $request->category_id,
        'user_id' => auth()->id(),
    ]);

    return redirect()->route('articles.index');
}

public function search(Request $request)
{
    $search = $request->input('query');
    $articles = Article::where('title', 'like', "%{$search}%")->paginate(10);
    return view('articles.index', compact('articles'));
}

}
