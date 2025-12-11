# 📚 Dokumentasi Lengkap Sistem Manajemen Produk

## 🎯 Overview Project

Ini adalah **Sistem Manajemen Produk** berbasis **Laravel 12** yang dirancang untuk mengelola:
- **Produk** dengan detail lengkap (nama, harga, kategori, deskripsi, berat, ukuran)
- **Kategori Produk** untuk pengelompokan produk
- **Gudang (Warehouse)** untuk menyimpan lokasi penyimpanan
- **Stok Produk** di setiap gudang dengan sistem transfer stok

### Teknologi yang Digunakan
- **Framework**: Laravel 12 (PHP 8.2+)
- **Database**: SQLite (default) / MySQL / PostgreSQL
- **ORM**: Eloquent ORM
- **View Engine**: Blade Templates

---

## 🏗️ Arsitektur Aplikasi

### 1. **MVC Pattern (Model-View-Controller)**

Aplikasi ini mengikuti pola MVC Laravel:

```
routes/web.php          → Mendefinisikan URL dan routing
    ↓
Controllers/           → Menangani logika bisnis
    ↓
Models/                → Berinteraksi dengan database
    ↓
Views/                 → Menampilkan UI ke user
```

### 2. **Struktur Database**

```
categories (1) ──< (N) products (1) ──< (1) product_details
                          │
                          │ (N)
                          │
                          └──< (N) product_warehouse (N) >── (1) warehouses
```

**Penjelasan Relasi:**
- **One-to-Many**: 1 Kategori memiliki banyak Produk
- **One-to-One**: 1 Produk memiliki 1 Detail Produk
- **Many-to-Many**: Banyak Produk bisa ada di banyak Gudang (melalui tabel pivot `product_warehouse`)

---

## 📋 Penjelasan Detail Setiap Komponen

### 🔀 **ROUTES** (`routes/web.php`)

#### Route Resource (CRUD Otomatis)
```php
Route::resource('categories', CategoryController::class);
Route::resource('warehouses', WarehouseController::class);
Route::resource('products', ProductController::class);
```

**Fitur Khusus**: `Route::resource()` adalah fitur Laravel yang **jarang diketahui** oleh developer pemula. Ini secara otomatis membuat **7 route** sekaligus:

| Method | URL | Controller Method | Deskripsi |
|--------|-----|-------------------|-----------|
| GET | `/categories` | `index()` | Daftar semua kategori |
| GET | `/categories/create` | `create()` | Form tambah kategori |
| POST | `/categories` | `store()` | Simpan kategori baru |
| GET | `/categories/{id}` | `show()` | Detail kategori |
| GET | `/categories/{id}/edit` | `edit()` | Form edit kategori |
| PUT/PATCH | `/categories/{id}` | `update()` | Update kategori |
| DELETE | `/categories/{id}` | `destroy()` | Hapus kategori |

**Keuntungan**: Mengurangi boilerplate code dan memastikan konsistensi naming convention.

#### Route Custom untuk Stok
```php
Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
Route::get('stocks/transfer', [StockController::class, 'create'])->name('stocks.transfer');
Route::post('stocks/transfer', [StockController::class, 'store'])->name('stocks.transfer.store');
```

**Alasan tidak menggunakan resource**: Sistem stok tidak mengikuti pola CRUD standar. Transfer stok adalah operasi khusus yang memerlukan logika bisnis khusus (tambah/kurangi stok, validasi, locking).

---

### 🎮 **CONTROLLERS**

#### 1. **CategoryController** - CRUD Sederhana

**Fitur Standar:**
- CRUD lengkap untuk kategori
- Validasi input
- Flash messages untuk feedback user

**Tidak ada fitur advanced** - controller ini straightforward untuk pembelajaran.

#### 2. **WarehouseController** - CRUD Sederhana

**Catatan Penting:**
- Method `show()` belum diimplementasikan (body kosong)
- Ini adalah contoh method yang **tidak digunakan** dalam aplikasi ini

