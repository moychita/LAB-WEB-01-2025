@extends('layouts.app')

@section('title', 'Detail Kategori')

@section('content')
    <h1 class="mb-3">Detail Kategori</h1>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary mb-3">
        &laquo; Kembali ke Daftar Kategori
    </a>

    {{-- Data utama kategori --}}
    <div class="card mb-4">
        <div class="card-header">
            Informasi Kategori
        </div>
        <div class="card-body">
            <h4 class="card-title mb-2">{{ $category->name }}</h4>

            <p class="card-text">
                <strong>Deskripsi:</strong><br>
                {{ $category->description ?: '-' }}
            </p>

            <p class="card-text">
                <small class="text-muted">
                    Dibuat: {{ $category->created_at?->format('d-m-Y H:i') ?? '-' }}<br>
                    Diperbarui: {{ $category->updated_at?->format('d-m-Y H:i') ?? '-' }}
                </small>
            </p>
        </div>
    </div>

    {{-- Daftar produk dalam kategori ini --}}
    <h3 class="mb-3">Produk dalam Kategori Ini</h3>

    @if ($category->products->count() > 0)
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 60px">No</th>
                    <th>Nama Produk</th>
                    <th style="width: 150px">Harga</th>
                    <th style="width: 140px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($category->products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->name }}</td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            @if (Route::has('products.show'))
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">
                                    Detail
                                </a>
                            @endif

                            @if (Route::has('products.edit'))
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p><em>Belum ada produk yang terhubung dengan kategori ini.</em></p>
    @endif
@endsection
