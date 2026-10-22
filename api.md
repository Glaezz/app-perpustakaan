# Dokumentasi REST API - Aplikasi Perpustakaan

Dokumentasi ini berisi seluruh endpoint REST API yang dibangun pada **Pertemuan 9** untuk resource `books`, `members`, `loans`, dan `stats`.

> Semua contoh response di bawah ini adalah **respons asli dari server** (`php artisan serve` di `http://127.0.0.1:8000`), diambil dengan request `Accept: application/json` - bukan contoh yang dikarang manual.

## Informasi Umum

| Item | Keterangan |
|---|---|
| Base URL | `http://127.0.0.1:8000/api` |
| Format response | JSON |
| Header wajib | `Accept: application/json` |
| Autentikasi | Belum ada (endpoint publik, lihat catatan di bawah) |
| Prefix route | `/api` (didaftarkan lewat `php artisan install:api` -> `routes/api.php`) |
| Jumlah endpoint | 11 route |

> **Catatan soal proteksi API:** middleware `auth` yang berbasis session browser tidak otomatis berlaku untuk `routes/api.php`. Autentikasi API berbasis token (Laravel Sanctum) adalah topik terpisah di luar cakupan Pertemuan 9, sehingga seluruh endpoint di bawah ini **sengaja dibiarkan publik**.

> **Catatan mode debug:** pada file `.env` lokal, `APP_DEBUG=true` membuat Laravel menyertakan field `exception`, `file`, `line`, dan `trace` pada response error. Field-field tersebut tidak ada di environment produksi (`APP_DEBUG=false`); contoh di bawah ini ditulis dalam bentuk produksi.

---

## Daftar Endpoint

| Method | URL | Body (jika ada) | Contoh Response | Status Sukses | Status Gagal |
|---|---|---|---|---|---|
| GET | `/api/books` | - | `{"data":[...], "links":{...}, "meta":{...}}` | 200 | - |
| GET | `/api/books/{id}` | - | `{"data":{"id":1,"judul":"Naruto",...}}` | 200 | 404 |
| POST | `/api/books` | `judul`, `penulis`, `penerbit`, `tahun_terbit`, `isbn`, `stok`, `category_id` | `{"data":{"id":2,"judul":"Dokumentasi API",...}}` | 201 | 422 |
| PUT | `/api/books/{id}` | `judul`, `penulis`, `penerbit`, `tahun_terbit`, `isbn`, `stok`, `category_id` | `{"data":{"id":2,"judul":"Dokumentasi API (Revisi)",...}}` | 200 | 404, 422 |
| DELETE | `/api/books/{id}` | - | `{"message":"Buku berhasil dihapus."}` | 200 | 404 |
| GET | `/api/members` | - | `{"data":[...], "links":{...}, "meta":{...}}` | 200 | - |
| GET | `/api/members/{id}` | - | `{"data":{"id":1,"nama":"Fieza",...}}` | 200 | 404 |
| GET | `/api/loans` | - | `{"data":[{"member":{...},"petugas":{...},"books":[...]}]}` | 200 | - |
| POST | `/api/loans` | `member_id`, `user_id`, `tanggal_pinjam`, `tanggal_kembali`, `book_ids[]` | `{"data":{"id":3,"status":"dipinjam",...}}` | 201 | 422 |
| PUT | `/api/loans/{id}/kembalikan` | - | `{"data":{"id":3,"status":"dikembalikan",...}}` | 200 | 404 |
| GET | `/api/stats` | - | `{"total_buku":1,"total_anggota":1,"peminjaman_aktif":0}` | 200 | - |

---

## Resource: Books

Endpoint ini memakai `BookResource`, sehingga field kategori selalu dikirim dalam bentuk nested (`category.id` dan `category.nama_kategori`).

### GET `/api/books`

Mengembalikan daftar buku beserta kategorinya, dengan filter opsional dan pagination 10 item per halaman.

**Query string (opsional):**

| Parameter | Tipe | Keterangan |
|---|---|---|
| `judul` | string | Pencarian sebagian judul (SQL `LIKE`) |
| `category_id` | integer | Pencocokan persis kategori |
| `page` | integer | Nomor halaman |

