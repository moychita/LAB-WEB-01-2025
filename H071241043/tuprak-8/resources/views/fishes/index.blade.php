@extends('layouts.app')

@section('title', 'FishBase - Manage Collection')

@section('content')

    <h2 class="text-center mb-4 text-white page-title">Your Fish Collection</h2>

    <div class="page-controls d-flex justify-content-between align-items-center mb-4">
        
        <form method="GET" action="{{ route('fishes.index') }}" class="d-flex align-items-center gap-2">
            <label for="rarity" class="form-label mb-0 text-white-50">Filter by Rarity:</label>
            <select name="rarity" id="rarity" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Rarities</option>
                @foreach ($rarities as $rarity)
                    <option value="{{ $rarity }}" {{ request('rarity') == $rarity ? 'selected' : '' }}>
                        {{ $rarity }}
                    </option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('fishes.create') }}" class="btn btn-light-main">
            <i class="bi bi-plus-lg"></i> Add New Fish
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #2b0920; border-color: #f92672; color: #f0f0f0;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($fishes->isNotEmpty())
        
        <div class="fish-list-container card-style-list">
            
            <div class="fish-list-header d-none d-md-flex p-3">
                <div class="col-md-3"><strong>Name</strong></div>
                <div class="col-md-2"><strong>Rarity</strong></div>
                <div class="col-md-2"><strong>Price/kg</strong></div>
                <div class="col-md-2"><strong>Probability</strong></div>
                <div class="col-md-3 text-end"><strong>Actions</strong></div>
            </div>

            @foreach ($fishes as $fish)
                <div class="fish-list-item">
                    <div class="row align-items-center p-3">
                        
                        <div class="col-md-3 mb-2 mb-md-0">
                            <strong class="fish-name">{{ $fish->name }}</strong>
                            <div class="fish-rarity d-block d-md-none">{{ $fish->rarity }}</div>
                        </div>

                        <div class="col-md-2 d-none d-md-block">{{ $fish->rarity }}</div>
                        <div class="col-md-2 col-6">{{ $fish->sell_price_per_kg }} Coins</div>
                        <div class="col-md-2 col-6">{{ $fish->catch_probability }}%</div>
                        
                        <div class="col-md-3 col-12 text-md-end mt-2 mt-md-0">
                            <div class="d-flex gap-1 justify-content-md-end">
                                <a href="{{ route('fishes.show', $fish) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('fishes.edit', $fish) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('fishes.destroy', $fish) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
            
           @if ($fishes->hasPages())
        
        <div class="d-flex flex-column align-items-center mt-4">
            
            <div class="pagination-summary mb-2">
                Showing {{ $fishes->firstItem() }} to {{ $fishes->lastItem() }} of {{ $fishes->total() }} results
            </div>

            <div class="flex-wrap justify-content-center">
                {{ $fishes->links('pagination::bootstrap-5') }}
            </div>
            
        </div>
    @endif
            

    @else
        <div class="empty-state text-center">
            <h4 class="text-white">No Fish Found</h4>
            <p class="text-white-50">Try adding a new fish, or clear your filter.</p>
        </div>
    @endif

@endsection