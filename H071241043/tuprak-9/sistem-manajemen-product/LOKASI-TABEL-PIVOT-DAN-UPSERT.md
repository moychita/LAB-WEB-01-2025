# 📍 Lokasi Tabel Pivot dan Upsert Pattern

## 🔗 1. TABEL PIVOT: `product_warehouse`

### A. Definisi Tabel Pivot (Migration)

**File**: `database/migrations/2025_11_14_061200_create_product_warehouse_table.php`

```15:26:database/migrations/2025_11_14_061200_create_product_warehouse_table.php
Schema::create('product_warehouse', function (Blueprint $table) { // Membuat tabel pivot 'product_warehouse' untuk relasi many-to-many, nama tabel pivot: singular_product + singular_warehouse
    $table->id(); // Kolom id sebagai primary key
    $table->foreignId('product_id') // Membuat kolom product_id sebagai foreign key
          ->constrained('products') // Foreign key constraint ke tabel 'products'
          ->cascadeOnDelete(); // Jika produk dihapus, semua record di pivot table juga terhapus
    $table->foreignId('warehouse_id') // Membuat kolom warehouse_id sebagai foreign key
          ->constrained('warehouses') // Foreign key constraint ke tabel 'warehouses'
          ->cascadeOnDelete(); // Jika warehouse dihapus, semua record di pivot table juga terhapus
    $table->integer('quantity')->default(0); // Kolom quantity INTEGER dengan default value 0, menyimpan jumlah stok produk di warehouse tertentu

    $table->unique(['product_id', 'warehouse_id']); // Menambahkan constraint UNIQUE pada kombinasi product_id dan warehouse_id, mencegah duplikasi: satu produk di satu warehouse hanya bisa punya satu record
});
```

**Penjelasan:**
- **Nama Tabel**: `product_warehouse` (mengikuti konvensi Laravel: singular + singular)
- **Fungsi**: Menghubungkan banyak produk dengan banyak gudang (many-to-many relationship)
- **Kolom Tambahan**: `quantity` - menyimpan jumlah stok produk di setiap gudang
- **Constraint UNIQUE**: Mencegah duplikasi - satu produk di satu gudang hanya bisa punya satu record

### B. Penggunaan di Model Product

**File**: `app/Models/Product.php`

```22:25:app/Models/Product.php
public function warehouses() { // Method relasi many-to-many dengan Warehouse
    return $this->belongsToMany(Warehouse::class, 'product_warehouse') // Mengembalikan relasi belongsToMany melalui tabel pivot product_warehouse
                ->withPivot('quantity'); // Menambahkan kolom quantity dari tabel pivot agar bisa diakses
}
```

**Penjelasan:**
- `belongsToMany()` - Mendefinisikan relasi many-to-many
- `'product_warehouse'` - Nama tabel pivot (jika tidak disebutkan, Laravel akan cari `product_warehouse` otomatis)
- `->withPivot('quantity')` - Menambahkan kolom `quantity` dari pivot table agar bisa diakses via `$product->warehouses[0]->pivot->quantity`

### C. Penggunaan di Model Warehouse

**File**: `app/Models/Warehouse.php`

```14:17:app/Models/Warehouse.php
public function products() { // Method relasi many-to-many dengan Product
    return $this->belongsToMany(Product::class, 'product_warehouse') // Mengembalikan relasi belongsToMany melalui tabel pivot product_warehouse
                ->withPivot('quantity'); // Menambahkan kolom quantity dari tabel pivot agar bisa diakses
}
```

**Penjelasan:**
- Relasi kebalikan dari Product
- Sama-sama menggunakan tabel pivot `product_warehouse`
- Juga menggunakan `withPivot('quantity')` untuk akses kolom quantity

### D. Penggunaan di Controller (Query Builder)

**File**: `app/Http/Controllers/StockController.php`

```62:66:app/Http/Controllers/StockController.php
$stock = DB::table('product_warehouse') // Mengakses tabel product_warehouse langsung menggunakan query builder
    ->where('warehouse_id', $data['warehouse_id']) // Mencari record berdasarkan warehouse_id
    ->where('product_id', $data['product_id']) // Dan product_id
    ->lockForUpdate() // Mengunci row untuk update (pessimistic locking) mencegah race condition
    ->first(); // Mengambil record pertama yang cocok (atau null jika tidak ada)
```

**Penjelasan:**
- Menggunakan Query Builder langsung (bukan Eloquent)
- Mencari record berdasarkan kombinasi `warehouse_id` dan `product_id`
- `lockForUpdate()` - Mengunci row untuk mencegah race condition

---

## 🔄 2. UPSERT PATTERN

### Lokasi: StockController@store()

**File**: `app/Http/Controllers/StockController.php`

**Baris 83-92**: Ini adalah implementasi Upsert Pattern

```83:92:app/Http/Controllers/StockController.php
if ($stock) { // Mengecek: jika record stok sudah ada
    DB::table('product_warehouse') // Mengakses tabel product_warehouse
        ->where('id', $stock->id) // Mencari berdasarkan ID record yang sudah ada
        ->update(['quantity' => $newQty]); // Memperbarui quantity dengan nilai baru
} else { // Jika record stok belum ada
    DB::table('product_warehouse')->insert([ // Menyisipkan record baru di tabel pivot
        'warehouse_id' => $data['warehouse_id'], // Mengisi warehouse_id
        'product_id'   => $data['product_id'], // Mengisi product_id
        'quantity'     => $newQty, // Mengisi quantity dengan nilai baru
    ]);
}
```