**Contoh request:**

```bash
curl -H "Accept: application/json" "http://127.0.0.1:8000/api/books"
curl -H "Accept: application/json" "http://127.0.0.1:8000/api/books?judul=nar"
curl -H "Accept: application/json" "http://127.0.0.1:8000/api/books?category_id=1"
```

**Response `200`:**

```json
{
  "data": [
    {
      "id": 1,
      "judul": "Naruto",
      "penulis": "Masashi",
      "penerbit": "kurang taw",
      "tahun_terbit": "1999",
      "isbn": null,
      "stok": 1,
      "sampul": null,
      "category": {
        "id": 1,
        "nama_kategori": "Fiksi"
      },
      "created_at": "2026-09-30T02:32:32.000000Z",
      "updated_at": "2026-09-30T02:32:32.000000Z"
    }
  ],
  "links": {
    "first": "http://127.0.0.1:8000/api/books?page=1",
    "last": "http://127.0.0.1:8000/api/books?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "links": [
      { "url": null, "label": "&laquo; Previous", "page": null, "active": false },
      { "url": "http://127.0.0.1:8000/api/books?page=1", "label": "1", "page": 1, "active": true },
      { "url": null, "label": "Next &raquo;", "page": null, "active": false }
    ],
    "path": "http://127.0.0.1:8000/api/books",
    "per_page": 10,
    "to": 1,
    "total": 1
  }
}
```

Response `GET /api/books?judul=nar` dan `GET /api/books?category_id=1` identik dengan contoh di atas, karena buku yang tersedia memang cocok dengan kedua filter tersebut.

### GET `/api/books/{id}`

Menampilkan satu buku beserta kategorinya. `404` otomatis diberikan Laravel jika `id` tidak ditemukan (`findOrFail`).

**Response `200`:**

```json
{
  "data": {
    "id": 1,
    "judul": "Naruto",
    "penulis": "Masashi",
    "penerbit": "kurang taw",
    "tahun_terbit": "1999",
    "isbn": null,
    "stok": 1,
    "sampul": null,
    "category": {
      "id": 1,
      "nama_kategori": "Fiksi"
    },
    "created_at": "2026-09-30T02:32:32.000000Z",
    "updated_at": "2026-09-30T02:32:32.000000Z"
  }
}
```

**Response `404` (`GET /api/books/999`):**

```json
{
  "message": "No query results for model [App\\Models\\Book] 999"
}
```

### POST `/api/books`

Menambah buku baru. Validasi memakai `App\Http\Requests\StoreBookRequest` yang sama dengan form web, sehingga aturan validasi tidak ditulis dua kali.

| Field | Tipe | Wajib | Aturan |
|---|---|---|---|
| `judul` | string | Ya | maks. 200 karakter |
| `penulis` | string | Ya | maks. 100 karakter |
| `penerbit` | string | Ya | maks. 100 karakter |
| `tahun_terbit` | integer | Ya | 1900 s.d. tahun sekarang |
| `isbn` | string | Tidak | maks. 20 karakter |
| `stok` | integer | Ya | minimal 0 |
| `category_id` | integer | Ya | harus ada di tabel `categories` |

**Contoh request:**

```bash
curl -X POST "http://127.0.0.1:8000/api/books" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "judul": "Dokumentasi API",
    "penulis": "Tim Framework",
    "penerbit": "Penerbit Contoh",
    "tahun_terbit": 2024,
    "isbn": "978-602-000-000-1",
    "stok": 5,
    "category_id": 1
  }'
```

**Response `201`:**

```json
{
  "data": {
    "id": 2,
    "judul": "Dokumentasi API",
    "penulis": "Tim Framework",
    "penerbit": "Penerbit Contoh",
    "tahun_terbit": 2024,
    "isbn": "978-602-000-000-1",
    "stok": 5,
    "sampul": null,
    "category": {
      "id": 1,
      "nama_kategori": "Fiksi"
    },
    "created_at": "2026-10-22T02:40:12.000000Z",
    "updated_at": "2026-10-22T02:40:12.000000Z"
  }
}
```

