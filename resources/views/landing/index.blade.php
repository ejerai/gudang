<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GudangSmart - Sistem Inventory Gudang</title>
    <meta name="description" content="GudangSmart membantu tim gudang mencatat barang masuk dan keluar, memantau stok menipis, dan membuat laporan persediaan dalam satu tempat.">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/logo.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
@php $masuk = auth()->check(); @endphp

{{-- ===== Nav ===== --}}
<header class="lp-nav">
    <div class="wrap">
        <a href="{{ route('landing') }}" class="lp-brand"><x-logo class="logo" />GudangSmart</a>
        <nav aria-label="Menu utama">
            <a href="#fitur" class="hide-s">Fitur</a>
            <a href="#peran" class="hide-s">Peran</a>
            <a href="#faq" class="hide-s">FAQ</a>
            <a href="{{ $masuk ? route('dashboard') : route('login') }}" class="lp-btn light sm">{{ $masuk ? 'Ke dashboard' : 'Masuk' }}</a>
        </nav>
    </div>
</header>

<main>
    {{-- ===== Hero ===== --}}
    <section class="hero">
        <div class="wrap">
            <h1>Tahu stok apa yang harus diisi, dan kenapa.</h1>
            <p class="sub">Catat barang masuk dan keluar, pantau stok yang menipis, dan lihat nilai persediaan. Semua tersusun rapi dalam satu dashboard untuk tim gudang Anda.</p>
            <div class="cta">
                <a href="{{ $masuk ? route('dashboard') : route('login') }}" class="lp-btn light">{{ $masuk ? 'Buka dashboard' : 'Masuk ke dashboard' }}</a>
                <a href="#fitur" class="lp-btn line">Lihat fitur</a>
            </div>
            <p class="note">Akses khusus manager dan staff gudang.</p>

            <div class="hero-card">
                <div class="mock">
                    <div class="mock-head">
                        <span class="ttl"><i>!</i>Perlu diisi ulang</span>
                        <span>Contoh tampilan</span>
                    </div>
                    <h3>Sarung Tangan Safety Heavy Duty</h3>
                    <div class="line"><span class="ok">✓</span>Kategori Alat Keselamatan Kerja</div>
                    <div class="chips">
                        <span class="chip warn">Stok 4</span>
                        <span class="chip">Batas menipis 10</span>
                        <span class="chip ind">Supplier terdaftar</span>
                    </div>
                    <div class="meter"><i class="low" style="width: 40%"></i><span><b>4 tersisa</b><em style="font-style:normal;color:#565968">40% dari batas</em></span></div>
                </div>
                <div class="mock next" aria-hidden="true">
                    <div class="mock-head">
                        <span class="ttl"><i>!</i>Segera dipesan</span>
                        <span>Contoh tampilan</span>
                    </div>
                    <h3>Kabel Tembaga Grade 304</h3>
                    <div class="line"><span class="ok">✓</span>Kategori Komponen Elektrik</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Pencarian ===== --}}
    <section class="sec" id="fitur">
        <div class="wrap">
            <div class="sec-head">
                <h2>Temukan barang yang Anda butuhkan</h2>
                <p>Cari berdasarkan nama atau kode barang. Hasil muncul saat Anda mengetik, baik di katalog maupun ketika mencatat mutasi stok.</p>
            </div>
            <div class="search-demo" aria-label="Contoh pencarian barang">
                <div class="box">
                    <svg class="icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/></svg>
                    <span><b>baut</b><i class="caret"></i></span>
                </div>
                <ul>
                    <li><div class="t"><strong>Baut Baja M8</strong><small>BRG-10482 · Komponen Mekanik</small></div><span class="chip">Stok 186</span></li>
                    <li><div class="t"><strong>Baut Baja M10</strong><small>BRG-20731 · Komponen Mekanik</small></div><span class="chip warn">Stok 7</span></li>
                    <li><div class="t"><strong>Baut Baja Grade 304</strong><small>BRG-35120 · Bahan Baku</small></div><span class="chip">Stok 92</span></li>
                </ul>
            </div>
        </div>
    </section>

    {{-- ===== Kategori & stok menipis ===== --}}
    <section class="sec tight">
        <div class="wrap split">
            <div class="txt">
                <h2>Ketahui kategori mana yang butuh perhatian</h2>
                <p>Setiap barang punya kategori dan supplier. Dari situ GudangSmart menghitung jumlah jenis, total stok, dan barang yang sudah menyentuh batas menipis, sehingga Anda tahu apa yang harus dipesan lebih dulu.</p>
                <ul>
                    <li>Batas stok menipis dihitung otomatis</li>
                    <li>Kontak supplier tampil di samping barang yang perlu diisi ulang</li>
                </ul>
            </div>
            <div class="viz">
                <div class="reqs">
                    <div class="cap">Contoh ringkasan</div>
                    <h3>5 barang perlu diisi ulang</h3>
                    <div class="row"><div><b>Bahan Baku</b><small>Stok ≤ 10</small></div><span class="r warn">1 barang</span></div>
                    <div class="row"><div><b>Komponen Mekanik</b><small>Stok ≤ 10</small></div><span class="r warn">2 barang</span></div>
                    <div class="row"><div><b>Komponen Elektrik</b><small>Stok aman</small></div><span class="r">0 barang</span></div>
                    <div class="row"><div><b>Alat Keselamatan Kerja</b><small>Stok ≤ 10</small></div><span class="r warn">2 barang</span></div>
                    <div class="row"><div><b>Peralatan dan Perkakas</b><small>Stok aman</small></div><span class="r">0 barang</span></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Mutasi stok ===== --}}
    <section class="sec">
        <div class="wrap split rev">
            <div class="viz">
                <div class="mock">
                    <div class="mock-head"><span class="ttl"><i>⇄</i>Catat mutasi</span><span>Contoh tampilan</span></div>
                    <h3>Selang Hidrolik Heavy Duty</h3>
                    <div class="line">BRG-48210 · Komponen Mekanik</div>
                    <div class="chips">
                        <span class="chip out">Barang keluar</span>
                        <span class="chip">Jumlah 12</span>
                        <span class="chip ind">Stok tersedia 58</span>
                    </div>
                    <div class="meter"><i style="width: 79%"></i><span><b>58 → 46</b><em style="font-style:normal;color:#565968">setelah disimpan</em></span></div>
                </div>
            </div>
            <div class="txt">
                <h2>Catat mutasi tanpa salah hitung</h2>
                <p>Pilih barang lewat pencarian, isi jumlah, lalu simpan. Stok diperbarui otomatis dan setiap perubahan tercatat lengkap dengan waktu dan petugasnya.</p>
                <ul>
                    <li>Barang keluar ditolak jika melebihi stok tersedia</li>
                    <li>Penyimpanan memakai transaksi basis data agar stok tetap konsisten</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- ===== Laporan ===== --}}
    <section class="sec soft">
        <div class="wrap">
            <div class="sec-head">
                <h2>Setiap laporan, dalam satu tempat</h2>
                <p>Nilai persediaan per kategori, daftar stok menipis, dan rincian mutasi per periode. Unduh sebagai CSV atau cetak langsung dari halaman laporan.</p>
            </div>
            <div class="rep">
                <div class="mock">
                    <h3>Nilai persediaan per kategori</h3>
                    <div class="bar-row"><span>Bahan Baku</span><div class="tr"><i style="width: 82%"></i></div><span class="v">82%</span></div>
                    <div class="bar-row"><span>Komponen Mekanik</span><div class="tr"><i style="width: 64%"></i></div><span class="v">64%</span></div>
                    <div class="bar-row"><span>Komponen Elektrik</span><div class="tr"><i style="width: 71%"></i></div><span class="v">71%</span></div>
                    <div class="bar-row"><span>Alat Keselamatan</span><div class="tr"><i style="width: 38%"></i></div><span class="v">38%</span></div>
                    <div class="bar-row"><span>Perkakas</span><div class="tr"><i style="width: 55%"></i></div><span class="v">55%</span></div>
                    <p class="rep-foot">Stok × harga satuan, barang terhapus tidak dihitung.</p>
                </div>
                <div class="mock">
                    <h3>Stok menipis</h3>
                    <div class="low-row"><div>Sarung Tangan Safety<small>Alat Keselamatan Kerja</small></div><span class="chip warn">4</span></div>
                    <div class="low-row"><div>Kawat Las Tipe A<small>Bahan Baku</small></div><span class="chip warn">7</span></div>
                    <div class="low-row"><div>Lampu Industri 5 Liter<small>Komponen Elektrik</small></div><span class="chip out">0</span></div>
                    <p class="rep-foot">Contoh tampilan</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Peran ===== --}}
    <section class="sec" id="peran">
        <div class="wrap">
            <div class="sec-head">
                <h2>Dua peran, satu gudang</h2>
                <p>Setiap akun memiliki hak akses sesuai tugasnya, sehingga data penting tidak diubah oleh orang yang tidak berwenang.</p>
            </div>
            <div class="roles">
                <div class="role">
                    <h3>Manager Gudang</h3>
                    <p>Mengelola seluruh data dan laporan.</p>
                    <ul>
                        <li>Tambah, ubah, dan hapus supplier serta barang</li>
                        <li>Pulihkan atau hapus permanen barang terhapus</li>
                        <li>Tambah kategori dan catat mutasi stok</li>
                        <li>Akses laporan, ekspor CSV, dan cetak</li>
                    </ul>
                </div>
                <div class="role">
                    <h3>Staff Gudang</h3>
                    <p>Fokus pada pekerjaan harian di lapangan.</p>
                    <ul>
                        <li>Lihat supplier, kategori, dan katalog barang</li>
                        <li>Catat barang masuk dan keluar</li>
                        <li class="no">Tidak dapat mengubah atau menghapus data master</li>
                        <li class="no">Tidak dapat membuka halaman laporan</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Statistik ===== --}}
    <section class="sec soft">
        <div class="wrap">
            <div class="sec-head" style="margin-bottom:34px"><h2>Lihat gambaran gudang sebelum masuk</h2></div>
            <div class="stats4">
                <div><strong>{{ number_format($statistik['barang'], 0, ',', '.') }}</strong><span>jenis barang tercatat</span></div>
                <div><strong>{{ number_format($statistik['kategori'], 0, ',', '.') }}</strong><span>kategori barang</span></div>
                <div><strong>{{ number_format($statistik['supplier'], 0, ',', '.') }}</strong><span>supplier aktif</span></div>
                <div><strong>{{ number_format($statistik['mutasi'], 0, ',', '.') }}</strong><span>mutasi stok dicatat</span></div>
            </div>
            <p class="center-link"><a href="{{ $masuk ? route('dashboard') : route('login') }}">{{ $masuk ? 'Buka dashboard' : 'Masuk untuk melihat selengkapnya' }} &rarr;</a></p>
        </div>
    </section>

    {{-- ===== FAQ ===== --}}
    <section class="sec" id="faq">
        <div class="wrap faq">
            <h2>FAQ</h2>
            <details>
                <summary>Apa itu GudangSmart?</summary>
                <p>GudangSmart adalah sistem inventory untuk gudang manufaktur. Aplikasi ini mencatat data barang, supplier, dan kategori, merekam mutasi stok masuk dan keluar, serta menyajikan laporan persediaan.</p>
            </details>
            <details>
                <summary>Siapa yang bisa masuk?</summary>
                <p>Hanya akun yang sudah terdaftar, yaitu manager gudang dan staff gudang. Belum ada pendaftaran mandiri, jadi akun dibuat oleh pengelola sistem.</p>
            </details>
            <details>
                <summary>Bagaimana stok diperbarui?</summary>
                <p>Stok berubah otomatis setiap kali mutasi dicatat. Barang masuk menambah stok, barang keluar mengurangi stok, dan sistem menolak barang keluar yang melebihi stok tersedia.</p>
            </details>
            <details>
                <summary>Kapan sebuah barang dianggap stok menipis?</summary>
                <p>Saat stoknya 10 atau kurang. Barang seperti ini muncul di lonceng pemberitahuan, daftar di sidebar, dan halaman laporan.</p>
            </details>
            <details>
                <summary>Apakah barang yang dihapus bisa dikembalikan?</summary>
                <p>Bisa. Barang yang dihapus dipindahkan ke daftar barang terhapus, dan manager dapat memulihkannya atau menghapusnya secara permanen.</p>
            </details>
            <details>
                <summary>Bisakah laporan diunduh?</summary>
                <p>Rincian mutasi per periode dapat diunduh sebagai CSV yang terbuka di Excel, dan halaman laporan bisa dicetak langsung dari browser.</p>
            </details>
        </div>
    </section>

    {{-- ===== CTA ===== --}}
    <section class="sec tight" style="padding-top:0">
        <div class="wrap">
            <div class="cta-band">
                <h2>Stok opname jadi lebih ringan dengan satu catatan.</h2>
                <p>Berhenti mencocokkan stok dari banyak catatan. Masuk, catat mutasi, dan biarkan dashboard menunjukkan apa yang perlu dipesan berikutnya.</p>
                <div class="cta">
                    <a href="{{ $masuk ? route('dashboard') : route('login') }}" class="lp-btn solid">{{ $masuk ? 'Buka dashboard' : 'Masuk sekarang' }}</a>
                    <a href="#fitur" class="lp-btn outline">Lihat fitur</a>
                </div>
            </div>
        </div>
    </section>
</main>

{{-- ===== Footer ===== --}}
<footer class="lp-foot">
    <div class="wrap">
        <div class="foot-grid">
            <div>
                <a href="{{ route('landing') }}" class="lp-brand"><x-logo class="logo" />GudangSmart</a>
                <p class="about">Sistem manufaktur dan inventory gudang untuk mencatat, memantau, dan melaporkan persediaan.</p>
            </div>
            <div>
                <h4>Produk</h4>
                <ul>
                    <li><a href="#fitur">Fitur</a></li>
                    <li><a href="#peran">Peran akses</a></li>
                    <li><a href="{{ $masuk ? route('dashboard') : route('login') }}">{{ $masuk ? 'Dashboard' : 'Masuk' }}</a></li>
                </ul>
            </div>
            <div>
                <h4>Bantuan</h4>
                <ul>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
            </div>
        </div>
        <p class="disclaimer">&copy; {{ date('Y') }} GudangSmart. Contoh data pada gambar ilustrasi hanya untuk gambaran tampilan.</p>
    </div>
</footer>
</body>
</html>
