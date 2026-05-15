# Skenario Black-Box Testing (MBS Booking)

Dokumen ini berisi rancangan dan skenario pengujian *Black-Box* untuk sistem peminjaman aset (MBS Booking). Pengujian ini dirancang untuk mencakup seluruh modul utama dan semua *role user* yang ada di dalam sistem (Admin, Kepsek, Guru, dan Staf).

## 1. Modul Autentikasi (Login & Logout)
**Role User:** Semua (Guest, Admin, Kepsek, Guru, Staf)

| ID | Skenario Pengujian | Parameter Input | Hasil yang Diharapkan |
|---|---|---|---|
| AUTH-01 | Login dengan kredensial valid (Admin) | `email`: admin@mbs.sch.id<br>`password`: password | Berhasil login dan dialihkan ke `/admin/dashboard` |
| AUTH-02 | Login dengan kredensial valid (Kepsek) | `email`: kepsek@mbs.sch.id<br>`password`: password | Berhasil login dan dialihkan ke `/admin/dashboard` |
| AUTH-03 | Login dengan kredensial valid (Guru/Staf) | `email`: budi@mbs.sch.id<br>`password`: password | Berhasil login dan dialihkan ke `/dashboard` |
| AUTH-04 | Login dengan email tidak terdaftar | `email`: unknown@mbs.sch.id<br>`password`: password | Gagal login, muncul pesan error kredensial tidak cocok. |
| AUTH-05 | Login dengan password salah | `email`: admin@mbs.sch.id<br>`password`: salah123 | Gagal login, muncul pesan error kredensial tidak cocok. |
| AUTH-06 | Melakukan Logout | N/A (Klik tombol Logout) | Sesi berakhir dan pengguna dikembalikan ke halaman `/login`. |

## 2. Modul Manajemen Aset
**Role User:** Admin, Kepsek

| ID | Skenario Pengujian | Parameter Input | Hasil yang Diharapkan |
|---|---|---|---|
| AST-01 | Menambahkan aset baru dengan data valid | `nama_aset`: "Proyektor Epson"<br>`kategori`: "alat"<br>`status`: "tersedia"<br>`is_restricted_for_student`: false | Aset berhasil disimpan, muncul di tabel daftar aset dengan pesan sukses. |
| AST-02 | Menambahkan aset tanpa mengisi nama aset (Kosong) | `nama_aset`: (kosong)<br>`kategori`: "ruangan"<br>`status`: "tersedia" | Gagal menyimpan, sistem memunculkan validasi error "Nama aset wajib diisi." |
| AST-03 | Mengubah status aset menjadi rusak | *Pilih aset ID 1*<br>`status`: "rusak" | Data aset berhasil diperbarui, status berubah di tabel. |
| AST-04 | Menandai aset sebagai "Restricted" untuk siswa | `is_restricted_for_student`: true (dicentang) | Aset terupdate dan terblokir jika nantinya diajukan untuk peminjaman siswa. |
| AST-05 | Menghapus aset dari sistem | *Klik tombol Hapus pada aset ID 1* | Data aset terhapus dari tabel dan sistem database. |

## 3. Modul Manajemen Peminjaman (Admin & Kepsek)
**Role User:** Admin, Kepsek

