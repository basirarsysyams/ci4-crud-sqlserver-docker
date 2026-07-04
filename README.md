# AppStarter - CodeIgniter 4 CRUD SQL Server

A simple CRUD application built using **CodeIgniter 4**, **SQL Server**, and **Docker**.

## 🌟 Fitur Utama

- ✅ **CRUD Lengkap** - Tambah, Edit, Hapus produk dengan modal popup
- ✅ **DataTables Server-Side** - Pencarian, sorting, dan pagination real-time via Ajax
- ✅ **Relasi JOIN** - Produk terhubung dengan kategori menggunakan SQL JOIN
- ✅ **Full Ajax** - Semua operasi tanpa reload halaman (SPA-like experience)
- ✅ **SweetAlert2** - Notifikasi dan konfirmasi yang modern & interaktif
- ✅ **Validasi Form** - Validasi di sisi server dengan error message per-field
- ✅ **CSRF Protection** - Keamanan request dengan token CSRF
- ✅ **Responsive Design** - Tampilan optimal di desktop dan mobile

---

| Teknologi           | Deskripsi                                    |
| ------------------- | -------------------------------------------- |
| **Backend**         | CodeIgniter 4 (PHP 8.3+)                     |
| **Database**        | Microsoft SQL Server (SQLSRV Driver)         |
| **Frontend**        | HTML5, CSS3, Vanilla JavaScript              |
| **Library JS**      | jQuery 3.7.1, DataTables 1.13.7, SweetAlert2 |
| **Web Server**      | Apache / Nginx                               |
| **Package Manager** | Composer                                     |

---

## 📁 Struktur Project

```
appstarter/
├── app/
│   ├── Config/
│   │   ├── Routes.php          # Routing aplikasi
│   │   ├── Filters.php         # Filter (CSRF exclude)
│   │   └── Database.php        # Konfigurasi DB
│   ├── Controllers/
│   │   └── ProductController.php   # Logic CRUD & Ajax
│   ├── Models/
│   │   ├── ProductModel.php        # Query JOIN & DataTables
│   │   └── CategoryModel.php       # Model kategori
│   └── Views/
│       ├── layouts/
│       │   └── main.php            # Layout utama
│       └── products/
│           └── index.php           # View DataTables + Modal
├── public/
│   └── index.php               # Entry point
├── writable/                   # Log & cache
├── .env                        # Environment variables
└── README.md                   # Dokumentasi ini
```

---

# Installation

## 1 Clone Repository

```bash
git clone https://github.com/username/appstarter.git

cd appstarter
```

---

## 2 Copy Environment atau Membuat file .env yang berisi data berikut ini:

```bash

CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
database.default.hostname = sqlserver
database.default.database = appstarter
database.default.username = sa
database.default.password = YourStrong!Passw0rd123
database.default.DBDriver = SQLSRV
database.default.port = 1433
database.default.encrypt = false
database.default.trustServerCertificate = true
```

## 3. Install Composer Dependencies

> **Penting**
>
> Walaupun Composer sudah terinstall di komputer Anda (`composer --version` berhasil),
> Anda **tetap harus menjalankan** `composer install`.
>
> Hal ini karena folder `vendor/` tidak disimpan di GitHub sehingga seluruh dependency CodeIgniter harus diunduh kembali.

Jalankan pada folder project:

```bash
composer install
```

---

## 4. Build Docker

Jalankan Docker Compose.

```bash
docker compose up -d --build
```

Pastikan kedua container sudah berjalan.

```bash
docker ps
```

Output yang diharapkan:

```
ci4-app
ci4-sqlserver
```

---

## 5. Cek Database SQL Server

Masuk ke SQL Server.

### Windows (CMD / PowerShell)

```cmd
docker exec -it ci4-sqlserver /opt/mssql-tools18/bin/sqlcmd -S localhost -U sa -P "YourStrong!Passw0rd123" -C
```

### macOS / Linux

```bash
docker exec -it ci4-sqlserver /opt/mssql-tools18/bin/sqlcmd \
-S localhost \
-U sa \
-P "YourStrong!Passw0rd123" \
-C
```

---

### Cek apakah database `appstarter` sudah ada

```sql
SELECT name FROM sys.databases;
GO
```

Jika database **appstarter** belum ada, buat terlebih dahulu.

```sql
CREATE DATABASE appstarter;
GO
```

---

## 6. Cek Apakah Tabel Sudah Ada

Masih di dalam SQL Server.

```sql
USE appstarter;
GO

SELECT TABLE_NAME
FROM INFORMATION_SCHEMA.TABLES;
GO
```

Apabila sudah terdapat tabel berikut

```
categories
products
migrations
```

berarti migration sudah pernah dijalankan sehingga **langkah migrate dapat dilewati**.

Jika tabel-tabel tersebut **belum ada**, lanjut ke langkah **Masuk ke Container Aplikasi**.

---

## 7. Cek Apakah Data Seeder Sudah Ada

