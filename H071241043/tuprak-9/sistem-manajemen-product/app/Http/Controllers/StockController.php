<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $warehouses  = Warehouse::orderBy('name')->get();
        $warehouseId = $request->get('warehouse_id');

        $stocks = collect();

        if ($warehouseId) {
            $stocks = Product::select('products.*', 'product_warehouse.quantity')
                ->join('product_warehouse', 'products.id', '=', 'product_warehouse.product_id')
                ->where('product_warehouse.warehouse_id', $warehouseId)
                ->with('category')
                ->orderBy('products.name')
                ->get();
        }

        return view('stocks.index', compact('warehouses', 'warehouseId', 'stocks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = Warehouse::orderBy('name')->get();
        $products   = Product::orderBy('name')->get();

        return view('stocks.transfer', compact('warehouses', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id'   => 'required|exists:products,id',
            'quantity'     => 'required|integer|not_in:0',
            'note'         => 'nullable|string',
        ]);

        DB::transaction(function () use ($data) {
            $stock = DB::table('product_warehouse')
                ->where('warehouse_id', $data['warehouse_id'])
                ->where('product_id', $data['product_id'])
                ->lockForUpdate()
                ->first();

            $currentQty = $stock->quantity ?? 0;
            $newQty     = $currentQty + $data['quantity'];

            if (!$stock && $data['quantity'] < 0) {
                throw ValidationException::withMessages([
                    'quantity' => 'Produk belum pernah ada di gudang ini, tidak bisa dikurangi.',
                ]);
            }

            if ($newQty < 0) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok di gudang ini tidak boleh minus.',
                ]);
            }

            if ($stock) {
                DB::table('product_warehouse')
                    ->where('id', $stock->id)
                    ->update(['quantity' => $newQty]);
            } else {
                DB::table('product_warehouse')->insert([
                    'warehouse_id' => $data['warehouse_id'],
                    'product_id'   => $data['product_id'],
                    'quantity'     => $newQty,
                ]);
            }
        });

        return redirect()
            ->route('stocks.index', ['warehouse_id' => $data['warehouse_id']])
            ->with('success', 'Transfer stok berhasil.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }
}
