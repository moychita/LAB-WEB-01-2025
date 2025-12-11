@extends('layouts.app')

@section('title', 'Edit Warehouse')

@section('content')
    <h1 class="mb-3">Edit Warehouse</h1>

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

    <form action="{{ route('warehouses.update', $warehouse) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Warehouse</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name', $warehouse->name) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Lokasi</label>
            <textarea name="location"
                      class="form-control"
                      rows="3">{{ old('location', $warehouse->location) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Update
        </button>
    </form>
@endsection
