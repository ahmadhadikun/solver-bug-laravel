<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil kata kunci dan kategori dari form
        $keyword = $request->keyword;
        $category = $request->category;

        // Membuat query pencarian buku
        $books = Book::with('category')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('title', 'LIKE', "%{$keyword}%")
                      ->orWhere('author', 'LIKE', "%{$keyword}%");
                });
            })
            ->when($category, function ($query) use ($category) {
                $query->where('category_id', $category);
            })
            ->get();

        // Mengambil seluruh kategori untuk dropdown
        $categories = Category::orderBy('name')->get();

        // Mengirim data ke halaman Blade
        return view('books.index', compact(
            'books',
            'categories',
            'keyword',
            'category'
        ));
    }
}