**Response `422` (body `{}`):**

```json
{
  "message": "Judul buku wajib diisi. (and 5 more errors)",
  "errors": {
    "judul": ["Judul buku wajib diisi."],
    "penulis": ["Nama penulis wajib diisi."],
    "penerbit": ["Nama penerbit wajib diisi."],
    "tahun_terbit": ["Tahun terbit wajib diisi."],
    "stok": ["Stok wajib diisi."],
    "category_id": ["Kategori wajib dipilih."]
  }
}
```

### PUT `/api/books/{id}`

Mengubah data buku. Validasi memakai bentuk inline (`$request->validate()`), bukan Form Request.

**Contoh request:**

```bash
curl -X PUT "http://127.0.0.1:8000/api/books/2" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "judul": "Dokumentasi API (Revisi)",
    "penulis": "Tim Framework",
    "penerbit": "Penerbit Contoh",
    "tahun_terbit": 2025,
    "isbn": "978-602-000-000-1",
    "stok": 7,
    "category_id": 1
  }'
```

**Response `200`:**

```json
{
  "data": {
    "id": 2,
    "judul": "Dokumentasi API (Revisi)",
    "penulis": "Tim Framework",
    "penerbit": "Penerbit Contoh",
    "tahun_terbit": 2025,
    "isbn": "978-602-000-000-1",
    "stok": 7,
    "sampul": null,
    "category": {
      "id": 1,
      "nama_kategori": "Fiksi"
    },
    "created_at": "2026-10-22T02:40:12.000000Z",
    "updated_at": "2026-10-22T02:40:12.000000Z"
  }
}
```

### DELETE `/api/books/{id}`

Menghapus buku. Tidak memakai API Resource karena tidak ada data buku yang perlu diformat lagi setelah dihapus.

**Contoh request:**

```bash
curl -X DELETE "http://127.0.0.1:8000/api/books/2" -H "Accept: application/json"
```

**Response `200`:**

```json
{
  "message": "Buku berhasil dihapus."
}
```

---

## Resource: Members

Endpoint `members` bersifat **read-only** pada pertemuan ini - hanya ada operasi `GET` (daftar dan detail).

### GET `/api/members`

Mengembalikan daftar anggota dengan pagination 10 item per halaman.

**Contoh request:**

```bash
curl -H "Accept: application/json" "http://127.0.0.1:8000/api/members"
```

**Response `200`:**

```json
{
  "data": [
    {
      "id": 1,
      "nama": "Fieza",
      "nim": "3125600002",
      "email": "fiezarausyan@gmail.com",
      "nomor_telepon": "085295006927",
      "alamat": "Demak Timur",
      "status": "aktif",
      "created_at": "2026-09-30T02:33:33.000000Z",
      "updated_at": "2026-09-30T02:33:33.000000Z"
    }
  ],
  "links": {
    "first": "http://127.0.0.1:8000/api/members?page=1",
    "last": "http://127.0.0.1:8000/api/members?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "links": [
      { "url": null, "label": "&laquo; Previous", "page": null, "active": false },
      { "url": "http://127.0.0.1:8000/api/members?page=1", "label": "1", "page": 1, "active": true },
      { "url": null, "label": "Next &raquo;", "page": null, "active": false }
    ],
    "path": "http://127.0.0.1:8000/api/members",
    "per_page": 10,
    "to": 1,
    "total": 1
  }
}
```

### GET `/api/members/{id}`

Menampilkan detail satu anggota.

**Contoh request:**

```bash
curl -H "Accept: application/json" "http://127.0.0.1:8000/api/members/1"
```

**Response `200`:**

```json
{
  "data": {
    "id": 1,
    "nama": "Fieza",
    "nim": "3125600002",
    "email": "fiezarausyan@gmail.com",
    "nomor_telepon": "085295006927",
    "alamat": "Demak Timur",
    "status": "aktif",
    "created_at": "2026-09-30T02:33:33.000000Z",
    "updated_at": "2026-09-30T02:33:33.000000Z"
  }
}
```