#### 3. **ProductController** - CRUD dengan Transaction

**Fitur Advanced yang Digunakan:**

##### a. **Database Transaction** (`DB::transaction()`)
```php
DB::transaction(function () use ($data) {
    $product = Product::create([...]);
    $product->detail()->create([...]);
});
```

**Mengapa Penting?**
- Memastikan **atomicity**: Jika pembuatan detail gagal, produk juga tidak akan tersimpan
- Mencegah data tidak konsisten di database
- Jika ada error, semua perubahan di-rollback otomatis

**Contoh Skenario:**
```
Tanpa Transaction:
1. Product berhasil dibuat (ID: 5)
2. ProductDetail gagal dibuat (error)
3. Hasil: Product ada tapi tidak punya detail ❌

Dengan Transaction:
1. Mulai transaction
2. Product dibuat (ID: 5)
3. ProductDetail gagal dibuat (error)
4. Rollback otomatis
5. Hasil: Tidak ada data yang tersimpan ✅
```

##### b. **Eager Loading** (`with()` dan `load()`)
```php
// Di index()
$products = Product::with('category')->orderBy('name')->paginate(10);

// Di show()
$product->load(['category', 'detail', 'warehouses']);
```

**Mengapa Penting?**
- Mencegah **N+1 Query Problem**

**Tanpa Eager Loading:**
```php
$products = Product::all(); // 1 query
foreach ($products as $product) {
    echo $product->category->name; // N query (1 per produk)
}
// Total: 1 + N queries ❌
```

**Dengan Eager Loading:**
```php
$products = Product::with('category')->get(); // 2 queries (1 untuk products, 1 untuk categories)
foreach ($products as $product) {
    echo $product->category->name; // 0 query (sudah di-load)
}
// Total: 2 queries ✅
```

##### c. **Route Model Binding**
```php
public function show(Product $product)
{
    // Laravel otomatis mencari Product berdasarkan ID dari URL
    // Jika tidak ditemukan, otomatis return 404
}
```

**Keuntungan:**
- Tidak perlu manual `Product::findOrFail($id)`
- Otomatis handle 404 jika tidak ditemukan
- Kode lebih clean dan readable

#### 4. **StockController** - Fitur Paling Advanced

**Fitur-Fitur Advanced yang Digunakan:**

##### a. **Pessimistic Locking** (`lockForUpdate()`)
```php
$stock = DB::table('product_warehouse')
    ->where('warehouse_id', $data['warehouse_id'])
    ->where('product_id', $data['product_id'])
    ->lockForUpdate() // 🔒 MENGUNCI ROW INI
    ->first();
```

**Apa itu Pessimistic Locking?**
- Mengunci row di database saat dibaca
- Row lain yang mencoba update harus menunggu
- Mencegah **race condition** dan **lost update**

**Contoh Masalah Tanpa Locking:**
```
User A membaca stok: quantity = 10
User B membaca stok: quantity = 10
User A update: 10 + 5 = 15
User B update: 10 + 3 = 13
Hasil akhir: 13 (seharusnya 18) ❌
```

**Dengan Locking:**
```
User A membaca stok: quantity = 10 (LOCKED)
User B menunggu...
User A update: 10 + 5 = 15 (UNLOCK)
User B membaca stok: quantity = 15 (LOCKED)
User B update: 15 + 3 = 18 (UNLOCK)
Hasil akhir: 18 ✅
```

**Kapan Digunakan?**
- Operasi financial (transfer uang, stok)
- Data yang sering di-update bersamaan
- Sistem dengan banyak concurrent users

##### b. **Database Transaction dengan Validasi Custom**
```php
DB::transaction(function () use ($data) {
    // ... locking dan update ...
    
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
});
```

**Fitur Khusus:**
- `ValidationException::withMessages()` - Melempar error validasi custom di dalam transaction
- Error ini akan di-catch oleh Laravel dan ditampilkan di form
- Transaction otomatis di-rollback jika exception dilempar

