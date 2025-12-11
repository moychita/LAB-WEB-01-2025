@extends('layouts.app')

@section('title', 'Daftar Warehouse')

@section('content')
    <h1 class="mb-3">Daftar Warehouse</h1>

    {{-- Tombol tambah warehouse --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('warehouses.create') }}" class="btn btn-primary">
            + Tambah Warehouse
        </a>
    </div>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel warehouse --}}
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th style="width: 60px">No</th>
                <th>Nama Warehouse</th>
                <th>Lokasi</th>
                <th style="width: 180px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($warehouses as $warehouse)
                <tr>
                    <td>
                        {{ ($warehouses->currentPage() - 1) * $warehouses->perPage() + $loop->iteration }}
                    </td>
                    <td>{{ $warehouse->name }}</td>
                    <td>{{ $warehouse->location }}</td>
                    <td>
                        <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-sm btn-warning">
                            Edit
                        </a>

                        <form action="{{ route('warehouses.destroy', $warehouse) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus warehouse ini?')">
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
                    <td colspan="4" class="text-center">
                        Belum ada warehouse yang terdaftar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div>
        {{ $warehouses->links() }}
    </div>
@endsection
