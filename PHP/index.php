<?php
// ============================================================================
// FILE UTAMA: index.php (Sistem Database Film Bioskop Berbasis Web PHP)
// Praktikum DPBO 2 - Konsep Multilevel Inheritance (Video -> Film -> FilmBioskop)
// ============================================================================

// Memuat definisi kelas sebelum session_start agar deserialisasi objek berjalan benar
require_once __DIR__ . '/FilmBioskop.php';

session_start();

// ----------------------------------------------------------------------------
// FUNGSI INISIALISASI DATA AWAL (HARDCODE 5 DATA FILM)
// ----------------------------------------------------------------------------
function getDefaultFilms() {
	return [
		new FilmBioskop("V01", "Oppenheimer", 180, 2023, "Biografi", 8.9, "Christopher Nolan", "Syncopy Inc.", "17+", "Universal Pictures", 100, "oppenheimer.jpg"),
		new FilmBioskop("V02", "Avatar", 162, 2009, "Sci-Fi", 7.9, "James Cameron", "Lightstorm Entertainment", "13+", "20th Century Fox", 237, "avatar.jpg"),
		new FilmBioskop("V03", "Zootopia 2", 108, 2025, "Animasi", 7.8, "Byron Howard", "Walt Disney Animation Studios", "SU", "Walt Disney Studios", 150, "zootopia_2.jpg"),
		new FilmBioskop("V04", "The Avengers", 143, 2012, "Action", 8.0, "Joss Whedon", "Marvel Studios", "13+", "Walt Disney Studios", 220, "the_avengers.jpg"),
		new FilmBioskop("V05", "Titanic", 195, 1997, "Romance", 7.9, "James Cameron", "Lightstorm Entertainment", "13+", "Paramount Pictures", 200, "titanic.jpg"),
	];
}

// Inisialisasi daftarFilm di session bila belum ada
if (!isset($_SESSION['daftarFilm']) || !is_array($_SESSION['daftarFilm'])) {
	$_SESSION['daftarFilm'] = getDefaultFilms();
}

// Helper untuk memeriksa apakah ID Film sudah ada di database
function isIdExists($id, $list) {
	foreach ($list as $f) {
		if ($f instanceof FilmBioskop && strcasecmp($f->getId(), $id) === 0) {
			return true;
		}
	}
	return false;
}

// ----------------------------------------------------------------------------
// PENANGANAN ACTIONS (RESET & TAMBAH DATA)
// ----------------------------------------------------------------------------

$pesan = "";
$tipePesan = "";

// Cek flash message dari session setelah redirect
if (isset($_SESSION['flash'])) {
	$pesan = $_SESSION['flash']['pesan'];
	$tipePesan = $_SESSION['flash']['tipe'];
	unset($_SESSION['flash']);
}

// 1. ACTION: RESET DATA KE DEFAULT
if (isset($_GET['action']) && $_GET['action'] === 'reset') {
	$_SESSION['daftarFilm'] = getDefaultFilms();
	$_SESSION['flash'] = [
		'pesan' => "Database film berhasil di-reset kembali ke 5 data default awal!",
		'tipe' => 'info'
	];
	header("Location: index.php");
	exit();
}

