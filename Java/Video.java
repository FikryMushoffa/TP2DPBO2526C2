// ============================================================================
// KELAS: Video (Base Class / Kelas Induk Teratas)
// Konsep: OOP Multilevel Inheritance (Level 1: Base Class)
// ============================================================================
// Kelas Video mendefinisikan atribut dan fungsionalitas dasar yang dimiliki oleh
// seluruh jenis karya video/audio-visual.
public class Video {
	// Atribut kelas Video (menggunakan hak akses protected agar dapat diwariskan ke kelas turunan)
	protected String id;          // ID unik video
	protected String judul;       // Judul karya video
	protected int durasi;         // Durasi video dalam satuan menit
	protected int tahunRilis;     // Tahun perilisan video

	// --------------------------------------------------------------------
	// KONSTRUKTOR
	// --------------------------------------------------------------------

	// Konstruktor default tanpa parameter untuk menginisialisasi atribut dengan nilai awal default
	public Video() {
		this.id = "";
		this.judul = "";
		this.durasi = 0;
		this.tahunRilis = 0;
	}

	// Konstruktor dengan parameter untuk menginisialisasi atribut sesuai argumen input
	public Video(String id, String judul, int durasi, int tahunRilis) {
		this.id = id;
		this.judul = judul;
		this.durasi = durasi;
		this.tahunRilis = tahunRilis;
	}

	// --------------------------------------------------------------------
	// METHOD GETTER (Untuk mengambil nilai dari setiap atribut)
	// --------------------------------------------------------------------

	// Method getter untuk mengembalikan nilai atribut id
	public String getId() {
		return this.id;
	}

	// Method getter untuk mengembalikan nilai atribut judul
	public String getJudul() {
		return this.judul;
	}

	// Method getter untuk mengembalikan nilai atribut durasi
	public int getDurasi() {
		return this.durasi;
	}

	// Method getter untuk mengembalikan nilai atribut tahunRilis
	public int getTahunRilis() {
		return this.tahunRilis;
	}

	// --------------------------------------------------------------------
	// METHOD SETTER (Untuk mengisi atau memperbarui nilai dari setiap atribut)
	// --------------------------------------------------------------------

	// Method setter untuk mengisi atau memperbarui nilai atribut id
	public void setId(String id) {
		this.id = id;
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut judul
	public void setJudul(String judul) {
		this.judul = judul;
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut durasi (validasi positif)
	public void setDurasi(int durasi) {
		if (durasi > 0) {
			this.durasi = durasi;
		}
	}

	// Method setter untuk mengisi atau memperbarui nilai atribut tahunRilis (validasi positif)
	public void setTahunRilis(int tahunRilis) {
		if (tahunRilis > 0) {
			this.tahunRilis = tahunRilis;
		}
	}
}