**Response `404` (`GET /api/members/999`):**

```json
{
  "message": "No query results for model [App\\Models\\Member] 999"
}
```

---

## Resource: Loans

`LoanResource` mengubah tiga relasi menjadi struktur nested: `member`, `user` (displays as `petugas`), dan `loanItems.book` (displays as `books`).

### GET `/api/loans`

Mengembalikan seluruh transaksi peminjaman dengan relasi `member`, `user`, dan `loanItems.book` di-eager load agar tidak terjadi N+1 Query Problem.

**Contoh request:**

```bash
curl -H "Accept: application/json" "http://127.0.0.1:8000/api/loans"
```

**Response `200`:**

```json
{
  "data": [
    {
      "id": 2,
      "member": {
        "id": 1,
        "nama": "Fieza"
      },
      "petugas": {
        "id": 1,
        "name": "Chairul"
      },
      "tanggal_pinjam": "2026-09-30",
      "tanggal_kembali": "2026-10-02",
      "tanggal_dikembalikan": "2026-09-30",
      "status": "dikembalikan",
      "books": [
        {
          "id": 1,
          "judul": "Naruto"
        }
      ]
    },
    {
      "id": 3,
      "member": {
        "id": 1,
        "nama": "Fieza"
      },
      "petugas": {
        "id": 1,
        "name": "Chairul"
      },
      "tanggal_pinjam": "2026-10-22",
      "tanggal_kembali": "2026-10-29",
      "tanggal_dikembalikan": "2026-10-22",
      "status": "dikembalikan",
      "books": [
        {
          "id": 1,
          "judul": "Naruto"
        }
      ]
    }
  ],
  "links": {
    "first": "http://127.0.0.1:8000/api/loans?page=1",
    "last": "http://127.0.0.1:8000/api/loans?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "links": [
      { "url": null, "label": "&laquo; Previous", "page": null, "active": false },
      { "url": "http://127.0.0.1:8000/api/loans?page=1", "label": "1", "page": 1, "active": true },
      { "url": null, "label": "Next &raquo;", "page": null, "active": false }
    ],
    "path": "http://127.0.0.1:8000/api/loans",
    "per_page": 10,
    "to": 2,
    "total": 2
  }
}
```

### POST `/api/loans`

Mencatat transaksi peminjaman baru. Satu transaksi bisa mencakup banyak buku lewat `book_ids[]`.

Karena API ini berjalan tanpa session `auth()`, `user_id` (petugas pencatat) **wajib dikirim eksplisit** di body request - berbeda dengan versi web yang mengambilnya otomatis dari `auth()->id()`.

| Field | Tipe | Wajib | Aturan |
|---|---|---|---|
| `member_id` | integer | Ya | harus ada di tabel `members` |
| `user_id` | integer | Ya | harus ada di tabel `users` |
| `tanggal_pinjam` | date | Ya | format `YYYY-MM-DD` |
| `tanggal_kembali` | date | Ya | tidak boleh sebelum `tanggal_pinjam` |
| `book_ids` | array | Ya | minimal 1 buku |
| `book_ids.*` | integer | Ya | harus ada di tabel `books` |

**Contoh request:**

```bash
curl -X POST "http://127.0.0.1:8000/api/loans" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "member_id": 1,
    "user_id": 1,
    "tanggal_pinjam": "2026-10-22",
    "tanggal_kembali": "2026-10-29",
    "book_ids": [1]
  }'
```

**Response `201`:**

```json
{
  "data": {
    "id": 3,
    "member": {
      "id": 1,
      "nama": "Fieza"
    },
    "petugas": {
      "id": 1,
      "name": "Chairul"
    },
    "tanggal_pinjam": "2026-10-22",
    "tanggal_kembali": "2026-10-29",
    "tanggal_dikembalikan": null,
    "status": "dipinjam",
    "books": [
      {
        "id": 1,
        "judul": "Naruto"
      }
    ]
  }
}
```

`status` bernilai `"dipinjam"` (bukan `null`) karena controller memanggil `$loan->refresh()` sebelum memuat relasi - objek hasil `Loan::create()` belum membawa nilai default kolom `status` yang diisi database.