// 2. ACTION: TAMBAH DATA FILM BARU (POST)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['tambah_film'])) {
	$id = trim($_POST['id'] ?? '');
	$judul = trim($_POST['judul'] ?? '');
	$durasi = (int)($_POST['durasi'] ?? 0);
	$tahunRilis = (int)($_POST['tahunRilis'] ?? 0);
	$genre = trim($_POST['genre'] ?? '');
	$rating = (float)($_POST['rating'] ?? 0.0);
	$sutradara = trim($_POST['sutradara'] ?? '');
	$studioProduksi = trim($_POST['studioProduksi'] ?? '');
	$usiaRating = trim($_POST['usiaRating'] ?? '');
	$distributor = trim($_POST['distributor'] ?? '');
	$biayaProduksi = (int)($_POST['biayaProduksi'] ?? 0);
	$fotoTeks = trim($_POST['foto_teks'] ?? '');

	$errors = [];

	// Validasi ID
	if (empty($id)) {
		$errors[] = "ID Film wajib diisi.";
	} elseif (isIdExists($id, $_SESSION['daftarFilm'])) {
		$errors[] = "ID Film '{$id}' sudah digunakan! Harap gunakan ID lain.";
	}

	// Validasi field lainnya
	if (empty($judul)) $errors[] = "Judul Film wajib diisi.";
	if ($durasi <= 0 || $durasi > 1000) $errors[] = "Durasi harus antara 1 s.d. 1000 menit.";
	if ($tahunRilis < 1888 || $tahunRilis > 2100) $errors[] = "Tahun rilis harus antara 1888 s.d. 2100.";
	if (empty($genre)) $errors[] = "Genre film wajib diisi.";
	if ($rating < 0.0 || $rating > 10.0) $errors[] = "Rating harus antara 0.0 s.d. 10.0.";
	if (empty($sutradara)) $errors[] = "Sutradara wajib diisi.";
	if (empty($studioProduksi)) $errors[] = "Studio Produksi wajib diisi.";
	if (empty($usiaRating)) $errors[] = "Usia Rating wajib diisi.";
	if (empty($distributor)) $errors[] = "Distributor wajib diisi.";
	if ($biayaProduksi < 0) $errors[] = "Biaya produksi tidak boleh negatif.";

	// Penanganan Foto Produk
	$namaFoto = "default.jpg";
	if (!empty($fotoTeks)) {
		$namaFoto = $fotoTeks;
	}

	// Cek upload file foto jika user memilih upload langsung
	if (isset($_FILES['foto_file']) && $_FILES['foto_file']['error'] === UPLOAD_ERR_OK) {
		$fileTmpPath = $_FILES['foto_file']['tmp_name'];
		$fileName = basename($_FILES['foto_file']['name']);
		$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
		$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

		if (in_array($fileExt, $allowedExtensions)) {
			$uploadDir = __DIR__ . '/images/';
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir, 0755, true);
			}
			$destPath = $uploadDir . $fileName;
			if (move_uploaded_file($fileTmpPath, $destPath)) {
				$namaFoto = $fileName;
			}
		} else {
			$errors[] = "Format foto tidak didukung (hanya JPG, PNG, WEBP, GIF).";
		}
	}

	if (empty($errors)) {
		// Buat objek baru FilmBioskop dan simpan ke dalam session
		$filmBaru = new FilmBioskop(
			$id, $judul, $durasi, $tahunRilis,
			$genre, $rating, $sutradara, $studioProduksi,
			$usiaRating, $distributor, $biayaProduksi, $namaFoto
		);
		$_SESSION['daftarFilm'][] = $filmBaru;
		$_SESSION['flash'] = [
			'pesan' => "Data Film '{$judul}' ({$id}) berhasil ditambahkan!",
			'tipe' => 'success'
		];
		header("Location: index.php");
		exit();
	} else {
		$pesan = implode("<br>", $errors);
		$tipePesan = "error";
	}
}

