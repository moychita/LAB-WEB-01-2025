@extends('layouts.app')

@section('content')
<h1>Daftar Kategori</h1>

<a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Tambah Kategori</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Kategori</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
                <td>
                    <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-info">Show</a>
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="3">Belum ada kategori.</td></tr>
        @endforelse
    </tbody>
</table>

{{ $categories->links() }}
@endsection
