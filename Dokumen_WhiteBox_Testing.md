# Skenario White-Box Testing (MBS Booking)

Berbeda dengan Black-Box yang berfokus pada fitur dan fungsionalitas aplikasi dari sudut pandang *user*, **White-Box Testing** menguji struktur internal, alur logika, dan *source code* secara detail. Tujuannya adalah memastikan setiap *branch* (cabang IF/ELSE), *condition*, dan perhitungan kalkulasi matematis tereksekusi dengan benar tanpa ada logika yang cacat (*logic flaw*).

Pada sistem MBS Booking, target paling penting (krusial) untuk White-Box Testing adalah **Algoritma di dalam `SawCalculatorService.php`**.

## 1. Pengujian Basis Path (Basis Path Testing)
Mari kita bedah fungsi `calculateLeadTimeScore` di dalam `SawCalculatorService` yang bertanggung jawab memberi skor jarak hari pengajuan. Terdapat struktur logika *percabangan* (Branch) di sana:

```php
public static function calculateLeadTimeScore(string $tglPakai, ?string $tglSubmit = null): int
{
    // ... kalkulasi $dayDiff ...
    if ($dayDiff >= 7) {
        return Peminjaman::LEAD_TIME_H7;    // Path 1 (Score: 3)
    }
    if ($dayDiff >= 3) {
        return Peminjaman::LEAD_TIME_H3;    // Path 2 (Score: 2)
    }
    return Peminjaman::LEAD_TIME_HARIH;     // Path 3 (Score: 1)
}
```

**Skenario Pengujian:**
Untuk mendapatkan *coverage* 100%, kita harus membuat data pengujian yang memaksa sistem melewati **ketiga jalur** kode di atas.

| ID Path | Kondisi | Parameter Input (`tglSubmit` -> `tglPakai`) | Hasil (*Expected*) | Tujuan Uji Logika |
|---|---|---|---|---|
| WT-01 | `$dayDiff >= 7` | "2026-05-10" -> "2026-05-18" (Selisih 8 hari) | `return 3` | Memastikan blok if pertama dieksekusi. |
| WT-02 | `3 <= $dayDiff < 7` | "2026-05-10" -> "2026-05-14" (Selisih 4 hari) | `return 2` | Memastikan bypass if pertama, dan masuk ke if kedua. |
| WT-03 | `$dayDiff < 3` | "2026-05-10" -> "2026-05-11" (Selisih 1 hari) | `return 1` | Memastikan bypass kedua if dan nilai default dikembalikan. |

## 2. Pengujian Pencegahan Error / Boundary (Condition Coverage)
Contoh vital lainnya ada pada fungsi normalisasi SAW (`normalizeMatrix`). Terdapat logika krusial untuk mencegah **Division by Zero** (Pembagian dengan nilai nol) yang dapat membuat server *crash* (Error 500):

```php
if (self::TYPES[$c] === 'benefit') {
    $normalized[$id][$c] = $max > 0 ? $value / $max : 0;
} else {
    $normalized[$id][$c] = $value > 0 ? $min / $value : 0;
}
```

**Skenario Pengujian:**
| ID | Skenario Kondisi | Parameter Matrix Internal | Hasil (*Expected*) |
|---|---|---|---|
| WT-04 | Benefit: `max > 0` | Array input nilai urgensi = [4, 2] | Perhitungan berjalan normal, ex: 2/4 = `0.5` |
| WT-05 | Benefit: `max == 0` | Array input nilai urgensi = [0, 0] | Ternary `max > 0` mengembalikan `false`, sistem me-*return* nilai `0` dan **tidak crash**. |
| WT-06 | Cost: `value > 0` | Array input lead time = [3, 2] | Perhitungan berjalan normal, ex: min(2)/3 = `0.66` |
| WT-07 | Cost: `value == 0` | Ada lead time terdeteksi = 0 | Ternary `value > 0` me-*return* `false`, hasil diset `0` tanpa menyebabkan error *division by zero*. |

## 3. Implementasi Kode (PHPUnit)
Dalam standar pengembangan Laravel, White-Box testing diimplementasikan langsung ke dalam bentuk *code* agar bisa dites secara otomatis (*Automated Testing*). 

Saya telah membuat *file unit test* PHPUnit nyata sebagai *sample* untuk fungsi kalkulasi `SawCalculatorService` di folder sistem Anda:
📁 **`tests/Unit/SawCalculatorServiceTest.php`**
