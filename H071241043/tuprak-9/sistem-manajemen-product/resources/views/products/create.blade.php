@extends('layouts.app')

@section('content')
<h1>Tambah Produk</h1>

<form action="{{ route('products.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama Produk</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label>Kategori</label>
        <select name="category_id" class="form-select">
            <option value="">-- Tanpa Kategori --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label>Harga</label>
        <input type="number" step="0.01" name="price" class="form-control"
               value="{{ old('price') }}" required>
        @error('price') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <hr>
    <h4>Detail Produk</h4>

    <div class="mb-3">
        <label>Deskripsi Lengkap</label>
        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        @error('description') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label>Berat (kg)</label>
        <input type="number" step="0.01" name="weight" class="form-control"
               value="{{ old('weight') }}" required>
        @error('weight') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label>Ukuran</label>
        <input type="text" name="size" class="form-control" value="{{ old('size') }}">
        @error('size') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <button class="btn btn-primary">Simpan</button>
</form>
@endsection
