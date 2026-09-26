from Film import Film

# ============================================================================
# KELAS: FilmBioskop (Derived Class / Kelas Turunan Level 2 dari Film)
# Konsep: OOP Multilevel Inheritance (Level 3: Video -> Film -> FilmBioskop)
# ============================================================================
# Kelas FilmBioskop mewarisi seluruh atribut dan method dari Film (dan secara
# tidak langsung dari Video), serta menambahkan atribut khusus penayangan bioskop.
class FilmBioskop(Film):
	# --------------------------------------------------------------------
	# KONSTRUKTOR
	# --------------------------------------------------------------------

	# Konstruktor berparameter yang memanggil konstruktor berparameter kelas induk (Film)
	def __init__(self, id="", judul="", durasi=0, tahunRilis=0,
				 genre="", rating=0.0, sutradara="", studioProduksi="",
				 usiaRating="", distributor="", biayaProduksi=0):
		super().__init__(id, judul, durasi, tahunRilis, genre, rating, sutradara, studioProduksi)
		# Atribut tambahan khusus untuk kelas FilmBioskop
		self.__usiaRating = usiaRating        # Klasifikasi batas usia penonton bioskop (misal: "SU", "13+", "17+", "21+")
		self.__distributor = distributor      # Nama perusahaan distributor yang mendistribusikan ke bioskop
		self.__biayaProduksi = biayaProduksi  # Estimasi total anggaran biaya produksi film dalam satuan Juta Dollar (integer, misal: 237)

	# --------------------------------------------------------------------
	# METHOD GETTER (Untuk mengambil nilai dari setiap atribut)
	# --------------------------------------------------------------------

	# Method getter untuk mengembalikan nilai atribut usiaRating
	def getUsiaRating(self):
		return self.__usiaRating

	# Method getter untuk mengembalikan nilai atribut distributor
	def getDistributor(self):
		return self.__distributor

	# Method getter untuk mengembalikan nilai atribut biayaProduksi (tipe int dalam Juta Dollar)
	def getBiayaProduksi(self):
		return self.__biayaProduksi

	# --------------------------------------------------------------------
	# METHOD SETTER (Untuk mengisi atau memperbarui nilai dari setiap atribut)
	# --------------------------------------------------------------------

	# Method setter untuk mengisi atau memperbarui nilai atribut usiaRating
	def setUsiaRating(self, usiaRating):
		self.__usiaRating = usiaRating

	# Method setter untuk mengisi atau memperbarui nilai atribut distributor
	def setDistributor(self, distributor):
		self.__distributor = distributor

	# Method setter untuk mengisi atau memperbarui nilai atribut biayaProduksi (validasi bilangan positif/nol)
	def setBiayaProduksi(self, biayaProduksi):
		if biayaProduksi >= 0:
			self.__biayaProduksi = biayaProduksi