##### c. **Query Builder vs Eloquent**
```php
// Menggunakan Query Builder langsung (bukan Eloquent)
DB::table('product_warehouse')
    ->where('warehouse_id', $data['warehouse_id'])
    ->update(['quantity' => $newQty]);
```

**Mengapa Query Builder?**
- Lebih cepat untuk operasi sederhana
- Tidak perlu load model ke memory
- Cocok untuk update langsung tanpa perlu relasi

**Kapan Pakai Query Builder vs Eloquent?**
- **Query Builder**: Operasi bulk, update sederhana, tidak perlu relasi
- **Eloquent**: Butuh relasi, validasi model, events, accessors/mutators

##### d. **Upsert Pattern** (Update or Insert)
```php
if ($stock) {
    // Update jika sudah ada
    DB::table('product_warehouse')
        ->where('id', $stock->id)
        ->update(['quantity' => $newQty]);
} else {
    // Insert jika belum ada
    DB::table('product_warehouse')->insert([...]);
}
```

**Pattern ini umum digunakan untuk:**
- Tabel pivot yang mungkin belum ada record
- Sistem stok (pertama kali produk masuk gudang)
- Logging/audit trail

---

### 📊 **MODELS** - Eloquent ORM

#### 1. **Relasi Database**

##### a. **One-to-Many: Category → Products**
```php
// Category.php
public function products() {
    return $this->hasMany(Product::class);
}

// Product.php
public function category() {
    return $this->belongsTo(Category::class);
}
```

**Cara Kerja:**
- `hasMany`: Satu kategori punya banyak produk
- `belongsTo`: Satu produk milik satu kategori
- Laravel otomatis mencari kolom `category_id` di tabel `products`

##### b. **One-to-One: Product → ProductDetail**
```php
// Product.php
public function detail() {
    return $this->hasOne(ProductDetail::class);
}

// ProductDetail.php
public function product() {
    return $this->belongsTo(Product::class);
}
```

**Cara Kerja:**
- `hasOne`: Satu produk punya satu detail
- `belongsTo`: Satu detail milik satu produk
- Kolom `product_id` di `product_details` memiliki constraint `UNIQUE`

**Mengapa Dipisah?**
- Normalisasi database (3NF)
- Detail produk bisa NULL (optional)
- Memudahkan query (tidak perlu load detail jika tidak diperlukan)

##### c. **Many-to-Many: Product ↔ Warehouse**
```php
// Product.php
public function warehouses() {
    return $this->belongsToMany(Warehouse::class, 'product_warehouse')
                ->withPivot('quantity');
}

// Warehouse.php
public function products() {
    return $this->belongsToMany(Product::class, 'product_warehouse')
                ->withPivot('quantity');
}
```

**Fitur Khusus: `withPivot('quantity')`**
- Menambahkan kolom dari tabel pivot ke hasil query
- Tanpa ini, kolom `quantity` tidak bisa diakses

**Contoh Penggunaan:**
```php
$product = Product::find(1);
foreach ($product->warehouses as $warehouse) {
    echo $warehouse->pivot->quantity; // ✅ Bisa akses quantity
}
```

**Tanpa `withPivot()`:**
```php
echo $warehouse->pivot->quantity; // ❌ Error: property tidak ada
```

#### 2. **Mass Assignment Protection**

```php
protected $fillable = ['name', 'price', 'category_id'];
```

**Mengapa Penting?**
- Mencegah **mass assignment vulnerability**
- Hanya field yang ada di `$fillable` yang bisa diisi via `create()` atau `update()`

**Contoh Vulnerability:**
```php
// Tanpa $fillable
$product = Product::create($request->all());
// User bisa kirim: ['name' => 'Product', 'is_admin' => true]
// Jika ada kolom is_admin, akan terisi! ❌

// Dengan $fillable
$product = Product::create($request->all());
// Hanya name, price, category_id yang bisa diisi ✅
```

