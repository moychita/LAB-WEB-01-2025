@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <h1 class="mb-3">Edit Kategori</h1>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary mb-3">
        &laquo; Kembali ke Daftar Kategori
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

    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Kategori</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name', $category->name) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description"
                      class="form-control"
                      rows="3">{{ old('description', $category->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Update
        </button>
    </form>
@endsection
