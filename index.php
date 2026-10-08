<?php
// Inisialisasi variabel hasil perhitungan
$hpp_total = 0;
$hpp_satuan = 0;
$laba_kotor = 0;
$harga_jual_saran = 0;

$nama_produk = '';
$persediaan_awal = 0;
$pembelian_bersih = 0;
$persediaan_akhir = 0;
$biaya_lain = 0;
$jumlah_produksi = 1;
$margin_keuntungan = 20;
$harga_jual_input = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = $_POST['nama_produk'] ?? '';
    $persediaan_awal = floatval($_POST['persediaan_awal'] ?? 0);
    $pembelian_bersih = floatval($_POST['pembelian_bersih'] ?? 0);
    $persediaan_akhir = floatval($_POST['persediaan_akhir'] ?? 0);
    $biaya_lain = floatval($_POST['biaya_lain'] ?? 0);
    $jumlah_produksi = max(1, intval($_POST['jumlah_produksi'] ?? 1));
    $margin_keuntungan = floatval($_POST['margin_keuntungan'] ?? 20);
    $harga_jual_input = floatval($_POST['harga_jual_input'] ?? 0);

    // Rumus HPP = Persediaan Awal + Pembelian Bersih - Persediaan Akhir + Biaya Overhead/Lainnya
    $hpp_total = ($persediaan_awal + $pembelian_bersih - $persediaan_akhir) + $biaya_lain;
    if ($hpp_total < 0) {
        $hpp_total = 0;
    }

    // HPP per Satuan
    $hpp_satuan = $hpp_total / $jumlah_produksi;

    // Jika user mengisi harga jual input, hitung labanya, jika tidak gunakan margin
    if ($harga_jual_input > 0) {
        $laba_kotor = $harga_jual_input - $hpp_satuan;
        $harga_jual_saran = $harga_jual_input;
    } else {
        $harga_jual_saran = $hpp_satuan + ($hpp_satuan * ($margin_keuntungan / 100));
        $laba_kotor = $harga_jual_saran - $hpp_satuan;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Penghitung Harga Pokok Penjualan (HPP)</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-blue-600 tracking-tight">Kalkulator HPP Produk</h1>
            <p class="mt-2 text-sm text-slate-600">Hitung Harga Pokok Penjualan, HPP Satuan, dan Rekomendasi Harga Jual dengan Mudah</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Form Input (2 Kolom) -->
            <div class="md:col-span-2 bg-white shadow-xl rounded-2xl p-6 sm:p-8 border border-slate-100">
                <form method="POST" action="" class="space-y-5">
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Produk / Barang</label>
                        <input type="text" name="nama_produk" value="<?= htmlspecialchars($nama_produk) ?>" placeholder="Contoh: Kripik Singkong Balado" required
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Persediaan Awal (Rp)</label>
                            <input type="number" step="any" name="persediaan_awal" value="<?= $persediaan_awal ?>" placeholder="0"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Pembelian Bersih (Rp)</label>
                            <input type="number" step="any" name="pembelian_bersih" value="<?= $pembelian_bersih ?>" placeholder="0"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Persediaan Akhir (Rp)</label>
                            <input type="number" step="any" name="persediaan_akhir" value="<?= $persediaan_akhir ?>" placeholder="0"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Biaya Lain / Overhead (Rp)</label>
                            <input type="number" step="any" name="biaya_lain" value="<?= $biaya_lain ?>" placeholder="Listrik, tenaga kerja, dll"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-slate-100">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah Produksi</label>
                            <input type="number" min="1" name="jumlah_produksi" value="<?= $jumlah_produksi ?>" placeholder="1" required
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Margin Keuntungan (%)</label>
                            <input type="number" step="any" name="margin_keuntungan" value="<?= $margin_keuntungan ?>" placeholder="20"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Harga Jual Aktual (Opt)</label>
                            <input type="number" step="any" name="harga_jual_input" value="<?= $harga_jual_input ?>" placeholder="Opsional"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition duration-200">
                            Hitung HPP Sekarang
                        </button>
                    </div>
                </form>
            </div>

            <!-- Hasil Perhitungan (1 Kolom) -->
            <div class="bg-white shadow-xl rounded-2xl p-6 sm:p-8 border border-slate-100 flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-800 border-b pb-3 mb-4">Hasil Analisis</h2>
                    
                    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                        <div class="space-y-4 text-sm">
                            <div>
                                <span class="text-slate-500 block text-xs uppercase tracking-wider font-semibold">Produk</span>
                                <span class="font-bold text-slate-800 text-base"><?= htmlspecialchars($nama_produk ?: 'Tanpa Nama') ?></span>
                            </div>

                            <div>
                                <span class="text-slate-500 block text-xs uppercase tracking-wider font-semibold">Total HPP Keseluruhan</span>
                                <span class="font-bold text-blue-600 text-lg">Rp <?= number_format($hpp_total, 0, ',', '.') ?></span>
                            </div>

                            <div>
                                <span class="text-slate-500 block text-xs uppercase tracking-wider font-semibold">HPP Per Satuan Unit</span>
                                <span class="font-bold text-slate-800 text-lg">Rp <?= number_format($hpp_satuan, 0, ',', '.') ?></span>
                            </div>

                            <div class="bg-blue-50 p-3.5 rounded-xl border border-blue-100">
                                <span class="text-blue-600 block text-xs uppercase tracking-wider font-semibold">Saran Harga Jual (<?= $margin_keuntungan ?>% Margin)</span>
                                <span class="font-extrabold text-blue-700 text-xl">Rp <?= number_format($harga_jual_saran, 0, ',', '.') ?></span>
                            </div>

                            <div>
                                <span class="text-slate-500 block text-xs uppercase tracking-wider font-semibold">Estimasi Laba Kotor / Unit</span>
                                <span class="font-bold text-emerald-600 text-base">Rp <?= number_format($laba_kotor, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12 text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-sm">Silakan masukkan data produk di samping lalu klik tombol hitung.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-8 pt-4 border-t border-slate-100 text-center text-xs text-slate-400">
                    &copy; <?= date('Y') ?> Kalkulator HPP PHP
                </div>
            </div>

        </div>
    </div>
</body>
</html>
