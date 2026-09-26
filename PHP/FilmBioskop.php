<?php
require_once __DIR__ . '/Film.php';

// ============================================================================
// KELAS: FilmBioskop (Derived Class / Kelas Turunan Level 2 dari Film)
// Konsep: OOP Multilevel Inheritance (Level 3: Video -> Film -> FilmBioskop)
// ============================================================================
// Kelas FilmBioskop mewarisi seluruh atribut dan method dari Film (dan secara
// tidak langsung dari Video), serta menambahkan atribut khusus penayangan bioskop
// dan atribut visual foto produk poster film.
class FilmBioskop extends Film {
	// Atribut tambahan khusus untuk kelas FilmBioskop
	private $usiaRating;    // Klasifikasi batas usia penonton bioskop (misal: "SU", "13+", "17+", "21+")
	private $distributor;   // Nama perusahaan distributor yang mendistribusikan ke bioskop
	private $biayaProduksi; // Estimasi total anggaran biaya produksi film dalam satuan Juta Dollar (integer, misal: 237)
	private $foto_produk;   // Nama berkas atau path relatif foto/poster film dalam folder images/

	// --------------------------------------------------------------------
	// KONSTRUKTOR
	// --------------------------------------------------------------------

	// Konstruktor berparameter yang memanggil konstruktor berparameter kelas induk (Film)
	public function __construct(
		$id = "", $judul = "", $durasi = 0, $tahunRilis = 0,
		$genre = "", $rating = 0.0, $sutradara = "", $studioProduksi = "",
		$usiaRating = "", $distributor = "", $biayaProduksi = 0, $foto_produk = ""
	) {
		parent::__construct($id, $judul, $durasi, $tahunRilis, $genre, $rating, $sutradara, $studioProduksi);
		$this->usiaRating = $usiaRating;
		$this->distributor = $distributor;
		$this->biayaProduksi = (int)$biayaProduksi;
		$this->foto_produk = $foto_produk;
	}

	// --------------------------------------------------------------------
	// METHOD GETTER (Untuk mengambil nilai dari setiap atribut)
	// --------------------------------------------------------------------

	// Method getter untuk mengembalikan nilai atribut usiaRating
	public function getUsiaRating() {
		return $this->usiaRating;
	}

	// Method getter untuk mengembalikan nilai atribut distributor
	public function getDistributor() {
		return $this->distributor;
	}

	// Method getter untuk mengembalikan nilai atribut biayaProduksi (tipe int dalam Juta Dollar)
	public function getBiayaProduksi() {
		return $this->biayaProduksi;
	}

	// Method getter untuk mengembalikan nama berkas / path foto_produk
	public function getFotoProduk() {
		return $this->foto_produk;
	}

	// Alias getter foto_produk
	public function get_foto_produk() {
		return $this->foto_produk;
	}

	// --------------------------------------------------------------------
	// METHOD SETTER (Untuk mengisi atau memperbarui nilai dari setiap atribut)
	// --------------------------------------------------------------------

	// Method setter untuk mengisi atau memperbarui nilai atribut usiaRating
	public function setUsiaRating($usiaRating) {
		$this->usiaRating = $usiaRating;
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut distributor
	public function setDistributor($distributor) {
		$this->distributor = $distributor;
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut biayaProduksi (validasi bilangan positif/nol)
	public function setBiayaProduksi($biayaProduksi) {
		if ($biayaProduksi >= 0) {
			$this->biayaProduksi = (int)$biayaProduksi;
		}
	}

	// Method setter untuk mengisi atau memperbarui nama berkas / path foto_produk
	public function setFotoProduk($foto_produk) {
		$this->foto_produk = $foto_produk;
	}

	// Alias setter foto_produk
	public function set_foto_produk($foto_produk) {
		$this->foto_produk = $foto_produk;
	}
}
?>
