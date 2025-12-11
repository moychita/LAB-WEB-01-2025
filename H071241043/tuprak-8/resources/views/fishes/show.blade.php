@extends('layouts.app')

@section('title', 'Fish Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white">Fish Details: {{ $fish->name }}</h2>
    <a href="{{ route('fishes.index') }}" class="btn btn-secondary">Back to List</a>
</div>

<div class="card">
    <div class="card-body p-4 p-md-5">

        <div class="row">
            <div class="col-lg-7">
                <ul class="list-group list-group-flush detail-list">
                    <li class="list-group-item">
                        <span class="detail-label">ID</span>
                        <span class="detail-value">{{ $fish->id }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="detail-label">Fish Name</span>
                        <span class="detail-value text-white">{{ $fish->name }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="detail-label">Rarity</span>
                        <span class="detail-value">{{ $fish->rarity }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="detail-label">Base Weight</span>
                        <span class="detail-value">{{ $fish->base_weight_min }} kg - {{ $fish->base_weight_max }} kg</span>
                    </li>
                    <li class="list-group-item">
                        <span class="detail-label">Sell Price/kg</span>
                        <span class="detail-value">{{ $fish->sell_price_per_kg }} Coins</span>
                    </li>
                    <li class="list-group-item">
                        <span class="detail-label">Catch Probability</span>
                        <span class="detail-value">{{ $fish->catch_probability }}%</span>
                    </li>
                </ul>
            </div>

            <div class="col-lg-5">
                <ul class="list-group list-group-flush detail-list">
                    <li class="list-group-item">
                        <span class="detail-label">Description</span>
                        <span class="detail-value-long">{{ $fish->description ?? '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="detail-label">Created At</span>
                        <span class="detail-value">{{ $fish->created_at }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="detail-label">Updated At</span>
                        <span class="detail-value">{{ $fish->updated_at }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('fishes.edit', $fish) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('fishes.destroy', $fish) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </div>

    </div>
</div>
@endsection