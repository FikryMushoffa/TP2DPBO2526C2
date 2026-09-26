<?php
// ============================================================================
// KELAS: Video (Base Class / Kelas Induk Teratas)
// Konsep: OOP Multilevel Inheritance (Level 1: Base Class)
// ============================================================================
// Kelas Video mendefinisikan atribut dan fungsionalitas dasar yang dimiliki oleh
// seluruh jenis karya video/audio-visual.
class Video {
	// Atribut kelas Video (menggunakan hak akses protected agar dapat diwariskan ke kelas turunan)
	protected $id;          // ID unik video
	protected $judul;       // Judul karya video
	protected $durasi;      // Durasi video dalam satuan menit
	protected $tahunRilis;  // Tahun perilisan video

	// --------------------------------------------------------------------
	// KONSTRUKTOR
	// --------------------------------------------------------------------

	// Konstruktor dengan parameter default untuk menginisialisasi atribut sesuai argumen input
	public function __construct($id = "", $judul = "", $durasi = 0, $tahunRilis = 0) {
		$this->id = $id;
		$this->judul = $judul;
		$this->durasi = (int)$durasi;
		$this->tahunRilis = (int)$tahunRilis;
	}

	// --------------------------------------------------------------------
	// METHOD GETTER (Untuk mengambil nilai dari setiap atribut)
	// --------------------------------------------------------------------

	// Method getter untuk mengembalikan nilai atribut id
	public function getId() {
		return $this->id;
	}

	// Method getter untuk mengembalikan nilai atribut judul
	public function getJudul() {
		return $this->judul;
	}

	// Method getter untuk mengembalikan nilai atribut durasi
	public function getDurasi() {
		return $this->durasi;
	}

	// Method getter untuk mengembalikan nilai atribut tahunRilis
	public function getTahunRilis() {
		return $this->tahunRilis;
	}

	// --------------------------------------------------------------------
	// METHOD SETTER (Untuk mengisi atau memperbarui nilai dari setiap atribut)
	// --------------------------------------------------------------------

	// Method setter untuk mengisi atau memperbarui nilai atribut id
	public function setId($id) {
		$this->id = $id;
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut judul
	public function setJudul($judul) {
		$this->judul = $judul;
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut durasi (validasi positif)
	public function setDurasi($durasi) {
		if ($durasi > 0) {
			$this->durasi = (int)$durasi;
		}
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut tahunRilis (validasi positif)
	public function setTahunRilis($tahunRilis) {
		if ($tahunRilis > 0) {
			$this->tahunRilis = (int)$tahunRilis;
		}
	}
}
?>