### Penjelasan Upsert Pattern:

**Upsert = Update or Insert**

1. **Cek apakah record sudah ada** (baris 62-66):
   ```php
   $stock = DB::table('product_warehouse')
       ->where('warehouse_id', $data['warehouse_id'])
       ->where('product_id', $data['product_id'])
       ->lockForUpdate()
       ->first();
   ```

2. **Jika ADA** (`if ($stock)`) - **UPDATE**:
   ```php
   DB::table('product_warehouse')
       ->where('id', $stock->id)
       ->update(['quantity' => $newQty]);
   ```

3. **Jika TIDAK ADA** (`else`) - **INSERT**:
   ```php
   DB::table('product_warehouse')->insert([
       'warehouse_id' => $data['warehouse_id'],
       'product_id'   => $data['product_id'],
       'quantity'     => $newQty,
   ]);
   ```

### Alur Lengkap Upsert Pattern:

```
1. User transfer stok produk ke gudang
   ↓
2. Cek apakah produk sudah pernah ada di gudang ini?
   ↓
3a. JIKA SUDAH ADA:
    → UPDATE quantity = quantity_lama + quantity_baru
   ↓
3b. JIKA BELUM ADA:
    → INSERT record baru dengan quantity = quantity_baru
   ↓
4. Selesai
```

### Contoh Skenario:

**Skenario 1: Produk pertama kali masuk gudang**
```
- Product ID: 1
- Warehouse ID: 2
- Quantity: 10

Hasil: INSERT baru
product_warehouse:
  id: 1
  product_id: 1
  warehouse_id: 2
  quantity: 10
```

**Skenario 2: Produk sudah ada, tambah stok**
```
- Product ID: 1
- Warehouse ID: 2
- Quantity saat ini: 10
- Quantity baru: +5

Hasil: UPDATE
product_warehouse:
  id: 1
  product_id: 1
  warehouse_id: 2
  quantity: 15 (10 + 5)
```

**Skenario 3: Produk sudah ada, kurangi stok**
```
- Product ID: 1
- Warehouse ID: 2
- Quantity saat ini: 15
- Quantity baru: -3

Hasil: UPDATE
product_warehouse:
  id: 1
  product_id: 1
  warehouse_id: 2
  quantity: 12 (15 - 3)
```

---

## 📊 Visualisasi Struktur Tabel Pivot

```
┌─────────────────────────────────────┐
│      product_warehouse (PIVOT)       │
├──────┬──────────────┬───────────────┤
│  id  │ product_id   │ warehouse_id  │
├──────┼──────────────┼───────────────┤
│  1   │      1       │      1        │  ← Produk 1 di Gudang 1
│  2   │      1       │      2        │  ← Produk 1 di Gudang 2
│  3   │      2       │      1        │  ← Produk 2 di Gudang 1
│  4   │      2       │      2        │  ← Produk 2 di Gudang 2
└──────┴──────────────┴───────────────┘
         │              │
         │              │
         ▼              ▼
    ┌─────────┐    ┌──────────┐
    │products │    │warehouses│
    └─────────┘    └──────────┘
```

**Kolom tambahan di pivot:**
```
┌──────┬──────────────┬───────────────┬──────────┐
│  id  │ product_id   │ warehouse_id  │ quantity│
├──────┼──────────────┼───────────────┼──────────┤
│  1   │      1       │      1        │    50    │
│  2   │      1       │      2        │    30    │
│  3   │      2       │      1        │    20    │
└──────┴──────────────┴───────────────┴──────────┘
```

---

## 🔍 Cara Mengakses Data dari Pivot Table

### Via Eloquent (dengan withPivot):

```php
// Dari Product
$product = Product::find(1);
foreach ($product->warehouses as $warehouse) {
    echo $warehouse->name; // Nama gudang
    echo $warehouse->pivot->quantity; // Quantity dari pivot table ✅
}

// Dari Warehouse
$warehouse = Warehouse::find(1);
foreach ($warehouse->products as $product) {
    echo $product->name; // Nama produk
    echo $product->pivot->quantity; // Quantity dari pivot table ✅
}
```

### Via Query Builder (langsung):

```php
// Di StockController
$stock = DB::table('product_warehouse')
    ->where('warehouse_id', $warehouseId)
    ->where('product_id', $productId)
    ->first();

echo $stock->quantity; // Langsung akses quantity
```

---

## ✅ Kesimpulan

### Tabel Pivot:
- **Lokasi Migration**: `database/migrations/2025_11_14_061200_create_product_warehouse_table.php`
- **Nama Tabel**: `product_warehouse`
- **Digunakan di**: 
  - `app/Models/Product.php` (method `warehouses()`)
  - `app/Models/Warehouse.php` (method `products()`)
  - `app/Http/Controllers/StockController.php` (query langsung)

### Upsert Pattern:
- **Lokasi**: `app/Http/Controllers/StockController.php` method `store()`
- **Baris**: 83-92
- **Fungsi**: Update jika record ada, Insert jika belum ada
- **Digunakan untuk**: Transfer stok (tambah/kurangi stok produk di gudang)

---

**Catatan**: Upsert pattern ini penting untuk sistem stok karena:
1. Produk bisa masuk gudang pertama kali (perlu INSERT)
2. Produk bisa ditambah/dikurangi stoknya (perlu UPDATE)
3. Tidak perlu cek manual apakah perlu INSERT atau UPDATE


