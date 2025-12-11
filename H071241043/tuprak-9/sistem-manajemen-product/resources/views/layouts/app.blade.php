<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistem Manajemen Produk')</title>

    {{-- Bootstrap CDN (opsional, biar tabel & button rapi) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    {{-- Navbar sederhana --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Product Management</a>

            <div>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-light me-2">
                    Produk
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-light me-2">
                    Kategori
                </a>
                <a href="{{ route('warehouses.index') }}" class="btn btn-sm btn-outline-light">
                    Warehouse
                </a>
                <a href="{{ route('stocks.index') }}" class="btn btn-sm btn-outline-light">
                    Stok
                </a>
            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <main class="container">
        @yield('content')
    </main>

    {{-- Script Bootstrap (opsional) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
