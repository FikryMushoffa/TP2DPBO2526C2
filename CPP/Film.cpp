// Kata Bu Rosa, Kang Bintang, Kang Daffa, Library semuanya di main

#include "Video.cpp"

// ============================================================================
// KELAS: Film (Derived Class / Kelas Turunan Level 1 dari Video)
// Konsep: OOP Multilevel Inheritance (Level 2: Video -> Film)
// ============================================================================
// Kelas Film mewarisi semua atribut dan method dari kelas Video (Induk),
// kemudian menambahkan atribut serta method spesifik perfilman.
class Film : public Video {
	protected:
		// Atribut tambahan khusus untuk kelas Film (bersifat protected untuk diwariskan ke FilmBioskop)
		string genre;          // Genre film (misal: Action, Sci-Fi, Romance, Animasi)
		double rating;         // Nilai rating kualitas film (skala 0.0 - 10.0)
		string sutradara;      // Nama sutradara pengarah film
		string studioProduksi; // Nama studio/rumah produksi pembuat film

	public:
		// --------------------------------------------------------------------
		// KONSTRUKTOR
		// --------------------------------------------------------------------

		// Konstruktor default tanpa parameter yang memanggil konstruktor default Video
		Film() : Video() {
			this->genre = "";
			this->rating = 0.0;
			this->sutradara = "";
			this->studioProduksi = "";
		}

		// Konstruktor berparameter yang memanggil konstruktor berparameter kelas Video
		Film(string id, string judul, int durasi, int tahunRilis,
			string genre, double rating, string sutradara, string studioProduksi)
			: Video(id, judul, durasi, tahunRilis) {
			this->genre = genre;
			this->rating = rating;
			this->sutradara = sutradara;
			this->studioProduksi = studioProduksi;
		}

		// --------------------------------------------------------------------
		// METHOD GETTER (Untuk mengambil nilai dari setiap atribut)
		// --------------------------------------------------------------------

		// Method getter untuk mengembalikan nilai atribut genre
		string getGenre() const {
			return this->genre;
		}

		// Method getter untuk mengembalikan nilai atribut rating
		double getRating() const {
			return this->rating;
		}

		// Method getter untuk mengembalikan nilai atribut sutradara
		string getSutradara() const {
			return this->sutradara;
		}

		// Method getter untuk mengembalikan nilai atribut studioProduksi
		string getStudioProduksi() const {
			return this->studioProduksi;
		}

		// --------------------------------------------------------------------
		// METHOD SETTER (Untuk mengisi atau memperbarui nilai dari setiap atribut)
		// --------------------------------------------------------------------

		// Method setter untuk mengisi atau memperbarui nilai atribut genre
		void setGenre(const string& genre) {
			this->genre = genre;
		}

		// Method setter untuk mengisi atau memperbarui nilai atribut rating (rentang 0.0 - 10.0)
		void setRating(double rating) {
			if (rating >= 0.0 && rating <= 10.0) {
				this->rating = rating;
			}
		}

		// Method setter untuk mengisi atau memperbarui nilai atribut sutradara
		void setSutradara(const string& sutradara) {
			this->sutradara = sutradara;
		}

		// Method setter untuk mengisi atau memperbarui nilai atribut studioProduksi
		void setStudioProduksi(const string& studioProduksi) {
			this->studioProduksi = studioProduksi;
		}

		// --------------------------------------------------------------------
		// DESTRUKTOR
		// --------------------------------------------------------------------

		// Destruktor virtual untuk kelas Film
		virtual ~Film() {
		}
};
