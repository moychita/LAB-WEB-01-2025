@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
    <h1 class="mb-3">Edit Produk</h1>

    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">
        &laquo; Kembali ke Daftar Produk
    </a>

    {{-- Error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- DATA PRODUK UTAMA --}}
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name', $product->name) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select">
                <option value="">-- Tanpa Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        @selected(old('category_id', $product->category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number"
                   step="0.01"
                   name="price"
                   class="form-control"
                   value="{{ old('price', $product->price) }}"
                   required>
        </div>

        <hr>
        <h4>Detail Produk</h4>

        @php
            $detail = $product->detail;
        @endphp

        <div class="mb-3">
            <label class="form-label">Deskripsi Lengkap</label>
            <textarea name="description"
                      class="form-control"
                      rows="3">{{ old('description', optional($detail)->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Berat (kg)</label>
            <input type="number"
                   step="0.01"
                   name="weight"
                   class="form-control"
                   value="{{ old('weight', optional($detail)->weight) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ukuran</label>
            <input type="text"
                   name="size"
                   class="form-control"
                   value="{{ old('size', optional($detail)->size) }}">
        </div>

        <button type="submit" class="btn btn-primary">
            Update
        </button>
    </form>
@endsection
