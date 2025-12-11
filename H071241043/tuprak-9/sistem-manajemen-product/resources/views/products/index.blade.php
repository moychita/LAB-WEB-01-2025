@extends('layouts.app')

@section('content')
    <h1 class="mb-3">Daftar Produk</h1>

    {{-- Tombol tambah produk --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            + Tambah Produk
        </a>

        {{-- (Opsional) Form pencarian --}}
        <form method="GET" action="{{ route('products.index') }}" class="d-flex gap-2">
            <input type="text"
                   name="q"
                   class="form-control"
                   placeholder="Cari nama produk..."
                   value="{{ request('q') }}">
            <button class="btn btn-outline-secondary" type="submit">Cari</button>
        </form>
    </div>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel produk --}}
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th style="width: 60px">No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th style="width: 150px">Harga</th>
                <th style="width: 220px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    {{-- Nomor urut global (ikut pagination) --}}
                    <td>
                        {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                    </td>

                    <td>{{ $product->name }}</td>

                    <td>{{ $product->category->name ?? '-' }}</td>

                    <td>
                        {{-- Bisa pakai format angka sederhana --}}
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>

                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">
                            Detail
                        </a>

                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                            Edit
                        </a>

                        <form action="{{ route('products.destroy', $product) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">
                        Belum ada produk yang terdaftar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div>
        {{ $products->withQueryString()->links() }}
    </div>
@endsection
