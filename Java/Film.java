// ============================================================================
// KELAS: Film (Derived Class / Kelas Turunan Level 1 dari Video)
// Konsep: OOP Multilevel Inheritance (Level 2: Video -> Film)
// ============================================================================
// Kelas Film mewarisi semua atribut dan method dari kelas Video (Induk),
// kemudian menambahkan atribut serta method spesifik perfilman.
public class Film extends Video {
	// Atribut tambahan khusus untuk kelas Film (bersifat protected untuk diwariskan ke FilmBioskop)
	protected String genre;          // Genre film (misal: Action, Sci-Fi, Romance, Animasi)
	protected double rating;         // Nilai rating kualitas film (skala 0.0 - 10.0)
	protected String sutradara;      // Nama sutradara pengarah film
	protected String studioProduksi; // Nama studio/rumah produksi pembuat film

	// --------------------------------------------------------------------
	// KONSTRUKTOR
	// --------------------------------------------------------------------

	// Konstruktor default tanpa parameter yang memanggil konstruktor default Video
	public Film() {
		super();
		this.genre = "";
		this.rating = 0.0;
		this.sutradara = "";
		this.studioProduksi = "";
	}

	// Konstruktor berparameter yang memanggil konstruktor berparameter kelas Video
	public Film(String id, String judul, int durasi, int tahunRilis,
			String genre, double rating, String sutradara, String studioProduksi) {
		super(id, judul, durasi, tahunRilis);
		this.genre = genre;
		this.rating = rating;
		this.sutradara = sutradara;
		this.studioProduksi = studioProduksi;
	}

	// --------------------------------------------------------------------
	// METHOD GETTER (Untuk mengambil nilai dari setiap atribut)
	// --------------------------------------------------------------------

	// Method getter untuk mengembalikan nilai atribut genre
	public String getGenre() {
		return this.genre;
	}

	// Method getter untuk mengembalikan nilai atribut rating
	public double getRating() {
		return this.rating;
	}

	// Method getter untuk mengembalikan nilai atribut sutradara
	public String getSutradara() {
		return this.sutradara;
	}

	// Method getter untuk mengembalikan nilai atribut studioProduksi
	public String getStudioProduksi() {
		return this.studioProduksi;
	}

	// --------------------------------------------------------------------
	// METHOD SETTER (Untuk mengisi atau memperbarui nilai dari setiap atribut)
	// --------------------------------------------------------------------

	// Method setter untuk mengisi atau memperbarui nilai atribut genre
	public void setGenre(String genre) {
		this.genre = genre;
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut rating (rentang 0.0 - 10.0)
	public void setRating(double rating) {
		if (rating >= 0.0 && rating <= 10.0) {
			this.rating = rating;
		}
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut sutradara
	public void setSutradara(String sutradara) {
		this.sutradara = sutradara;
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut studioProduksi
	public void setStudioProduksi(String studioProduksi) {
		this.studioProduksi = studioProduksi;
	}
}