---

### 🗄️ **MIGRATIONS** - Database Schema

#### 1. **Foreign Key Constraints**

##### a. **Cascade Delete**
```php
$table->foreignId('product_id')
      ->constrained('products')
      ->cascadeOnDelete();
```

**Artinya:**
- Jika produk dihapus, semua record di `product_details` juga terhapus otomatis
- Mencegah orphaned records (data yang tidak punya parent)

##### b. **Null on Delete**
```php
$table->foreignId('category_id')
      ->nullable()
      ->constrained('categories')
      ->nullOnDelete();
```

**Artinya:**
- Jika kategori dihapus, `category_id` di produk menjadi `NULL`
- Produk tidak ikut terhapus, hanya kehilangan kategori

**Kapan Pakai Cascade vs Null?**
- **Cascade**: Data yang tidak berarti tanpa parent (detail produk)
- **Null**: Data yang masih berguna tanpa parent (produk tanpa kategori)

#### 2. **Unique Constraint**

```php
// Di product_details
$table->foreignId('product_id')->unique();

// Di product_warehouse
$table->unique(['product_id', 'warehouse_id']);
```

**Composite Unique:**
- Satu produk di satu gudang hanya bisa punya satu record
- Mencegah duplikasi data stok

#### 3. **Data Types**

```php
$table->decimal('price', 15, 2);  // DECIMAL(15,2) - untuk uang
$table->decimal('weight', 8, 2);  // DECIMAL(8,2) - untuk berat
$table->integer('quantity');      // INTEGER - untuk jumlah
```

**Mengapa Decimal untuk Uang?**
- `float` dan `double` punya masalah precision (0.1 + 0.2 = 0.30000000004)
- `decimal` presisi tetap, cocok untuk financial data

---

## 🔍 Fitur-Fitur yang Jarang Digunakan / Advanced

### 1. **Route Model Binding dengan Type Hinting**

```php
public function show(Product $product)
```

**Fitur Ini:**
- Laravel otomatis resolve model dari URL parameter
- Jika `{product}` di URL adalah `5`, Laravel otomatis cari `Product::find(5)`
- Jika tidak ditemukan, return 404 otomatis

**Tanpa Route Model Binding:**
```php
public function show($id)
{
    $product = Product::findOrFail($id);
    // ...
}
```

**Dengan Route Model Binding:**
```php
public function show(Product $product)
{
    // $product sudah tersedia, tidak perlu findOrFail
}
```

### 2. **Compact() Helper Function**

```php
return view('products.index', compact('products'));
```

**Apa itu `compact()`?**
- PHP built-in function
- Membuat array dari variabel dengan nama yang sama
- Equivalent dengan: `['products' => $products]`

**Keuntungan:**
- Lebih ringkas
- Tidak perlu menulis nama variabel dua kali

### 3. **Null Coalescing Operator (`??`)**

```php
$category_id = $data['category_id'] ?? null;
```

**Artinya:**
- Jika `$data['category_id']` ada dan tidak null, gunakan nilainya
- Jika tidak ada atau null, gunakan `null`

**Equivalent dengan:**
```php
$category_id = isset($data['category_id']) ? $data['category_id'] : null;
```

### 4. **Flash Messages dengan `with()`**

```php
return redirect()->route('products.index')
                 ->with('success', 'Produk berhasil ditambahkan.');
```

**Cara Kerja:**
- Data disimpan di session
- Hanya tersedia untuk request berikutnya
- Otomatis dihapus setelah dibaca

**Di View:**
```blade
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
```

### 5. **Pagination**

```php
$products = Product::orderBy('name')->paginate(10);
```

**Fitur:**
- Otomatis membagi data menjadi halaman
- 10 item per halaman
- Generate link pagination otomatis

**Di View:**
```blade
{{ $products->links() }}
```