| ID | Skenario Pengujian | Parameter Input | Hasil yang Diharapkan |
|---|---|---|---|
| APM-01 | Filter data peminjaman di Dashboard/Tabel | `status`: "pending"<br>`tgl_pakai`: "2026-05-20" | Tabel hanya menampilkan data dengan status 'pending' pada tanggal tersebut. |
| APM-02 | Admin membuat peminjaman normal (Tidak Ada Konflik) | `asset_id`: 1 (tersedia)<br>`tgl_pakai`: Hari ini+2<br>`jam_mulai`: "08:00"<br>`jam_selesai`: "10:00"<br>`tujuan`: "Rapat"<br>`urgensi_score`: 2<br>`is_student_borrower`: false | Peminjaman berhasil dibuat dan status otomatis `approved` (karena tidak ada jadwal bentrok). |
| APM-03 | Admin mengajukan peminjaman untuk siswa (Dengan Guarantor) | `is_student_borrower`: true<br>`nama_siswa`: "Ahmad"<br>`guarantor_id`: 3 (Guru Budi) + *Param Waktu Valid* | Peminjaman berhasil dibuat. Data nama siswa dan guru penanggung jawab tersimpan. |
| APM-04 | Pengajuan siswa pada aset *Restricted* | `asset_id`: (ID Aset restricted)<br>`is_student_borrower`: true | Gagal diajukan. Sistem menampilkan validasi: "Aset [Nama] tidak dapat dipinjam oleh siswa." |
| APM-05 | Pengajuan pada aset berstatus selain 'Tersedia' | `asset_id`: (ID Aset rusak) | Gagal diajukan. Sistem menampilkan validasi: "Aset sedang tidak tersedia." |
| APM-06 | Kepsek mengajukan peminjaman pada jadwal yang sudah terisi (Hak Veto) | `asset_id`: 2<br>*Jam konflik dengan peminjaman lain*<br>`is_student_borrower`: false | Peminjaman Kepsek langsung `approved`. Peminjaman lain yang sebelumnya menyita jadwal otomatis di-`canceled` (Batal otomatis: Hak Veto Kepsek). |
| APM-07 | Admin mengajukan peminjaman konflik jadwal (Triggers Algoritma SAW) | `asset_id`: 2<br>*Jam konflik dengan peminjaman lain* | Peminjaman dibuat dengan status `pending`. Sistem menjalankan algoritma SAW, dan memberikan notifikasi: "Terdeteksi konflik jadwal. SAW telah menentukan prioritas." |
| APM-08 | Force Cancel peminjaman yang sudah disetujui | *Pilih peminjaman approved*<br>`cancel_reason`: "Aset perlu diperbaiki mendadak" | Status peminjaman berubah menjadi `canceled` dan alasan pembatalan tersimpan di sistem. |

## 4. Modul Manajemen Peminjaman (Guru & Staf)
**Role User:** Guru, Staf

| ID | Skenario Pengujian | Parameter Input | Hasil yang Diharapkan |
|---|---|---|---|
| GPM-01 | Mengajukan peminjaman normal (Tersedia) | `asset_id`: 3<br>`tgl_pakai`: Hari ini+1<br>`jam_mulai`: "13:00"<br>`jam_selesai`: "15:00"<br>`tujuan`: "Ekstrakurikuler"<br>`urgensi_score`: 1 | Peminjaman berhasil diajukan, status otomatis menjadi `approved` karena jadwal kosong. |
| GPM-02 | Mengajukan peminjaman dengan parameter jam tidak logis | `jam_mulai`: "15:00"<br>`jam_selesai`: "13:00" | Gagal diajukan. Validasi form menolak dengan pesan: "Jam selesai harus setelah jam mulai." |
| GPM-03 | Mengajukan peminjaman di masa lalu | `tgl_pakai`: "2020-01-01" | Gagal diajukan. Validasi error: "Tanggal pakai tidak boleh di masa lalu." |
| GPM-04 | Guru mengajukan peminjaman yang bentrok jadwal (Konflik) | `asset_id`: 3<br>*Jam tumpang tindih dengan peminjaman GPM-01*<br>`urgensi_score`: 4 (Ujian) | Peminjaman diterima, namun statusnya `pending`. Proses SAW akan menghitung urgensi, *lead time*, dan bobot jabatan untuk membandingkannya dengan peminjaman sebelumnya. |
| GPM-05 | Uji Keamanan: Akses detail peminjaman milik orang lain | Akses langsung ke URL: `/peminjamans/5` (bukan miliknya) | Sistem memblokir akses dan mengembalikan pesan error 403 (Unauthorized). |
