<?php

// Data jalur pendaftaran (contoh). Ganti dengan data asli atau pindahkan ke database.
$umum = ['Teknik Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi', 'Ilmu Hukum', 'Sastra Inggris'];
$dasar = ['mulai' => '01 Oktober 2026', 'selesai' => '30 November 2026', 'periode' => '2027/2028 Ganjil', 'tahap' => 'Gelombang 2', 'pengumuman' => '14 Desember 2026'];

return [
    'prestasi' => $dasar + [
        'slug' => 'prestasi', 'nama' => 'PMB PRESTASI', 'perkuliahan' => 'Reguler', 'biaya' => 'Rp. 200.000',
        'tes' => 'Seleksi berkas (tanpa tes tertulis)',
        'deskripsi' => 'Jalur seleksi berdasarkan nilai rapor dan prestasi akademik maupun non-akademik. Pelamar terbaik berpeluang mendapat beasiswa hingga 100% biaya kuliah.',
        'syarat' => ['Lulusan SMA/SMK/MA sederajat', 'Rata-rata nilai rapor semester 1–5 minimal 80', 'Memiliki prestasi akademik atau non-akademik (diutamakan tingkat kabupaten/kota ke atas)'],
        'berkas' => ['Pas foto terbaru', 'KTP atau kartu identitas', 'Scan rapor semester 1–5', 'Sertifikat atau piagam prestasi'],
        'tahapan' => ['Buat pendaftaran dan lengkapi formulir', 'Unggah berkas dan sertifikat prestasi', 'Verifikasi berkas oleh panitia', 'Pengumuman hasil dan daftar ulang'],
        'prodi' => $umum,
    ],
    'reguler' => $dasar + [
        'slug' => 'reguler', 'nama' => 'PMB REGULER', 'perkuliahan' => 'Reguler/Karyawan', 'biaya' => 'Rp. 250.000',
        'tes' => 'Tes tertulis: 6 Desember 2026',
        'deskripsi' => 'Jalur seleksi melalui tes tertulis untuk seluruh program studi non-kesehatan. Tersedia kelas reguler dan kelas karyawan.',
        'syarat' => ['Lulusan SMA/SMK/MA sederajat', 'Mengikuti tes seleksi sesuai jadwal', 'Memilih maksimal 2 program studi'],
        'berkas' => ['Pas foto terbaru', 'KTP atau kartu identitas', 'Ijazah atau surat keterangan lulus', 'Scan rapor terakhir'],
        'tahapan' => ['Buat pendaftaran dan lengkapi formulir', 'Bayar biaya formulir', 'Ikuti tes seleksi', 'Pengumuman hasil dan daftar ulang'],
        'prodi' => $umum,
    ],
    'reguler-kesehatan' => $dasar + [
        'slug' => 'reguler-kesehatan', 'nama' => 'PMB REGULER KESEHATAN', 'perkuliahan' => 'Reguler', 'biaya' => 'Rp. 350.000',
        'tes' => 'Tes tertulis dan wawancara: 6 Desember 2026',
        'deskripsi' => 'Jalur khusus program studi rumpun kesehatan, dilengkapi pemeriksaan kesehatan dasar dan wawancara.',
        'syarat' => ['Lulusan SMA/SMK/MA sederajat', 'Sehat jasmani dan rohani', 'Tidak buta warna (untuk program studi tertentu)'],
        'berkas' => ['Pas foto terbaru', 'KTP atau kartu identitas', 'Ijazah atau surat keterangan lulus', 'Surat keterangan sehat'],
        'tahapan' => ['Buat pendaftaran dan lengkapi formulir', 'Bayar biaya formulir', 'Tes tertulis dan wawancara', 'Pemeriksaan kesehatan dan pengumuman'],
        'prodi' => ['Keperawatan', 'Kebidanan', 'Teknologi Laboratorium Medis', 'Profesi Ners'],
    ],
    'reguler-kedokteran' => $dasar + [
        'slug' => 'reguler-kedokteran', 'nama' => 'PMB REGULER KEDOKTERAN', 'perkuliahan' => 'Reguler', 'biaya' => 'Rp. 500.000',
        'tes' => 'Tes potensi akademik dan wawancara: 6 Desember 2026',
        'deskripsi' => 'Jalur seleksi Program Studi Kedokteran melalui tes potensi akademik, wawancara, dan pemeriksaan kesehatan.',
        'syarat' => ['Lulusan SMA/MA jurusan IPA atau sederajat', 'Nilai rapor IPA minimal 85', 'Sehat jasmani dan rohani'],
        'berkas' => ['Pas foto terbaru', 'KTP atau kartu identitas', 'Ijazah atau surat keterangan lulus', 'Scan rapor semester 1–5', 'Surat keterangan sehat'],
        'tahapan' => ['Buat pendaftaran dan lengkapi formulir', 'Bayar biaya formulir', 'Tes potensi akademik dan wawancara', 'Pemeriksaan kesehatan dan pengumuman'],
        'prodi' => ['Kedokteran (S1)'],
    ],
];
