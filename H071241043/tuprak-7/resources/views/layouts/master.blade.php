<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🟩 Eksplor NTB</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    {{-- HEADER --}}
    <header>
        <h1>🟩 Eksplor NTB</h1>
        <nav>
            <a href="/">Home</a>
            <a href="/destinasi">Destinasi</a>
            <a href="/kuliner">Kuliner</a>
            <a href="/galeri">Galeri</a>
            <a href="/kontak">Kontak</a>
        </nav>
    </header>

    {{-- KONTEN --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer>
        <p>&copy; 2025 Eksplor NTB | Dibuat oleh 🟩 Nama Kamu</p>
    </footer>
</body>
</html>