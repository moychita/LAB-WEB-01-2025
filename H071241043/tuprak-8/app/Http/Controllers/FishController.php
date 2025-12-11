<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use Illuminate\Http\Request;

class FishController extends Controller
{
    public function index(Request $request)
    {
        $rarities = [
            'Common', 'Uncommon', 'Rare', 'Epic', 'Legendary', 'Mythic', 'Secret'
        ];

        $query = Fish::query();

        if ($request->filled('rarity')) {
            $query->where('rarity', $request->rarity);
        }

        $fishes = $query->latest()->paginate(10);

        return view('fishes.index', [
            'fishes' => $fishes,
            'rarities' => $rarities,
        ]);
    }

    public function create()
    {
        $rarities = [
            'Common', 'Uncommon', 'Rare', 'Epic', 'Legendary', 'Mythic', 'Secret'
        ];

        return view('fishes.create', [
            'rarities' => $rarities
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'rarity' => 'required|in:Common,Uncommon,Rare,Epic,Legendary,Mythic,Secret',
            'base_weight_min' => 'required|numeric|min:0',
            'base_weight_max' => 'required|numeric|min:0|gt:base_weight_min', 
            'sell_price_per_kg' => 'required|integer|min:0',
            'catch_probability' => 'required|numeric|min:0.01|max:100.00',
            'description' => 'nullable|string',
        ]);


        Fish::create([
            'name' => $request->name,
            'rarity' => $request->rarity,
            'base_weight_min' => $request->base_weight_min,
            'base_weight_max' => $request->base_weight_max,
            'sell_price_per_kg' => $request->sell_price_per_kg,
            'catch_probability' => $request->catch_probability,
            'description' => $request->description,
        ]);

        return redirect()->route('fishes.index')
                         ->with('success', 'New fish has been added successfully!');
    }

  
    public function show(Fish $fish)
    {
        return view('fishes.show', [
            'fish' => $fish
        ]);
    }

  
    public function edit(Fish $fish)
    {
    
        $rarities = [
            'Common', 'Uncommon', 'Rare', 'Epic', 'Legendary', 'Mythic', 'Secret'
        ];

        return view('fishes.edit', [
            'rarities' => $rarities,
            'fish' => $fish 
        ]);
    }

   
    public function update(Request $request, Fish $fish)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'rarity' => 'required|in:Common,Uncommon,Rare,Epic,Legendary,Mythic,Secret',
            'base_weight_min' => 'required|numeric|min:0',
            'base_weight_max' => 'required|numeric|min:0|gt:base_weight_min', 
            'sell_price_per_kg' => 'required|integer|min:0',
            'catch_probability' => 'required|numeric|min:0.01|max:100.00',
            'description' => 'nullable|string',
        ]);

       
        $fish->update([
            'name' => $request->name,
            'rarity' => $request->rarity,
            'base_weight_min' => $request->base_weight_min,
            'base_weight_max' => $request->base_weight_max,
            'sell_price_per_kg' => $request->sell_price_per_kg,
            'catch_probability' => $request->catch_probability,
            'description' => $request->description,
        ]);


        return redirect()->route('fishes.index')
                         ->with('success', 'Fish data has been updated successfully!');
    }

  
    public function destroy(Fish $fish)
    {
        $fish->delete();
        return redirect()->route('fishes.index')
                         ->with('success', 'Fish has been deleted successfully!');
    }
}