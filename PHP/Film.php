<?php
require_once __DIR__ . '/Video.php';

// ============================================================================
// KELAS: Film (Derived Class / Kelas Turunan Level 1 dari Video)
// Konsep: OOP Multilevel Inheritance (Level 2: Video -> Film)
// ============================================================================
// Kelas Film mewarisi semua atribut dan method dari kelas Video (Induk),
// kemudian menambahkan atribut serta method spesifik perfilman.
class Film extends Video {
	// Atribut tambahan khusus untuk kelas Film (bersifat protected untuk diwariskan ke FilmBioskop)
	protected $genre;          // Genre film (misal: Action, Sci-Fi, Romance, Animasi)
	protected $rating;         // Nilai rating kualitas film (skala 0.0 - 10.0)
	protected $sutradara;      // Nama sutradara pengarah film
	protected $studioProduksi; // Nama studio/rumah produksi pembuat film

	// --------------------------------------------------------------------
	// KONSTRUKTOR
	// --------------------------------------------------------------------

	// Konstruktor berparameter yang memanggil konstruktor kelas induk (Video)
	public function __construct(
		$id = "", $judul = "", $durasi = 0, $tahunRilis = 0,
		$genre = "", $rating = 0.0, $sutradara = "", $studioProduksi = ""
	) {
		parent::__construct($id, $judul, $durasi, $tahunRilis);
		$this->genre = $genre;
		$this->rating = (float)$rating;
		$this->sutradara = $sutradara;
		$this->studioProduksi = $studioProduksi;
	}

	// --------------------------------------------------------------------
	// METHOD GETTER (Untuk mengambil nilai dari setiap atribut)
	// --------------------------------------------------------------------

	// Method getter untuk mengembalikan nilai atribut genre
	public function getGenre() {
		return $this->genre;
	}

	// Method getter untuk mengembalikan nilai atribut rating
	public function getRating() {
		return $this->rating;
	}

	// Method getter untuk mengembalikan nilai atribut sutradara
	public function getSutradara() {
		return $this->sutradara;
	}

	// Method getter untuk mengembalikan nilai atribut studioProduksi
	public function getStudioProduksi() {
		return $this->studioProduksi;
	}

	// --------------------------------------------------------------------
	// METHOD SETTER (Untuk mengisi atau memperbarui nilai dari setiap atribut)
	// --------------------------------------------------------------------

	// Method setter untuk mengisi atau memperbarui nilai atribut genre
	public function setGenre($genre) {
		$this->genre = $genre;
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut rating (rentang 0.0 - 10.0)
	public function setRating($rating) {
		if ($rating >= 0.0 && $rating <= 10.0) {
			$this->rating = (float)$rating;
		}
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut sutradara
	public function setSutradara($sutradara) {
		$this->sutradara = $sutradara;
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut studioProduksi
	public function setStudioProduksi($studioProduksi) {
		$this->studioProduksi = $studioProduksi;
	}
}
?>
