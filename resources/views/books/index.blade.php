<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .book-card {
            transition: all 0.3s ease;
            border-left: 4px solid #20c997;
            background: white;
            border-radius: 8px;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .btn-submit {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #20c997 0%, #12b886 100%);
            letter-spacing: 0.5px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #12b886 0%, #0ca678 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(18, 184, 134, 0.3);
        }

        .btn-delete {
            transition: all 0.2s ease;
            letter-spacing: 0.5px;
        }

        .btn-delete:hover {
            transform: scale(1.05);
            box-shadow: 0 3px 10px rgba(220, 53, 69, 0.2);
        }

        .loading-icon {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .header-title {
            font-family: 'Playfair Display', serif;
        }

        .section-header {
            background: linear-gradient(135deg, #212529 0%, #343a40 100%);
        }

        .empty-state-icon {
            color: #adb5bd;
        }

        .form-input {
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
        }

        .form-input:focus {
            border-color: #20c997;
            box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.25);
        }
    </style>
</head>
<body class="p-6">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <header class="text-center mb-12">
            <div class="inline-block p-4 rounded-lg bg-white shadow-sm mb-4">
                <i class="fas fa-book-open text-3xl text-teal-600"></i>
            </div>
            <h1 class="header-title text-5xl font-bold text-gray-900 mb-3">Perpustakaan Digital</h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Kelola koleksi literatur Anda dengan antarmuka yang elegan</p>
        </header>

        <!-- Flash Message -->
        @if(session('success'))
            <div class="bg-teal-100 border-l-4 border-teal-500 text-teal-800 p-4 mb-8 rounded-lg shadow-sm flex items-center">
                <i class="fas fa-check-circle text-teal-600 mr-3 text-xl"></i>
                <div>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Form Tambah Buku -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-12 border border-gray-100">
            <div class="section-header px-6 py-5">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-plus-circle mr-3"></i>Tambah Buku Baru
                </h2>
            </div>
            <form method="POST" action="{{ route('books.store') }}" class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Buku</label>
                        <input type="text" id="title" name="title" placeholder="Masukkan judul buku"
                               class="form-input w-full px-4 py-3 rounded-lg focus:ring-0 focus:outline-none">
                    </div>
                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700 mb-2">Penulis</label>
                        <input type="text" id="author" name="author" placeholder="Nama penulis"
                               class="form-input w-full px-4 py-3 rounded-lg focus:ring-0 focus:outline-none">
                    </div>
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                        <input type="number" id="year" name="year" placeholder="Tahun terbit" min="1900" max="{{ date('Y') }}"
                               class="form-input w-full px-4 py-3 rounded-lg focus:ring-0 focus:outline-none">
                    </div>
                </div>
                <div class="mt-8 text-center">
                    <button type="submit" class="btn-submit text-white px-8 py-3 rounded-lg shadow-md flex items-center justify-center space-x-2 mx-auto">
                        <span id="btn-text">Tambah Buku</span>
                        <span id="loading-icon" class="hidden loading-icon">
                            <i class="fas fa-spinner"></i>
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar Buku -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="section-header px-6 py-5">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-books mr-3"></i>
                    <span>Koleksi Buku Saya</span>
                    <span class="ml-auto bg-gray-900 text-white text-sm px-3 py-1 rounded-full font-medium">
                        {{ count($books) }} buku
                    </span>
                </h2>
            </div>

            @if(count($books) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                    @foreach($books as $book)
                    <div class="book-card p-6">
                        <div class="flex flex-col h-full">
                            <div class="flex-grow">
                                <div class="flex items-start mb-4">
                                    <div class="bg-teal-100 p-3 rounded-lg mr-4">
                                        <i class="fas fa-book text-teal-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-800">{{ $book->title }}</h3>
                                        <div class="flex items-center text-gray-600 mt-2">
                                            <i class="fas fa-user-edit text-teal-500 mr-2"></i>
                                            <span>{{ $book->author }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg inline-flex items-center">
                                    <i class="fas fa-calendar-alt text-teal-500 mr-2"></i>
                                    <span class="text-sm font-medium">{{ $book->year }}</span>
                                </div>
                            </div>
                            <div class="mt-6 text-right">
                                <form method="POST" action="{{ route('books.destroy', $book->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete bg-red-50 text-red-600 px-5 py-2 rounded-lg hover:bg-red-100 flex items-center justify-center">
                                        <i class="fas fa-trash-alt mr-2"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="p-10 text-center">
                    <div class="empty-state-icon inline-block p-6 rounded-full bg-gray-100 mb-6">
                        <i class="fas fa-books text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">Koleksi Kosong</h3>
                    <p class="text-gray-500 mb-4">Anda belum menambahkan buku apapun</p>
                    <p class="text-gray-400 text-sm">Mulai bangun koleksi Anda dengan menambahkan buku pertama</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function() {
            const btnText = document.getElementById('btn-text');
            const loadingIcon = document.getElementById('loading-icon');

            btnText.textContent = 'Memproses...';
            loadingIcon.classList.remove('hidden');
            document.getElementById('submit-btn').disabled = true;
        });
    </script>
</body>
</html>