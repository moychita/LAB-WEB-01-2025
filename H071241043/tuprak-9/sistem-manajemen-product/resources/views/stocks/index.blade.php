@extends('layouts.app')

@section('title', 'Manajemen Stok')

@section('content')
    <h1 class="mb-3">Manajemen Stok</h1>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="warehouse_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Pilih Gudang --</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" @selected($warehouseId == $warehouse->id)>
                        {{ $warehouse->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <a href="{{ route('stocks.transfer') }}" class="btn btn-primary">
                Transfer Stok
            </a>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($warehouseId && $stocks->count())
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Jumlah Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->category->name ?? '-' }}</td>
                        <td>{{ $row->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($warehouseId)
        <p><em>Belum ada stok untuk gudang ini.</em></p>
    @else
        <p>Silakan pilih gudang terlebih dahulu.</p>
    @endif
@endsection
