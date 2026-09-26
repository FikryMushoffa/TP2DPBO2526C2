// ============================================================================
// KELAS: FilmBioskop (Derived Class / Kelas Turunan Level 2 dari Film)
// Konsep: OOP Multilevel Inheritance (Level 3: Video -> Film -> FilmBioskop)
// ============================================================================
// Kelas FilmBioskop mewarisi seluruh atribut dan method dari Film (dan secara
// tidak langsung dari Video), serta menambahkan atribut khusus penayangan bioskop.
public class FilmBioskop extends Film {
	// Atribut tambahan khusus untuk kelas FilmBioskop
	private String usiaRating;  // Klasifikasi batas usia penonton bioskop (misal: "SU", "13+", "17+", "21+")
	private String distributor; // Nama perusahaan distributor yang mendistribusikan ke bioskop
	private int biayaProduksi;  // Estimasi total anggaran biaya produksi film dalam satuan Juta Dollar (integer, misal: 237)

	// --------------------------------------------------------------------
	// KONSTRUKTOR
	// --------------------------------------------------------------------

	// Konstruktor default tanpa parameter yang memanggil konstruktor default kelas Film
	public FilmBioskop() {
		super();
		this.usiaRating = "";
		this.distributor = "";
		this.biayaProduksi = 0;
	}

	// Konstruktor berparameter yang memanggil konstruktor berparameter kelas induk (Film)
	public FilmBioskop(String id, String judul, int durasi, int tahunRilis,
			String genre, double rating, String sutradara, String studioProduksi,
			String usiaRating, String distributor, int biayaProduksi) {
		super(id, judul, durasi, tahunRilis, genre, rating, sutradara, studioProduksi);
		this.usiaRating = usiaRating;
		this.distributor = distributor;
		this.biayaProduksi = biayaProduksi;
	}

	// --------------------------------------------------------------------
	// METHOD GETTER (Untuk mengambil nilai dari setiap atribut)
	// --------------------------------------------------------------------

	// Method getter untuk mengembalikan nilai atribut usiaRating
	public String getUsiaRating() {
		return this.usiaRating;
	}

	// Method getter untuk mengembalikan nilai atribut distributor
	public String getDistributor() {
		return this.distributor;
	}

	// Method getter untuk mengembalikan nilai atribut biayaProduksi (tipe int dalam Juta Dollar)
	public int getBiayaProduksi() {
		return this.biayaProduksi;
	}

	// --------------------------------------------------------------------
	// METHOD SETTER (Untuk mengisi atau memperbarui nilai dari setiap atribut)
	// --------------------------------------------------------------------

	// Method setter untuk mengisi atau memperbarui nilai atribut usiaRating
	public void setUsiaRating(String usiaRating) {
		this.usiaRating = usiaRating;
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut distributor
	public void setDistributor(String distributor) {
		this.distributor = distributor;
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut biayaProduksi (validasi bilangan positif/nol)
	public void setBiayaProduksi(int biayaProduksi) {
		if (biayaProduksi >= 0) {
			this.biayaProduksi = biayaProduksi;
		}
	}
}