### 6. **Query Builder Methods**

#### `select()` - Memilih kolom spesifik
```php
Product::select('products.*', 'product_warehouse.quantity')
```

#### `join()` - Join tabel
```php
->join('product_warehouse', 'products.id', '=', 'product_warehouse.product_id')
```

#### `where()` - Filter data
```php
->where('product_warehouse.warehouse_id', $warehouseId)
```

#### `orderBy()` - Sorting
```php
->orderBy('products.name')
```

### 7. **Collection Methods**

```php
$stocks = collect(); // Membuat collection kosong
```

**Collection vs Array:**
- Collection punya banyak helper methods
- Bisa di-chain: `$collection->filter()->map()->sort()`
- Lebih powerful daripada array biasa

---

## 🔄 Alur Kerja Aplikasi

### 1. **Alur CRUD Produk**

```
User mengakses /products/create
    ↓
ProductController@create
    ↓
Ambil semua kategori untuk dropdown
    ↓
Tampilkan form (view: products.create)
    ↓
User submit form → POST /products
    ↓
ProductController@store
    ↓
Validasi input
    ↓
DB::transaction {
    - Buat Product
    - Buat ProductDetail
}
    ↓
Redirect ke /products dengan flash message
```

### 2. **Alur Transfer Stok**

```
User mengakses /stocks/transfer
    ↓
StockController@create
    ↓
Ambil semua warehouse dan products
    ↓
Tampilkan form transfer
    ↓
User submit form → POST /stocks/transfer
    ↓
StockController@store
    ↓
Validasi input
    ↓
DB::transaction {
    1. Lock row di product_warehouse (lockForUpdate)
    2. Baca quantity saat ini
    3. Hitung quantity baru
    4. Validasi:
       - Tidak bisa kurangi jika belum ada stok
       - Tidak boleh minus
    5. Update atau Insert (upsert pattern)
}
    ↓
Redirect ke /stocks dengan filter warehouse
```

---

## ⚠️ Catatan Penting untuk Developer

### 1. **Method yang Belum Diimplementasikan**

- `WarehouseController@show()` - Body kosong
- `StockController@show()` - Body kosong
- `StockController@edit()` - Body kosong
- `StockController@update()` - Body kosong
- `StockController@destroy()` - Body kosong

**Ini normal** - tidak semua method resource route harus diimplementasikan jika tidak digunakan.

### 2. **Best Practices yang Sudah Diterapkan**

✅ **Database Transactions** untuk operasi multi-table  
✅ **Eager Loading** untuk mencegah N+1 queries  
✅ **Pessimistic Locking** untuk operasi stok  
✅ **Mass Assignment Protection** dengan `$fillable`  
✅ **Foreign Key Constraints** untuk data integrity  
✅ **Validasi Input** di controller  
✅ **Flash Messages** untuk user feedback  

### 3. **Potensi Improvement**

🔸 **Service Layer**: Pindahkan logika bisnis dari controller ke service class  
🔸 **Form Requests**: Pindahkan validasi ke Form Request class  
🔸 **Repository Pattern**: Abstraksi database operations  
🔸 **Event & Listeners**: Untuk logging atau notifikasi  
🔸 **Queue Jobs**: Untuk operasi yang memakan waktu  
🔸 **API Resources**: Jika perlu API endpoint  

---

## 📖 Kesimpulan

Project ini adalah contoh implementasi **Laravel MVC** yang baik dengan:
- Struktur database yang normalized
- Relasi yang tepat (one-to-many, one-to-one, many-to-many)
- Penggunaan advanced features (transactions, locking, eager loading)
- Best practices Laravel

Cocok untuk pembelajaran dan bisa dikembangkan lebih lanjut sesuai kebutuhan bisnis.

---

**Dibuat oleh**: AI Assistant  
**Tanggal**: 2025  
**Framework**: Laravel 12  
**PHP Version**: 8.2+

