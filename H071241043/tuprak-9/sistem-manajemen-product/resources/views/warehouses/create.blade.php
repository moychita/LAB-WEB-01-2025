@extends('layouts.app')

@section('title', 'Tambah Warehouse')

@section('content')
    <h1 class="mb-3">Tambah Warehouse</h1>

    <a href="{{ route('warehouses.index') }}" class="btn btn-secondary mb-3">
        &laquo; Kembali ke daftar
    </a>

    {{-- Tampilkan error validasi --}}
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

    <form action="{{ route('warehouses.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Warehouse</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Lokasi</label>
            <textarea name="location"
                      class="form-control"
                      rows="3">{{ old('location') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Simpan
        </button>
    </form>
@endsection
