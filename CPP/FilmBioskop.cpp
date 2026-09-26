// Kata Bu Rosa, Kang Bintang, Kang Daffa, Library semuanya di main

#include "Film.cpp"

// ============================================================================
// KELAS: FilmBioskop (Derived Class / Kelas Turunan Level 2 dari Film)
// Konsep: OOP Multilevel Inheritance (Level 3: Video -> Film -> FilmBioskop)
// ============================================================================
// Kelas FilmBioskop mewarisi seluruh atribut dan method dari Film (dan secara
// tidak langsung dari Video), serta menambahkan atribut khusus penayangan bioskop.
class FilmBioskop : public Film {
	private:
		// Atribut tambahan khusus untuk kelas FilmBioskop
		string usiaRating;  // Klasifikasi batas usia penonton bioskop (misal: "SU", "13+", "17+", "21+")
		string distributor; // Nama perusahaan distributor yang mendistribusikan ke bioskop
		int biayaProduksi;  // Estimasi total anggaran biaya produksi film dalam satuan Juta Dollar (integer, misal: 237)

	public:
		// --------------------------------------------------------------------
		// KONSTRUKTOR
		// --------------------------------------------------------------------

		// Konstruktor default tanpa parameter yang memanggil konstruktor default kelas Film
		FilmBioskop() : Film() {
			this->usiaRating = "";
			this->distributor = "";
			this->biayaProduksi = 0;
		}

		// Konstruktor berparameter yang memanggil konstruktor berparameter kelas induk (Film)
		FilmBioskop(string id, string judul, int durasi, int tahunRilis,
			string genre, double rating, string sutradara, string studioProduksi,
			string usiaRating, string distributor, int biayaProduksi)
			: Film(id, judul, durasi, tahunRilis, genre, rating, sutradara, studioProduksi) {
			this->usiaRating = usiaRating;
			this->distributor = distributor;
			this->biayaProduksi = biayaProduksi;
		}

		// --------------------------------------------------------------------
		// METHOD GETTER (Untuk mengambil nilai dari setiap atribut)
		// --------------------------------------------------------------------

		// Method getter untuk mengembalikan nilai atribut usiaRating
		string getUsiaRating() const {
			return this->usiaRating;
		}

		// Method getter untuk mengembalikan nilai atribut distributor
		string getDistributor() const {
			return this->distributor;
		}

		// Method getter untuk mengembalikan nilai atribut biayaProduksi (tipe int dalam Juta Dollar)
		int getBiayaProduksi() const {
			return this->biayaProduksi;
		}

		// --------------------------------------------------------------------
		// METHOD SETTER (Untuk mengisi atau memperbarui nilai dari setiap atribut)
		// --------------------------------------------------------------------

		// Method setter untuk mengisi atau memperbarui nilai atribut usiaRating
		void setUsiaRating(const string& usiaRating) {
			this->usiaRating = usiaRating;
		}

		// Method setter untuk mengisi atau memperbarui nilai atribut distributor
		void setDistributor(const string& distributor) {
			this->distributor = distributor;
		}

		// Method setter untuk mengisi atau memperbarui nilai atribut biayaProduksi (validasi bilangan positif/nol)
		void setBiayaProduksi(int biayaProduksi) {
			if (biayaProduksi >= 0) {
				this->biayaProduksi = biayaProduksi;
			}
		}

		// --------------------------------------------------------------------
		// DESTRUKTOR
		// --------------------------------------------------------------------

		// Destruktor virtual untuk kelas FilmBioskop
		virtual ~FilmBioskop() {
		}
};
