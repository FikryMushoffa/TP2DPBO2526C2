from Video import Video

# ============================================================================
# KELAS: Film (Derived Class / Kelas Turunan Level 1 dari Video)
# Konsep: OOP Multilevel Inheritance (Level 2: Video -> Film)
# ============================================================================
# Kelas Film mewarisi semua atribut dan method dari kelas Video (Induk),
# kemudian menambahkan atribut serta method spesifik perfilman.
class Film(Video):
	# --------------------------------------------------------------------
	# KONSTRUKTOR
	# --------------------------------------------------------------------

	# Konstruktor berparameter yang memanggil konstruktor kelas induk (Video)
	def __init__(self, id="", judul="", durasi=0, tahunRilis=0,
				 genre="", rating=0.0, sutradara="", studioProduksi=""):
		super().__init__(id, judul, durasi, tahunRilis)
		# Atribut tambahan khusus untuk kelas Film (bersifat protected untuk diwariskan ke FilmBioskop)
		self._genre = genre                      # Genre film (misal: Action, Sci-Fi, Romance, Animasi)
		self._rating = rating                    # Nilai rating kualitas film (skala 0.0 - 10.0)
		self._sutradara = sutradara              # Nama sutradara pengarah film
		self._studioProduksi = studioProduksi    # Nama studio/rumah produksi pembuat film

	# --------------------------------------------------------------------
	# METHOD GETTER (Untuk mengambil nilai dari setiap atribut)
	# --------------------------------------------------------------------

	# Method getter untuk mengembalikan nilai atribut genre
	def getGenre(self):
		return self._genre

	# Method getter untuk mengembalikan nilai atribut rating
	def getRating(self):
		return self._rating

	# Method getter untuk mengembalikan nilai atribut sutradara
	def getSutradara(self):
		return self._sutradara

	# Method getter untuk mengembalikan nilai atribut studioProduksi
	def getStudioProduksi(self):
		return self._studioProduksi

	# --------------------------------------------------------------------
	# METHOD SETTER (Untuk mengisi atau memperbarui nilai dari setiap atribut)
	# --------------------------------------------------------------------

	# Method setter untuk mengisi atau memperbarui nilai atribut genre
	def setGenre(self, genre):
		self._genre = genre

	# Method setter untuk mengisi atau memperbarui nilai atribut rating (rentang 0.0 - 10.0)
	def setRating(self, rating):
		if 0.0 <= rating <= 10.0:
			self._rating = rating

	# Method setter untuk mengisi atau memperbarui nilai atribut sutradara
	def setSutradara(self, sutradara):
		self._sutradara = sutradara

	# Method setter untuk mengisi atau memperbarui nilai atribut studioProduksi
	def setStudioProduksi(self, studioProduksi):
		self._studioProduksi = studioProduksi
