# 🎸 GudangMusik — Sistem Inventaris Toko Alat Musik

Aplikasi web manajemen inventaris gudang untuk toko alat musik. Dibangun dengan **Laravel 11.57.1** + **MySQL 8.4.3** menggunakan arsitektur **MVC**, dilengkapi autentikasi, role-based access control (RBAC), dan sistem transaksi stok masuk/keluar.

> **UAS Web Application Development** — Universitas Cakrawala  
> **Studi Kasus:** Sistem Manajemen Inventaris Gudang

---

## 👤 Identitas

- **Nama** : [NATALIA METEKOHY]
- **NIM** : [25110300040]
- **Kelas** : [ILMU KOMPUTER]

---

## 🎬 Demo

- **Live URL**: [akan diisi setelah deploy]
- **Video Demo**: [URL YouTube - akan diisi setelah rekam]
- **Repository**: https://github.com/nataliametekohy8-coder/gudangmusik

---

## 🎯 Fitur Utama

### 🔐 Autentikasi & Keamanan
- Login, Register, Logout
- Session regeneration setelah login
- Password hashing dengan bcrypt
- Role-Based Access Control (Admin vs Staff)

### 📊 Dashboard
- Statistik real-time (total produk, nilai stok, low-stock alert)
- Grafik transaksi 7 hari terakhir
- Kartu statistik interaktif

### 📦 CRUD Produk
- Create, Read, Update, Delete produk
- Validasi ketat (SKU unik, harga jual ≥ harga beli, stok ≥ 0)
- Filter kategori & status stok, search by nama/SKU/brand

### 🗂️ CRUD Kategori
- 5 kategori default (Gitar, Drum, Keyboard, Audio, Aksesoris)
- Jumlah produk per kategori
- Proteksi hapus jika masih ada produk

### 🚚 CRUD Supplier
- Data pemasok (nama, telepon, email, alamat)
- Relasi ke produk

### 🔄 Transaksi Stok
- **Stok Masuk (IN)** — auto-tambah stok
- **Stok Keluar (OUT)** — auto-kurangi stok
- **Atomisitas** via `DB::transaction` + `lockForUpdate()`
- **Validasi** stok tidak boleh negatif

---

## 🛠️ Tech Stack

| Komponen | Versi |
|---|---|
| **Framework** | Laravel 11.57.1 |
| **PHP** | 8.3.30 |
| **Database** | MySQL 8.4.3 |
| **Web Server** | Apache 2.4.66 (Laragon) |
| **CSS** | Custom (1260+ baris, tanpa template) |

---

## ⚙️ Persyaratan Sistem

- PHP >= 8.2
- Composer >= 2.7
- MySQL >= 8.0

---

## 🚀 Cara Install

### 1. Clone repository

```bash
git clone https://github.com/nataliametekohy8-coder/gudangmusik.git
cd gudangmusik
