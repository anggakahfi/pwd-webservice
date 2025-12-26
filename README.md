# README - Sistem Pencarian Data Mahasiswa

## Struktur File

```
pwd-webservice/
├── akademik.sql
├── koneksi.php
├── getdatamhs.php
└── akses_mhs.php
```

## 1. akademik.sql

**Tabel mahasiswa:**
- `id`: Primary key auto increment
- `nim`: Nomor Induk Mahasiswa (VARCHAR 20)
- `nama`: Nama lengkap mahasiswa
- `jkel`: Jenis kelamin
- `alamat`: Alamat lengkap
- `tgllhr`: Tanggal lahir
- `created_at`: Timestamp otomatis

**Sample data:** 5 mahasiswa dengan NIM mulai 230018052 - 230018055

## 2. koneksi.php

**Fungsi:** Membuat koneksi ke database MySQL

**Konfigurasi:**
- Host: localhost
- User: root
- Password: kosong
- Database: akademik

**Error handling:** Die jika koneksi gagal

## 3. getdatamhs.php

**Fungsi:** Web service API untuk mengambil data mahasiswa berdasarkan NIM

### Bagian kode:

**Header JSON:**
```php
header('Content-Type: application/json; charset=utf-8');
```
Memastikan response dalam format JSON

**Validasi koneksi:**
```php
if (!isset($conn) || $conn === false)
```
Cek apakah koneksi database berhasil, return HTTP 500 jika gagal

**Validasi parameter:**
```php
if (!isset($_GET['nim']) || empty(trim($_GET['nim'])))
```
Cek parameter NIM ada dan tidak kosong, return HTTP 400 jika invalid

**Prepared statement:**
```php
$sql = "SELECT * FROM mahasiswa WHERE nim = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $nim);
```
Mencegah SQL injection dengan prepared statement

**Eksekusi query:**
```php
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
```
Jalankan query dan ambil hasil

**Response:**
- HTTP 200 + data jika ditemukan
- HTTP 404 jika tidak ditemukan
- HTTP 500 jika error query

**Cleanup:**
```php
mysqli_stmt_close($stmt);
mysqli_close($conn);
```
Tutup statement dan koneksi

## 4. akses_mhs.php

**Fungsi:** Interface web untuk mencari data mahasiswa

### Bagian kode:

**Form input:**
```html
<form method="GET">
    <input type="text" name="nim" value="...">
    <button type="submit">Cari Data</button>
</form>
```
Form untuk input NIM, value dipertahankan setelah submit dengan `htmlspecialchars()`

**Validasi input:**
```php
$nim = trim($_GET['nim']);
if (empty($nim)) {
    echo 'alert warning';
}
```
Cek NIM tidak kosong setelah di-trim

**cURL request:**
```php
$url = "http://localhost/pwd-webservice/getdatamhs.php?nim=" . urlencode($nim);
$client = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
curl_setopt($client, CURLOPT_TIMEOUT, 10);
curl_setopt($client, CURLOPT_CONNECTTIMEOUT, 5);
```
- `urlencode()`: Encode parameter NIM
- `CURLOPT_RETURNTRANSFER`: Return response sebagai string
- `CURLOPT_TIMEOUT`: Timeout 10 detik
- `CURLOPT_CONNECTTIMEOUT`: Timeout koneksi 5 detik

**Error handling cURL:**
```php
if ($response === false) {
    // Koneksi gagal
} elseif ($http_code !== 200) {
    // HTTP error
}
```
Tangani error koneksi dan HTTP non-200

**Parse JSON:**
```php
$result = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    // JSON invalid
}
```
Decode JSON dan validasi

**Tampilkan data:**
```php
if ($result['status'] === "success" && isset($result['data'])) {
    // Tampilkan tabel
    echo htmlspecialchars($mhs['nim'] ?? '');
}
```
Render data dalam tabel Bootstrap dengan `htmlspecialchars()` untuk mencegah XSS

## Cara Penggunaan

1. Import `akademik.sql` ke database MySQL
2. Pastikan konfigurasi `koneksi.php` sesuai
3. Akses `akses_mhs.php` via browser
4. Masukkan NIM (contoh: 230018052)
5. Klik "Cari Data"

## Security Features

- Prepared statement (SQL injection prevention)
- `htmlspecialchars()` (XSS prevention)
- `urlencode()` (URL encoding)
- Input validation dan sanitasi
- Error handling komprehensif
- HTTP status codes yang sesuai
- Timeout cURL untuk mencegah hanging