Masih di SQL Server.

```sql
USE appstarter;
GO

SELECT * FROM categories;
GO

SELECT * FROM products;
GO
```

Jika kedua tabel sudah berisi data, maka **Seeder tidak perlu dijalankan kembali**.

Jika tabel masih kosong, nanti jalankan Seeder pada langkah berikutnya.

---

## 8. Keluar dari SQL Server

```sql
QUIT
```

---

## 9. Masuk ke Container Aplikasi

### Windows

```cmd
docker exec -it ci4-app bash
```

### macOS / Linux

```bash
docker exec -it ci4-app bash
```

---

## 10. Jalankan Migration (Jika Diperlukan)

**Lewati langkah ini apabila tabel `categories`, `products`, dan `migrations` sudah ada.**

Jika tabel belum ada, jalankan:

```bash
php spark migrate
```

---

## 11. Jalankan Seeder (Jika Diperlukan)

**Lewati langkah ini apabila tabel `categories` dan `products` sudah berisi data.**

Jika tabel masih kosong, jalankan:

```bash
php spark db:seed CategorySeeder
php spark db:seed ProductSeeder
```

---

## 12. Buka Aplikasi

Buka browser dan akses:

```
http://localhost:8080/products
```

# 🔍 Lokasi Fitur Utama (JOIN, DataTables, Ajax)

### 1️⃣ **SQL JOIN** - Relasi Products & Categories

**File:** `app/Models/ProductModel.php`

```php
// Method getWithCategory() - Untuk mengambil data edit
public function getWithCategory()
{
    return $this->select('products.*, categories.name as category_name')
        ->join('categories', 'categories.id = products.category_id', 'left')
        ->orderBy('products.id', 'DESC');
}

// Method getDataTables() - Untuk DataTables server-side
$builder = $this->db->table('products')
    ->select('products.id, products.name, products.price, categories.name as category_name')
    ->join('categories', 'categories.id = products.category_id', 'left');
```

**Penjelasan:**

- Menggunakan `LEFT JOIN` untuk tetap menampilkan produk meskipun tidak memiliki kategori
- Relasi `products.category_id` → `categories.id`
- Mengambil kolom `categories.name` dengan alias `category_name`

---

### 2️⃣ **DataTables Server-Side Processing**

**Frontend (Client):** `app/Views/products/index.php`

```javascript
table = $('#products-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '<?= base_url('products/ajax-list') ?>',
        type: 'POST'
    },
    columns: [
        { data: null, /* No urut */ },
        { data: 'name' },
        { data: 'category_name' },
        { data: 'price_formatted' },
        { data: null, /* Action buttons */ }
    ],
    // ... konfigurasi lainnya
});
```

**Backend (Server):** `app/Models/ProductModel.php` → `getDataTables()`

```php
public function getDataTables($request)
{
    // 1. Total records
    $totalRecords = $this->db->table('products')->countAllResults();

    // 2. Search/Filter
    if (!empty($keyword)) {
        $builder->groupStart()
            ->like('products.name', $keyword)
            ->orLike('categories.name', $keyword)
        ->groupEnd();
    }

    // 3. Order/Sorting
    $builder->orderBy($orderColumn, $orderDir);

    // 4. Pagination
    $data = $builder->get($length, $start)->getResultArray();

    return [
        'draw'            => (int) $request->getPost('draw'),
        'recordsTotal'    => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data'            => $data,
    ];
}
```

**Route:** `app/Config/Routes.php`

```php
$routes->post('products/ajax-list', 'ProductController::ajaxList');
```

---

### 3️⃣ **Ajax Operations (Full CRUD)**

**File:** `app/Controllers/ProductController.php` & `app/Views/products/index.php`

| Operasi                   | Endpoint                     | Method | File Controller   |
| ------------------------- | ---------------------------- | ------ | ----------------- |
| **Read All** (DataTables) | `/products/ajax-list`        | POST   | `ajaxList()`      |
| **Read One** (Edit)       | `/products/ajax-get/{id}`    | GET    | `ajaxGet($id)`    |
| **Create**                | `/products/ajax-store`       | POST   | `ajaxStore()`     |
| **Update**                | `/products/ajax-update/{id}` | POST   | `ajaxUpdate($id)` |
| **Delete**                | `/products/ajax-delete/{id}` | POST   | `ajaxDelete($id)` |

**Contoh Ajax Create (JavaScript):**

```javascript
$('#product-form').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '<?= base_url('products/ajax-store') ?>',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                table.ajax.reload(null, false);
                Swal.fire('Berhasil!', response.message, 'success');
            }
        }
    });
});
```

---

# Database Structure

## categories

| Field       | Type    |
| ----------- | ------- |
| id          | int     |
| name        | varchar |
| description | text    |

---

## products

| Field       | Type    |
| ----------- | ------- |
| id          | int     |
| category_id | int     |
| name        | varchar |
| price       | int     |

---

# Author

Basir Arsy
