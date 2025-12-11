@extends('layouts.app')

@section('title', 'Add New Fish')

@section('content')
<h2 class="text-center mb-4 text-white">Add a New Fish</h2>

<form action="{{ route('fishes.store') }}" method="POST">
    @csrf
    
    <div class="card">
        <div class="card-body p-4 p-md-5">

            <div class="row">

                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="name" class="form-label form-label-required">Fish Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="rarity" class="form-label form-label-required">Rarity</label>
                            <select class="form-select" id="rarity" name="rarity" required>
                                <option value="" disabled selected>Select Rarity</option>
                                @foreach ($rarities as $rarity)
                                    <option value="{{ $rarity }}" {{ old('rarity') == $rarity ? 'selected' : '' }}>
                                        {{ $rarity }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="base_weight_min" class="form-label form-label-required">Min Weight (kg)</label>
                            <input type="number" step="0.01" class="form-control" id="base_weight_min" name="base_weight_min" value="{{ old('base_weight_min') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="base_weight_max" class="form-label form-label-required">Max Weight (kg)</label>
                            <input type="number" step="0.01" class="form-control" id="base_weight_max" name="base_weight_max" value="{{ old('base_weight_max') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="sell_price_per_kg" class="form-label form-label-required">Price/kg (Coins)</label>
                            <input type="number" class="form-control" id="sell_price_per_kg" name="sell_price_per_kg" value="{{ old('sell_price_per_kg') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="catch_probability" class="form-label form-label-required">Catch Probability (%)</label>
                            <input type="number" step="0.01" min="0.01" max="100.00" class="form-control" id="catch_probability" name="catch_probability" value="{{ old('catch_probability') }}" placeholder="e.g., 25.50" required>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="mb-3 h-100">
                        <label for="description" class="form-label">Description (optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="10" 
                                  placeholder="Optional fish description..." 
                                  style="height: calc(100% - 38px); min-height: 150px;">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('fishes.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Fish</button>
            </div>

        </div>
    </div>
</form>

@endsection