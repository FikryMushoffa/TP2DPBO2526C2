# ============================================================================
# KELAS: Video (Base Class / Kelas Induk Teratas)
# Konsep: OOP Multilevel Inheritance (Level 1: Base Class)
# ============================================================================
# Kelas Video mendefinisikan atribut dan fungsionalitas dasar yang dimiliki oleh
# seluruh jenis karya video/audio-visual.
class Video:
	# --------------------------------------------------------------------
	# KONSTRUKTOR
	# --------------------------------------------------------------------

	# Konstruktor dengan parameter default untuk menginisialisasi atribut
	def __init__(self, id="", judul="", durasi=0, tahunRilis=0):
		# Atribut kelas Video (menggunakan hak akses protected agar dapat diwariskan ke kelas turunan)
		self._id = id                  # ID unik video
		self._judul = judul            # Judul karya video
		self._durasi = durasi          # Durasi video dalam satuan menit
		self._tahunRilis = tahunRilis  # Tahun perilisan video

	# --------------------------------------------------------------------
	# METHOD GETTER (Untuk mengambil nilai dari setiap atribut)
	# --------------------------------------------------------------------

	# Method getter untuk mengembalikan nilai atribut id
	def getId(self):
		return self._id

	# Method getter untuk mengembalikan nilai atribut judul
	def getJudul(self):
		return self._judul

	# Method getter untuk mengembalikan nilai atribut durasi
	def getDurasi(self):
		return self._durasi

	# Method getter untuk mengembalikan nilai atribut tahunRilis
	def getTahunRilis(self):
		return self._tahunRilis

	# --------------------------------------------------------------------
	# METHOD SETTER (Untuk mengisi atau memperbarui nilai dari setiap atribut)
	# --------------------------------------------------------------------

	# Method setter untuk mengisi atau memperbarui nilai atribut id
	def setId(self, id):
		self._id = id

	# Method setter untuk mengisi atau memperbarui nilai atribut judul
	def setJudul(self, judul):
		self._judul = judul

	# Method setter untuk mengisi atau memperbarui nilai atribut durasi (validasi positif)
	def setDurasi(self, durasi):
		if durasi > 0:
			self._durasi = durasi

	# Method setter untuk mengisi atau memperbarui nilai atribut tahunRilis (validasi positif)
	def setTahunRilis(self, tahunRilis):
		if tahunRilis > 0:
			self._tahunRilis = tahunRilis