**Response `422` (body `{"member_id": 1}`):**

```json
{
  "message": "Petugas (user_id) wajib diisi. (and 3 more errors)",
  "errors": {
    "user_id": ["Petugas (user_id) wajib diisi."],
    "tanggal_pinjam": ["Tanggal pinjam wajib diisi."],
    "tanggal_kembali": ["Tanggal kembali wajib diisi."],
    "book_ids": ["Pilih minimal satu buku."]
  }
}
```

### PUT `/api/loans/{id}/kembalikan`

Menandai transaksi sebagai sudah dikembalikan: `status` menjadi `"dikembalikan"` dan `tanggal_dikembalikan` diisi dengan tanggal hari ini.

**Contoh request:**

```bash
curl -X PUT "http://127.0.0.1:8000/api/loans/3/kembalikan" -H "Accept: application/json"
```

**Response `200`:**

```json
{
  "data": {
    "id": 3,
    "member": {
      "id": 1,
      "nama": "Fieza"
    },
    "petugas": {
      "id": 1,
      "name": "Chairul"
    },
    "tanggal_pinjam": "2026-10-22",
    "tanggal_kembali": "2026-10-29",
    "tanggal_dikembalikan": "2026-10-22",
    "status": "dikembalikan",
    "books": [
      {
        "id": 1,
        "judul": "Naruto"
      }
    ]
  }
}
```

---

## Resource: Stats

### GET `/api/stats`

Ringkasan angka untuk kartu statistik. Tidak memakai API Resource karena responsnya bukan representasi satu jenis Model.

**Contoh request:**

```bash
curl -H "Accept: application/json" "http://127.0.0.1:8000/api/stats"
```

**Response `200`:**

```json
{
  "total_buku": 1,
  "total_anggota": 1,
  "peminjaman_aktif": 0
}
```

| Field | Keterangan |
|---|---|
| `total_buku` | Jumlah seluruh data buku |
| `total_anggota` | Jumlah seluruh data anggota |
| `peminjaman_aktif` | Jumlah transaksi berstatus `dipinjam` |

Endpoint ini akan dikonsumsi oleh `dashboard.blade.php` pada Pertemuan 10.

---

## Ringkasan Skenarios Gagal

| Skenario | Request | Status | Response |
|---|---|---|---|
| Id buku tidak ditemukan | `GET /api/books/999` | 404 | `{"message":"No query results for model [App\\Models\\Book] 999"}` |
| Id anggota tidak ditemukan | `GET /api/members/999` | 404 | `{"message":"No query results for model [App\\Models\\Member] 999"}` |
| Id transaksi tidak ditemukan | `PUT /api/loans/999/kembalikan` | 404 | `{"message":"No query results for model [App\\Models\\Loan] 999"}` |
| Route tidak terdaftar | `GET /api/loans/999` | 404 | `{"message":"The route api/loans/999 could not be found."}` |
| Body buku kosong | `POST /api/books` | 422 | `{"message":"Judul buku wajib diisi. (and 5 more errors)","errors":{...}}` |
| Body transaksi tidak lengkap | `POST /api/loans` | 422 | `{"message":"Petugas (user_id) wajib diisi. (and 3 more errors)","errors":{...}}` |

---

## Daftar Route (`php artisan route:list --path=api`)

```
  GET|HEAD  api/books                                   Api\BookController@index
  POST      api/books                                   Api\BookController@store
  GET|HEAD  api/books/{id}                              Api\BookController@show
  PUT       api/books/{id}                              Api\BookController@update
  DELETE    api/books/{id}                              Api\BookController@destroy
  GET|HEAD  api/members                                 Api\MemberController@index
  GET|HEAD  api/members/{id}                            Api\MemberController@show
  GET|HEAD  api/loans                                   Api\LoanController@index
  POST      api/loans                                   Api\LoanController@store
  PUT       api/loans/{id}/kembalikan                   Api\LoanController@kembalikan
  GET|HEAD  api/stats                                   Api\StatsController@index
```


