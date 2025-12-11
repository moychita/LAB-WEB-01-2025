{{-- resources/views/products/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
    <h1 class="mb-3">Detail Produk</h1>

    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">
        &laquo; Kembali ke Daftar Produk
    </a>

    {{-- Kartu info utama produk --}}
    <div class="card mb-4">
        <div class="card-header">
            Informasi Utama
        </div>
        <div class="card-body">
            <h4 class="card-title mb-2">{{ $product->name }}</h4>

            <p class="mb-1">
                <strong>Kategori:</strong>
                {{ $product->category->name ?? '-' }}
            </p>

            <p class="mb-1">
                <strong>Harga:</strong>
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>

            <p class="mb-0">
                <small class="text-muted">
                    Dibuat: {{ $product->created_at?->format('d-m-Y H:i') ?? '-' }}<br>
                    Diperbarui: {{ $product->updated_at?->format('d-m-Y H:i') ?? '-' }}
                </small>
            </p>
        </div>
    </div>

    {{-- Detail produk (product_details) --}}
    <div class="card mb-4">
        <div class="card-header">
            Detail Produk
        </div>
        <div class="card-body">
            @php
                $detail = $product->detail;
            @endphp

            <p>
                <strong>Deskripsi:</strong><br>
                {{ $detail->description ?? '-' }}
            </p>

            <p class="mb-1">
                <strong>Berat:</strong>
                {{ $detail->weight ?? '-' }} kg
            </p>

            <p class="mb-0">
                <strong>Ukuran:</strong>
                {{ $detail->size ?? '-' }}
            </p>
        </div>
    </div>
@endsection
