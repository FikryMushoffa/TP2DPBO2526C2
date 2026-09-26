// Kata Bu Rosa, Kang Bintang, Kang Daffa, Library semuanya di main

// ============================================================================
// KELAS: Video (Base Class / Kelas Induk Teratas)
// Konsep: OOP Multilevel Inheritance (Level 1: Base Class)
// ============================================================================
// Kelas Video mendefinisikan atribut dan fungsionalitas dasar yang dimiliki oleh
// seluruh jenis karya video/audio-visual.
class Video {
	protected:
		// Atribut kelas Video (menggunakan hak akses protected agar dapat diwariskan ke kelas turunan)
		string id;          // ID unik video
		string judul;       // Judul karya video
		int durasi;         // Durasi video dalam satuan menit
		int tahunRilis;     // Tahun perilisan video

	public:
		// --------------------------------------------------------------------
		// KONSTRUKTOR
		// --------------------------------------------------------------------

		// Konstruktor default tanpa parameter untuk menginisialisasi atribut dengan nilai awal default
		Video() {
			this->id = "";
			this->judul = "";
			this->durasi = 0;
			this->tahunRilis = 0;
		}

		// Konstruktor dengan parameter untuk menginisialisasi atribut sesuai argumen input
		Video(string id, string judul, int durasi, int tahunRilis) {
			this->id = id;
			this->judul = judul;
			this->durasi = durasi;
			this->tahunRilis = tahunRilis;
		}

		// --------------------------------------------------------------------
		// METHOD GETTER (Untuk mengambil nilai dari setiap atribut)
		// --------------------------------------------------------------------

		// Method getter untuk mengembalikan nilai atribut id
		string getId() const {
			return this->id;
		}

		// Method getter untuk mengembalikan nilai atribut judul
		string getJudul() const {
			return this->judul;
		}

		// Method getter untuk mengembalikan nilai atribut durasi
		int getDurasi() const {
			return this->durasi;
		}

		// Method getter untuk mengembalikan nilai atribut tahunRilis
		int getTahunRilis() const {
			return this->tahunRilis;
		}

		// --------------------------------------------------------------------
		// METHOD SETTER (Untuk mengisi atau memperbarui nilai dari setiap atribut)
		// --------------------------------------------------------------------

		// Method setter untuk mengisi atau memperbarui nilai atribut id
		void setId(const string& id) {
			this->id = id;
		}

		// Method setter untuk mengisi atau memperbarui nilai atribut judul
		void setJudul(const string& judul) {
			this->judul = judul;
		}

		// Method setter untuk mengisi atau memperbarui nilai atribut durasi (validasi positif)
		void setDurasi(int durasi) {
			if (durasi > 0) {
				this->durasi = durasi;
			}
		}

		// Method setter untuk mengisi atau memperbarui nilai atribut tahunRilis (validasi positif)
		void setTahunRilis(int tahunRilis) {
			if (tahunRilis > 0) {
				this->tahunRilis = tahunRilis;
			}
		}

		// --------------------------------------------------------------------
		// DESTRUKTOR
		// --------------------------------------------------------------------

		// Destruktor virtual untuk membersihkan alokasi memori saat objek dihapus
		virtual ~Video() {
		}
};
