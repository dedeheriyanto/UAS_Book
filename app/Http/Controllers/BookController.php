<?php

namespace App\Http\Controllers;

use App\Models\Book; // Pastikan untuk mengimpor model Book
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all(); // Mengambil semua data buku
        return view('books.index', compact('books')); // Mengirim data ke view
    }

    public function show($id)
    {
        // Menampilkan detail buku berdasarkan ID
        $book = Book::findOrFail($id); // Menggunakan findOrFail untuk menangani jika ID tidak ditemukan
        return view('books.show', compact('book')); // Mengirim data buku ke view
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'year' => 'required|integer',
        ]);

        // Menyimpan buku baru ke database
        Book::create($request->all());
        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan!'); // Redirect dengan pesan sukses
    }

    public function destroy($id)
    {
        // Menghapus buku berdasarkan ID
        Book::findOrFail($id)->delete(); // Menggunakan findOrFail untuk menangani jika ID tidak ditemukan
        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus!'); // Redirect dengan pesan sukses
    }
}
