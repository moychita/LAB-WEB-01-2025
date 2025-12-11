@extends('layouts.app')

@section('title', 'Transfer Stok')

@section('content')
    <h1 class="mb-3">Transfer Stok Produk</h1>

    <a href="{{ route('stocks.index') }}" class="btn btn-secondary mb-3">
        &laquo; Kembali ke Manajemen Stok
    </a>

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

    <form action="{{ route('stocks.transfer.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Gudang</label>
            <select name="warehouse_id" class="form-select" required>
                <option value="">-- Pilih Gudang --</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" @selected(old('warehouse_id') == $warehouse->id)>
                        {{ $warehouse->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Produk</label>
            <select name="product_id" class="form-select" required>
                <option value="">-- Pilih Produk --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Jumlah Stok</label>
            <input type="number"
                   name="quantity"
                   class="form-control"
                   value="{{ old('quantity') }}"
                   required>
            <small class="text-muted">
                Contoh: 10 (tambah stok 10), -5 (kurangi stok 5)
            </small>
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan (opsional)</label>
            <textarea name="note" class="form-control" rows="2">{{ old('note') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Proses Transfer
        </button>
    </form>
@endsection