// Daftar film aktif
$koleksiFilm = $_SESSION['daftarFilm'];
$totalFilm = count($koleksiFilm);
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sistem Database Film Bioskop | DPBO PHP Multilevel Inheritance</title>
	<!-- Google Fonts: Inter & Outfit -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
	<style>
		:root {
			--bg-primary: #0a0e17;
			--bg-secondary: #111827;
			--bg-card: rgba(17, 24, 39, 0.95);
			--border-color: rgba(6, 182, 212, 0.25);
			--border-highlight: #06b6d4;
			--accent-cyan: #06b6d4;
			--accent-yellow: #facc15;
			--accent-green: #10b981;
			--accent-red: #ef4444;
			--accent-blue: #3b82f6;
			--text-main: #f3f4f6;
			--text-muted: #9ca3af;
			--font-heading: 'Outfit', sans-serif;
			--font-body: 'Inter', sans-serif;
			--font-mono: 'JetBrains Mono', monospace;
		}

		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			background-color: var(--bg-primary);
			background-image: 
				radial-gradient(circle at 15% 15%, rgba(6, 182, 212, 0.10) 0%, transparent 45%),
				radial-gradient(circle at 85% 85%, rgba(250, 204, 21, 0.08) 0%, transparent 45%);
			background-attachment: fixed;
			color: var(--text-main);
			font-family: var(--font-body);
			min-height: 100vh;
			line-height: 1.5;
			padding-bottom: 60px;
		}

		/* Container */
		.container {
			max-width: 1440px;
			margin: 0 auto;
			padding: 0 24px;
		}

		/* Header Banner - Menyerupai Banner Terminal CLI */
		header {
			padding: 32px 0 20px;
			border-bottom: 1px solid rgba(255, 255, 255, 0.08);
			margin-bottom: 28px;
		}

		.header-content {
			display: flex;
			justify-content: space-between;
			align-items: center;
			flex-wrap: wrap;
			gap: 20px;
		}

		.title-badge {
			display: inline-block;
			background: rgba(250, 204, 21, 0.12);
			color: var(--accent-yellow);
			border: 1px solid rgba(250, 204, 21, 0.3);
			padding: 4px 12px;
			border-radius: 6px;
			font-size: 0.82rem;
			font-weight: 600;
			margin-bottom: 8px;
			text-transform: uppercase;
			letter-spacing: 0.05em;
		}

		h1 {
			font-family: var(--font-heading);
			font-size: 2.1rem;
			font-weight: 800;
			letter-spacing: -0.02em;
			background: linear-gradient(135deg, #ffffff 40%, var(--accent-cyan) 100%);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
		}

		.subtitle {
			color: var(--text-muted);
			font-size: 0.95rem;
			margin-top: 4px;
		}

		.subtitle code {
			color: var(--accent-cyan);
			background: rgba(6, 182, 212, 0.12);
			padding: 2px 6px;
			border-radius: 4px;
			font-family: var(--font-mono);
			font-size: 0.88rem;
		}

		/* Action Buttons */
		.header-actions {
			display: flex;
			gap: 12px;
		}

		.btn {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			padding: 10px 20px;
			border-radius: 8px;
			font-weight: 600;
			font-size: 0.9rem;
			cursor: pointer;
			transition: all 0.2s ease;
			text-decoration: none;
			border: none;
		}

		.btn-primary {
			background: linear-gradient(135deg, #06b6d4, #0891b2);
			color: #ffffff;
			box-shadow: 0 4px 14px rgba(6, 182, 212, 0.35);
		}

		.btn-primary:hover {
			background: linear-gradient(135deg, #0891b2, #0e7490);
			box-shadow: 0 6px 18px rgba(6, 182, 212, 0.5);
			transform: translateY(-2px);
		}

		.btn-secondary {
			background: rgba(255, 255, 255, 0.06);
			color: #e5e7eb;
			border: 1px solid rgba(255, 255, 255, 0.15);
		}

		.btn-secondary:hover {
			background: rgba(255, 255, 255, 0.12);
			border-color: rgba(255, 255, 255, 0.3);
		}

		/* Alert Messages */
		.alert {
			padding: 14px 18px;
			border-radius: 8px;
			margin-bottom: 24px;
			display: flex;
			align-items: center;
			gap: 12px;
			font-size: 0.92rem;
		}

		.alert-success {
			background: rgba(16, 185, 129, 0.15);
			border: 1px solid rgba(16, 185, 129, 0.4);
			color: #a7f3d0;
		}

		.alert-error {
			background: rgba(239, 68, 68, 0.15);
			border: 1px solid rgba(239, 68, 68, 0.4);
			color: #fca5a5;
		}

		.alert-info {
			background: rgba(6, 182, 212, 0.15);
			border: 1px solid rgba(6, 182, 212, 0.4);
			color: #a5f3fc;
		}

		/* Table Header Card */
		.table-card {
			background: var(--bg-card);
			border: 1px solid var(--border-color);
			border-radius: 14px;
			box-shadow: 0 16px 36px rgba(0, 0, 0, 0.5);
			overflow: hidden;
		}

		.table-title-bar {
			background: rgba(6, 182, 212, 0.08);
			border-bottom: 1px solid var(--border-color);
			padding: 14px 24px;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.table-title {
			font-family: var(--font-heading);
			font-size: 1.15rem;
			font-weight: 700;
			color: var(--accent-yellow);
			letter-spacing: 0.08em;
			text-transform: uppercase;
		}

		/* Table Wrapper */
		.table-wrapper {
			overflow-x: auto;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			text-align: left;
			font-size: 0.88rem;
		}

		thead {
			background: rgba(0, 0, 0, 0.4);
			border-bottom: 2px solid var(--border-color);
		}

		th {
			padding: 14px 12px;
			color: var(--accent-yellow);
			font-weight: 700;
			font-size: 0.82rem;
			letter-spacing: 0.03em;
			white-space: nowrap;
			border-right: 1px solid rgba(6, 182, 212, 0.15);
		}

		th:last-child {
			border-right: none;
		}

		td {
			padding: 12px 12px;
			border-bottom: 1px solid rgba(255, 255, 255, 0.06);
			border-right: 1px solid rgba(255, 255, 255, 0.04);
			vertical-align: middle;
		}

		td:last-child {
			border-right: none;
		}

		tbody tr:hover {
			background: rgba(6, 182, 212, 0.05);
		}

		/* Per-column styling */
		.text-center { text-align: center; }
		.text-left { text-align: left; }

		.id-badge {
			display: inline-block;
			background: rgba(6, 182, 212, 0.15);
			color: var(--accent-cyan);
			padding: 3px 8px;
			border-radius: 4px;
			font-family: var(--font-mono);
			font-weight: 600;
			font-size: 0.82rem;
			border: 1px solid rgba(6, 182, 212, 0.3);
		}

		.film-title-text {
			font-weight: 600;
			color: #ffffff;
		}

		.poster-thumb {
			width: 64px;
			height: 90px;
			object-fit: cover;
			border-radius: 8px;
			border: 1px solid rgba(255, 255, 255, 0.15);
			display: block;
			margin: 0 auto;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
			transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
		}

		.poster-thumb:hover {
			transform: scale(1.22);
			border-color: var(--accent-cyan);
			box-shadow: 0 8px 20px rgba(6, 182, 212, 0.4);
			position: relative;
			z-index: 10;
		}

		.rating-badge {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 3px;
			background: rgba(250, 204, 21, 0.12);
			color: var(--accent-yellow);
			padding: 3px 7px;
			border-radius: 5px;
			font-weight: 700;
			font-size: 0.82rem;
			border: 1px solid rgba(250, 204, 21, 0.25);
		}

		.genre-pill {
			display: inline-block;
			background: rgba(59, 130, 246, 0.15);
			color: #93c5fd;
			padding: 2px 8px;
			border-radius: 9999px;
			font-size: 0.78rem;
			font-weight: 500;
		}

		.usia-badge {
			display: inline-block;
			padding: 3px 8px;
			border-radius: 5px;
			font-weight: 700;
			font-size: 0.78rem;
			text-align: center;
		}

		.usia-su { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); }
		.usia-13 { background: rgba(250, 204, 21, 0.2); color: #fde047; border: 1px solid rgba(250, 204, 21, 0.4); }
		.usia-17 { background: rgba(249, 115, 22, 0.2); color: #fb923c; border: 1px solid rgba(249, 115, 22, 0.4); }
		.usia-21 { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4); }

		.biaya-val {
			font-weight: 600;
			color: #34d399;
		}


		/* Table Info Footer - Menyerupai [INFO] Total Koleksi di CLI */
		.table-info-bar {
			padding: 14px 20px;
			background: rgba(0, 0, 0, 0.3);
			border-top: 1px solid var(--border-color);
			font-family: var(--font-mono);
			font-size: 0.9rem;
			color: var(--accent-green);
			font-weight: 600;
		}

		/* Modal Form */
		.modal-backdrop {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: rgba(0, 0, 0, 0.75);
			backdrop-filter: blur(6px);
			display: none;
			justify-content: center;
			align-items: center;
			z-index: 100;
			padding: 20px;
		}

		.modal-card {
			background: #111827;
			border: 1px solid var(--border-color);
			border-radius: 16px;
			max-width: 680px;
			width: 100%;
			max-height: 90vh;
			overflow-y: auto;
			box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);
			animation: zoomIn 0.25s ease;
		}

		@keyframes zoomIn {
			from { opacity: 0; transform: scale(0.95); }
			to { opacity: 1; transform: scale(1); }
		}

		.modal-header {
			padding: 20px 24px;
			border-bottom: 1px solid rgba(255, 255, 255, 0.1);
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.modal-title {
			font-family: var(--font-heading);
			font-size: 1.25rem;
			color: var(--accent-yellow);
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.close-btn {
			background: none;
			border: none;
			color: var(--text-muted);
			font-size: 1.5rem;
			cursor: pointer;
			transition: color 0.2s;
		}

		.close-btn:hover { color: #ffffff; }

		.modal-body {
			padding: 24px;
		}

		.form-grid {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 16px;
		}

		.form-group {
			display: flex;
			flex-direction: column;
			gap: 6px;
		}

		.form-group.full-width {
			grid-column: span 2;
		}

		label {
			font-size: 0.82rem;
			font-weight: 600;
			color: #e5e7eb;
			display: flex;
			justify-content: space-between;
		}

		label span.badge-level {
			font-size: 0.72rem;
			color: var(--accent-cyan);
			font-weight: normal;
		}

		input[type="text"],
		input[type="number"],
		select {
			background: rgba(0, 0, 0, 0.35);
			border: 1px solid rgba(255, 255, 255, 0.12);
			border-radius: 8px;
			padding: 10px 14px;
			color: #ffffff;
			font-family: var(--font-body);
			font-size: 0.9rem;
			transition: border-color 0.2s, box-shadow 0.2s;
		}

		input:focus,
		select:focus {
			outline: none;
			border-color: var(--accent-cyan);
			box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.2);
		}

		.modal-footer {
			padding: 18px 24px;
			border-top: 1px solid rgba(255, 255, 255, 0.1);
			display: flex;
			justify-content: flex-end;
			gap: 12px;
		}

		/* Quick fill box */
		.quick-fill-box {
			background: rgba(6, 182, 212, 0.08);
			border: 1px dashed rgba(6, 182, 212, 0.35);
			border-radius: 8px;
			padding: 12px 16px;
			margin-bottom: 20px;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.btn-fill {
			background: rgba(6, 182, 212, 0.2);
			color: var(--accent-cyan);
			border: 1px solid rgba(6, 182, 212, 0.4);
			padding: 6px 12px;
			border-radius: 6px;
			font-size: 0.8rem;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.2s;
		}

		.btn-fill:hover {
			background: var(--accent-cyan);
			color: #000000;
		}

		/* Footer */
		footer {
			margin-top: 40px;
			text-align: center;
			color: var(--text-muted);
			font-size: 0.85rem;
		}

		/* Responsive */
		@media (max-width: 768px) {
			.form-grid {
				grid-template-columns: 1fr;
			}
			.form-group.full-width {
				grid-column: span 1;
			}
			h1 {
				font-size: 1.6rem;
			}
			.header-content {
				flex-direction: column;
				align-items: flex-start;
			}
		}
	</style>
</head>
<body>

	<div class="container">
		
		<!-- Header Banner -->
		<header>
			<div class="header-content">
				<div>
					<div class="title-badge">Tugas Praktikum 2 (TP2) - Desain Pemrograman Berorientasi Objek</div>
					<h1>SISTEM DATABASE FILM BIOSKOP</h1>
					<p class="subtitle">Multilevel Inheritance: <code>Video</code> &rarr; <code>Film</code> &rarr; <code>FilmBioskop</code></p>
				</div>
				<div class="header-actions">
					<button class="btn btn-primary" onclick="openModal()">
						<span>+</span> Tambah Data Film Baru
					</button>
					<a href="index.php?action=reset" class="btn btn-secondary" onclick="return confirm('Apakah Anda yakin ingin me-reset seluruh data ke 5 data default awal?');">
						<span>↺</span> Reset Data Default
					</a>
				</div>
			</div>
		</header>

		<!-- Flash Alert Message -->
		<?php if (!empty($pesan)): ?>
			<div class="alert alert-<?= $tipePesan === 'error' ? 'error' : ($tipePesan === 'info' ? 'info' : 'success') ?>">
				<span><?= $tipePesan === 'error' ? '❌' : ($tipePesan === 'info' ? 'ℹ️' : '✅') ?></span>
				<div><?= $pesan ?></div>
			</div>
		<?php endif; ?>

		<!-- Tabel Dinamis Koleksi Film (12 Kolom Lengkap Sesuai CLI + Foto) -->
		<div class="table-card">
			<div class="table-title-bar">
				<span class="table-title">DAFTAR LENGKAP KOLEKSI FILM BIOSKOP</span>
			</div>
			
			<div class="table-wrapper">
				<table>
					<thead>
						<tr>
							<th class="text-center" style="width: 7%;">Foto Produk</th>
							<th class="text-center" style="width: 5%;">ID</th>
							<th class="text-left" style="width: 14%;">Judul Film</th>
							<th class="text-center" style="width: 7%;">Durasi (mnt)</th>
							<th class="text-center" style="width: 6%;">Tahun</th>
							<th class="text-left" style="width: 8%;">Genre</th>
							<th class="text-center" style="width: 7%;">Rating</th>
							<th class="text-left" style="width: 11%;">Sutradara</th>
							<th class="text-left" style="width: 14%;">Studio Produksi</th>
							<th class="text-center" style="width: 6%;">Usia</th>
							<th class="text-left" style="width: 10%;">Distributor</th>
							<th class="text-center" style="width: 7%;">Biaya (Juta Dollar)</th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($koleksiFilm)): ?>
							<tr>
								<td colspan="12" style="text-align: center; padding: 40px; color: var(--text-muted);">
									Belum ada data film di dalam database.
								</td>
							</tr>
						<?php else: ?>
							<?php foreach ($koleksiFilm as $film): ?>
								<?php if ($film instanceof FilmBioskop): ?>
									<?php
										$foto = $film->getFotoProduk();
										$imgPath = "images/" . $foto;
										if (empty($foto) || !file_exists(__DIR__ . '/' . $imgPath)) {
											$imgPath = "images/default.jpg";
										}
										// Kelas styling badge usia
										$usiaClass = "usia-su";
										if (strpos($film->getUsiaRating(), "13") !== false) $usiaClass = "usia-13";
										elseif (strpos($film->getUsiaRating(), "17") !== false) $usiaClass = "usia-17";
										elseif (strpos($film->getUsiaRating(), "21") !== false) $usiaClass = "usia-21";
									?>
									<tr>
										<!-- 1. Foto Produk (Paling Kiri, Hanya Gambar) -->
										<td class="text-center">
											<img src="<?= htmlspecialchars($imgPath) ?>" alt="<?= htmlspecialchars($film->getJudul()) ?>" class="poster-thumb" onerror="this.onerror=null; this.src='https://placehold.co/100x150/111827/06b6d4?text=Poster';">
										</td>

										<!-- 2. ID -->
										<td class="text-center">
											<span class="id-badge"><?= htmlspecialchars($film->getId()) ?></span>
										</td>
										
										<!-- 3. Judul Film -->
										<td class="text-left">
											<span class="film-title-text"><?= htmlspecialchars($film->getJudul()) ?></span>
										</td>
										
										<!-- 4. Durasi (mnt) -->
										<td class="text-center"><?= $film->getDurasi() ?></td>
										
										<!-- 5. Tahun -->
										<td class="text-center"><?= $film->getTahunRilis() ?></td>
										
										<!-- 6. Genre -->
										<td class="text-left">
											<span class="genre-pill"><?= htmlspecialchars($film->getGenre()) ?></span>
										</td>
										
										<!-- 7. Rating -->
										<td class="text-center">
											<span class="rating-badge">★ <?= number_format($film->getRating(), 1) ?></span>
										</td>
										
										<!-- 8. Sutradara -->
										<td class="text-left"><?= htmlspecialchars($film->getSutradara()) ?></td>
										
										<!-- 9. Studio Produksi (Kolom Khusus Tersendiri) -->
										<td class="text-left"><?= htmlspecialchars($film->getStudioProduksi()) ?></td>
										
										<!-- 10. Usia -->
										<td class="text-center">
											<span class="usia-badge <?= $usiaClass ?>"><?= htmlspecialchars($film->getUsiaRating()) ?></span>
										</td>
										
										<!-- 11. Distributor -->
										<td class="text-left"><?= htmlspecialchars($film->getDistributor()) ?></td>
										
										<!-- 12. Biaya (Juta Dollar) -->
										<td class="text-center biaya-val"><?= $film->getBiayaProduksi() ?></td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<!-- Ringkasan Info (Menyerupai [INFO] Total Koleksi pada CLI) -->
			<div class="table-info-bar">
				[INFO] Total Koleksi: <?= $totalFilm ?> Film tersimpan dalam sistem.
			</div>
		</div>

		<!-- Footer -->
		<footer>
			<p>Praktikum DPBO PHP Multilevel Inheritance &copy; <?= date('Y') ?> &mdash; Sistem Database Film Bioskop</p>
		</footer>

	</div>

	<!-- Modal Dialog Form Tambah Film (Menyerupai FORMULIR TAMBAH DATA FILM BIOSKOP pada CLI) -->
	<div class="modal-backdrop" id="modalAdd">
		<div class="modal-card">
			<div class="modal-header">
				<h3 class="modal-title"><span>🎬</span> FORMULIR TAMBAH DATA FILM BIOSKOP</h3>
				<button type="button" class="close-btn" onclick="closeModal()">&times;</button>
			</div>
			
			<form action="index.php" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
					
					<!-- Tombol Cepat Isi Data Sample (Frozen II dari file.txt) -->
					<div class="quick-fill-box">
						<div>
							<strong style="color: var(--accent-cyan); font-size: 0.9rem;">Coba data masukan (file.txt)?</strong>
							<p style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Frozen II (V06) otomatis terisi ke formulir</p>
						</div>
						<button type="button" class="btn-fill" onclick="fillSampleData()">⚡ Isi Data Frozen II</button>
					</div>

					<div class="form-grid">
						<!-- [1/11] ID Film -->
						<div class="form-group">
							<label for="id">
								[ 1/11] ID Film
								<span class="badge-level">Video</span>
							</label>
							<input type="text" id="id" name="id" required placeholder="Contoh: V06">
						</div>

						<!-- [2/11] Judul Film -->
						<div class="form-group">
							<label for="judul">
								[ 2/11] Judul Film
								<span class="badge-level">Video</span>
							</label>
							<input type="text" id="judul" name="judul" required placeholder="Contoh: Frozen II">
						</div>

						<!-- [3/11] Durasi (Menit) -->
						<div class="form-group">
							<label for="durasi">
								[ 3/11] Durasi (Menit)
								<span class="badge-level">Video</span>
							</label>
							<input type="number" id="durasi" name="durasi" required min="1" max="1000" placeholder="103">
						</div>

						<!-- [4/11] Tahun Rilis -->
						<div class="form-group">
							<label for="tahunRilis">
								[ 4/11] Tahun Rilis
								<span class="badge-level">Video</span>
							</label>
							<input type="number" id="tahunRilis" name="tahunRilis" required min="1888" max="2100" placeholder="2019">
						</div>

						<!-- [5/11] Genre -->
						<div class="form-group">
							<label for="genre">
								[ 5/11] Genre
								<span class="badge-level">Film</span>
							</label>
							<input type="text" id="genre" name="genre" required placeholder="Contoh: Animasi">
						</div>

						<!-- [6/11] Rating -->
						<div class="form-group">
							<label for="rating">
								[ 6/11] Rating (0.0 - 10.0)
								<span class="badge-level">Film</span>
							</label>
							<input type="number" id="rating" name="rating" required step="0.1" min="0" max="10" placeholder="6.8">
						</div>

						<!-- [7/11] Sutradara -->
						<div class="form-group">
							<label for="sutradara">
								[ 7/11] Sutradara
								<span class="badge-level">Film</span>
							</label>
							<input type="text" id="sutradara" name="sutradara" required placeholder="Contoh: Chris Buck">
						</div>

						<!-- [8/11] Studio Produksi -->
						<div class="form-group">
							<label for="studioProduksi">
								[ 8/11] Studio Produksi
								<span class="badge-level">Film</span>
							</label>
							<input type="text" id="studioProduksi" name="studioProduksi" required placeholder="Walt Disney Animation Studios">
						</div>

						<!-- [9/11] Usia Rating -->
						<div class="form-group">
							<label for="usiaRating">
								[ 9/11] Usia Rating
								<span class="badge-level">FilmBioskop</span>
							</label>
							<select id="usiaRating" name="usiaRating" required>
								<option value="">-- Pilih Usia Rating --</option>
								<option value="SU">SU (Semua Umur)</option>
								<option value="13+">13+ (Remaja)</option>
								<option value="17+">17+ (Dewasa)</option>
								<option value="21+">21+ (Dewasa Khusus)</option>
							</select>
						</div>

						<!-- [10/11] Distributor -->
						<div class="form-group">
							<label for="distributor">
								[10/11] Distributor
								<span class="badge-level">FilmBioskop</span>
							</label>
							<input type="text" id="distributor" name="distributor" required placeholder="Walt Disney Studios">
						</div>

						<!-- [11/11] Biaya Produksi (Juta Dollar) -->
						<div class="form-group">
							<label for="biayaProduksi">
								[11/11] Biaya Produksi (Juta $)
								<span class="badge-level">FilmBioskop</span>
							</label>
							<input type="number" id="biayaProduksi" name="biayaProduksi" required min="0" placeholder="150">
						</div>

						<!-- [12/12] Foto Produk -->
						<div class="form-group">
							<label for="foto_teks">
								[12/12] Nama File Foto
								<span class="badge-level">Foto Produk</span>
							</label>
							<input type="text" id="foto_teks" name="foto_teks" placeholder="frozen_2.jpg">
						</div>

						<!-- Upload File Opsional -->
						<div class="form-group full-width">
							<label for="foto_file">
								Atau Upload File Poster Baru (Opsional):
							</label>
							<input type="file" id="foto_file" name="foto_file" accept="image/*" style="font-size: 0.85rem;">
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
					<button type="submit" name="tambah_film" class="btn btn-primary">Simpan Film</button>
				</div>
			</form>
		</div>
	</div>

	<script>
		// Modal controls
		function openModal() {
			document.getElementById('modalAdd').style.display = 'flex';
		}

		function closeModal() {
			document.getElementById('modalAdd').style.display = 'none';
		}

		// Close modal bila mengklik backdrop luar
		window.onclick = function(event) {
			const modal = document.getElementById('modalAdd');
			if (event.target === modal) {
				closeModal();
			}
		};

		// Fungsi pengisian cepat data masukan sample (Frozen II dari file.txt)
		function fillSampleData() {
			document.getElementById('id').value = "V06";
			document.getElementById('judul').value = "Frozen II";
			document.getElementById('durasi').value = 103;
			document.getElementById('tahunRilis').value = 2019;
			document.getElementById('genre').value = "Animasi";
			document.getElementById('rating').value = 6.8;
			document.getElementById('sutradara').value = "Chris Buck";
			document.getElementById('studioProduksi').value = "Walt Disney Animation Studios";
			document.getElementById('usiaRating').value = "SU";
			document.getElementById('distributor').value = "Walt Disney Studios";
			document.getElementById('biayaProduksi').value = 150;
			document.getElementById('foto_teks').value = "frozen_2.jpg";
		}
	</script>

</body>
</html>
