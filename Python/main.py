from FilmBioskop import FilmBioskop

# ============================================================================
# DEKLARASI KONSTANTA WARNA ANSI TERMINAL (STANDAR)
# ============================================================================
RESET = "\033[0m"    # Reset format teks ke default terminal
DIM = "\033[2m"      # Format teks redup (dim)

# Warna Teks Standar (Bukan Bright)
BLACK = "\033[30m"   # Teks hitam
RED = "\033[31m"     # Teks merah
GREEN = "\033[32m"   # Teks hijau
YELLOW = "\033[33m"  # Teks kuning
BLUE = "\033[34m"    # Teks biru
MAGENTA = "\033[35m" # Teks magenta
CYAN = "\033[36m"    # Teks cyan
WHITE = "\033[37m"   # Teks putih

# ============================================================================
# PENYIMPANAN DATA UTAMA & DEFINISI HEADER TABEL
# ============================================================================

# List dinamis penampung seluruh data objek FilmBioskop di memori
daftarFilm = []

# Array 11 nama kolom tabel (keterangan satuan disematkan pada header)
HEADERS = [
    "ID", "Judul Film", "Durasi (mnt)", "Tahun",
    "Genre", "Rating", "Sutradara", "Studio Produksi",
    "Usia", "Distributor", "Biaya (Juta Dollar)"
]

# List penampung lebar karakter dinamis untuk masing-masing kolom tabel data
colWidths = [len(h) for h in HEADERS]

# ============================================================================
# FUNGSI-FUNGSI PEMBANTU (UTILITY FUNCTIONS)
# ============================================================================

# Fungsi untuk membersihkan dan menghapus spasi, tab, newline, serta karakter
# non-printable di awal/akhir
def trim(text):
    if text is None:
        return ""
    # Ambil karakter ASCII yang dapat dicetak (abaikan karakter kontrol / BOM)
    cleaned = "".join(c for c in str(text) if 32 <= ord(c) <= 126)
    return cleaned.strip()

# Fungsi untuk membandingkan dua string tanpa membedakan huruf besar/kecil
def equalsIgnoreCase(a, b):
    if a is None or b is None:
        return False
    return a.casefold() == b.casefold()

# Fungsi untuk memeriksa apakah ID film tertentu sudah ada di dalam database
def isIdExists(id_val):
    for f in daftarFilm:
        if equalsIgnoreCase(f.getId(), id_val):
            return True
    return False

# Fungsi untuk memformat angka rating menjadi string 1 angka di belakang koma (misal: 7.9)
def formatRating(r):
    return f"{r:.1f}"

# Fungsi untuk memformat angka biaya produksi bertipe integer (misal: 237)
def formatBiaya(b):
    return str(b)

# Fungsi untuk menempatkan teks di posisi tengah sesuai lebar kolom (center alignment pada tabel dinamis)
def centerText(text, width):
    text_str = str(text)
    if len(text_str) >= width:
        return text_str
    pad_left = (width - len(text_str)) // 2
    pad_right = width - len(text_str) - pad_left
    return (" " * pad_left) + text_str + (" " * pad_right)

# ============================================================================
# MANAJEMEN TABEL DINAMIS (DYNAMIC TABLE FORMATTING KHUSUS SHOW DATA)
# Digunakan karena panjang isi data film bisa berbeda-beda
# ============================================================================

# Fungsi untuk menghitung lebar setiap kolom secara dinamis berdasarkan data terpanjang
def hitungLebarKolom():
    # 1. Inisialisasi awal dengan panjang karakter header masing-masing kolom
    for i in range(11):
        colWidths[i] = len(HEADERS[i])

    # 2. Iterasi ke seluruh data film untuk mencari panjang teks terpanjang
    for f in daftarFilm:
        colWidths[0] = max(colWidths[0], len(f.getId()))
        colWidths[1] = max(colWidths[1], len(f.getJudul()))
        colWidths[2] = max(colWidths[2], len(str(f.getDurasi())))
        colWidths[3] = max(colWidths[3], len(str(f.getTahunRilis())))
        colWidths[4] = max(colWidths[4], len(f.getGenre()))
        colWidths[5] = max(colWidths[5], len(formatRating(f.getRating())))
        colWidths[6] = max(colWidths[6], len(f.getSutradara()))
        colWidths[7] = max(colWidths[7], len(f.getStudioProduksi()))
        colWidths[8] = max(colWidths[8], len(f.getUsiaRating()))
        colWidths[9] = max(colWidths[9], len(f.getDistributor()))
        colWidths[10] = max(colWidths[10], len(formatBiaya(f.getBiayaProduksi())))

# Fungsi untuk menghitung total lebar horizontal keseluruhan tabel secara presisi
def hitungTotalLebarTabel():
    total = 1  # pembatas paling kiri '|'
    for i in range(11):
        total += (colWidths[i] + 2) + 1  # padding 2 spasi + 1 pembatas kolom '|'
    return total

# Prosedur untuk mencetak garis pembatas horizontal tabel dinamis
def cetakGarisPemisah(persimpangan='+', garis='-'):
    print(YELLOW + persimpangan, end="")
    for i in range(11):
        print(CYAN + (garis * (colWidths[i] + 2)) + YELLOW + persimpangan, end="")
    print(RESET)

# Prosedur untuk mencetak baris header judul kolom tabel dinamis
def cetakHeaderKolom():
    print(CYAN + "|" + RESET, end="")
    for i in range(11):
        print(" " + YELLOW + centerText(HEADERS[i], colWidths[i]) + RESET + " " + CYAN + "|" + RESET, end="")
    print()

# Prosedur untuk mencetak satu baris data film
def cetakBarisData(f):
    cols = [
        f.getId(),
        f.getJudul(),
        str(f.getDurasi()),
        str(f.getTahunRilis()),
        f.getGenre(),
        formatRating(f.getRating()),
        f.getSutradara(),
        f.getStudioProduksi(),
        f.getUsiaRating(),
        f.getDistributor(),
        formatBiaya(f.getBiayaProduksi())
    ]

    # Pembatas kolom awal (warna cyan)
    print(CYAN + "|" + RESET, end="")

    for i in range(11):
        print(" ", end="")
        # Kolom angka dan kategori tertentu dicetak rata tengah (center)
        if i in (0, 2, 3, 5, 8, 10):
            print(centerText(cols[i], colWidths[i]), end="")
        else:
            # Kolom teks biasa dicetak rata kiri (left aligned)
            print(cols[i] + (" " * (colWidths[i] - len(cols[i]))), end="")
        # Pembatas kolom penutup (warna cyan)
        print(" " + CYAN + "|" + RESET, end="")
    print()

# Prosedur untuk menampilkan seluruh data dalam tabel dinamis lengkap
def tampilkanTabel():
    if len(daftarFilm) == 0:
        print(YELLOW + "\n  [INFO] Belum ada data film di dalam database.\n\n" + RESET)
        return

    hitungLebarKolom()
    total_lebar = hitungTotalLebarTabel()

    print()
    # 1. Garis penutup atas tabel (garis cyan, persimpangan kuning)
    print(YELLOW + "+" + CYAN + ("=" * (total_lebar - 2)) + YELLOW + "+\n" + RESET, end="")

    # 2. Baris judul utama tabel (pembatas cyan, teks judul kuning)
    print(CYAN + "|" + RESET + YELLOW + centerText("DAFTAR LENGKAP KOLEKSI FILM BIOSKOP", total_lebar - 2) + RESET + CYAN + "|\n" + RESET, end="")

    # 3. Garis pemisah antara judul tabel dan header kolom
    cetakGarisPemisah('+', '=')

    # 4. Header kolom-kolom tabel (pembatas cyan, judul kolom kuning)
    cetakHeaderKolom()

    # 5. Garis pemisah di bawah header kolom
    cetakGarisPemisah('+', '=')

    # 6. Cetak seluruh baris data film
    for i, film in enumerate(daftarFilm):
        cetakBarisData(film)
        if i + 1 < len(daftarFilm):
            cetakGarisPemisah('+', '-')
        else:
            cetakGarisPemisah('+', '=')

    # 7. Keterangan ringkasan jumlah data di bawah tabel
    print(GREEN + f"  [INFO] Total Koleksi: {len(daftarFilm)} Film tersimpan dalam sistem.\n\n" + RESET)

# ============================================================================
# VALIDASI & PEMBACAAN INPUT USER
# ============================================================================

# Fungsi untuk membaca input teks dari pengguna dengan validasi wajib diisi
def inputString(prompt):
    while True:
        try:
            val = input(prompt)
        except EOFError:
            return ""
        val = trim(val)
        # Menghapus tanda petik ganda bila ada
        if len(val) >= 2 and val.startswith('"') and val.endswith('"'):
            val = val[1:-1]
            val = trim(val)
        if val != "":
            return val
        print(RED + "    [!] Masukan tidak boleh kosong. Silakan ketik kembali.\n" + RESET, end="")

# Fungsi untuk membaca input bilangan bulat (integer) dengan rentang batasan
def inputInt(prompt, min_val, max_val):
    while True:
        try:
            raw = input(prompt)
        except EOFError:
            return -1
        raw = trim(raw)
        try:
            val = int(raw)
            if min_val <= val <= max_val:
                return val
        except ValueError:
            pass

        print(RED + f"    [!] Masukan harus berupa angka bulat antara {min_val} s.d. {max_val}!\n" + RESET, end="")

# Fungsi untuk membaca input angka desimal (float/double) dengan rentang nilai
def inputDouble(prompt, min_val, max_val):
    while True:
        try:
            raw = input(prompt)
        except EOFError:
            return -1.0
        raw = trim(raw)
        try:
            val = float(raw)
            if min_val <= val <= max_val:
                return val
        except ValueError:
            pass

        print(RED + f"    [!] Masukan harus berupa angka desimal antara {min_val} s.d. {max_val} (contoh: 7.5 atau 252.2)!\n" + RESET, end="")

# ============================================================================
# FORMULIR TAMBAH DATA (BINGKAI HARDCODE KARENA PANJANGNYA TETAP)
# ============================================================================
def tambahData():
    print()
    print(YELLOW + "+" + CYAN + ("=" * 74) + YELLOW + "+")
    print(CYAN + "|" + RESET + YELLOW + "                    FORMULIR TAMBAH DATA FILM BIOSKOP                     " + RESET + CYAN + "|")
    print(YELLOW + "+" + CYAN + ("=" * 74) + YELLOW + "+\n" + RESET, end="")

    # 1. Input ID Film unik
    while True:
        id_film = inputString("  [ 1/11] ID Film                        : ")
        if not id_film:
            return
        if not isIdExists(id_film):
            break
        print(RED + f"    [!] ID Film '{id_film}' sudah digunakan! Silakan gunakan ID lain.\n" + RESET, end="")

    # 2-4. Input atribut kelas Video
    judul = inputString("  [ 2/11] Judul Film                     : ")
    durasi = inputInt("  [ 3/11] Durasi (Menit)                 : ", 1, 1000)
    tahun_rilis = inputInt("  [ 4/11] Tahun Rilis                    : ", 1888, 2100)

    # 5-8. Input atribut kelas Film (Derived Class Level 1)
    genre = inputString("  [ 5/11] Genre                          : ")
    rating = inputDouble("  [ 6/11] Rating (0.0 - 10.0)            : ", 0.0, 10.0)
    sutradara = inputString("  [ 7/11] Sutradara                      : ")
    studio = inputString("  [ 8/11] Studio Produksi                : ")

    # 9-11. Input atribut kelas FilmBioskop (Derived Class Level 2)
    usia = inputString("  [ 9/11] Usia Rating                    : ")
    dist = inputString("  [10/11] Distributor                    : ")
    biaya = inputInt("  [11/11] Biaya Produksi (Juta Dollar)   : ", 0, 999999)

    # Buat objek baru FilmBioskop dan masukkan ke dalam list daftarFilm
    film_baru = FilmBioskop(
        id_film, judul, durasi, tahun_rilis,
        genre, rating, sutradara, studio,
        usia, dist, biaya
    )
    daftarFilm.append(film_baru)

    print(YELLOW + "+" + CYAN + ("=" * 74) + YELLOW + "+\n" + RESET, end="")
    print(GREEN + f"  [BERHASIL] Data Film '{judul}' ({id_film}) berhasil ditambahkan!\n\n" + RESET, end="")

# ============================================================================
# TAMPILAN BANNER, MENU UTAMA, & OUTRO (TABEL HARDCODE KARENA PANJANGNYA TETAP)
# ============================================================================

# Prosedur menampilkan banner pembuka program (hardcode)
def tampilkanBanner():
    print()
    print(YELLOW + "+" + CYAN + ("=" * 74) + YELLOW + "+")
    print(CYAN + "|" + RESET + YELLOW + "                       SISTEM DATABASE FILM BIOSKOP                       " + RESET + CYAN + "|")
    print(CYAN + "|" + RESET + YELLOW + "     Tugas Praktikum 2 (TP2) - Desain Pemrograman Berorientasi Objek      " + RESET + CYAN + "|")
    print(YELLOW + "+" + CYAN + ("=" * 74) + YELLOW + "+\n\n" + RESET, end="")

# Prosedur menampilkan tabel menu utama (hardcode)
def tampilkanMenu():
    print(YELLOW + "+" + CYAN + ("=" * 74) + YELLOW + "+")
    print(CYAN + "|" + RESET + YELLOW + "                            MENU UTAMA SISTEM                             " + RESET + CYAN + "|")
    print(YELLOW + "+" + CYAN + ("=" * 74) + YELLOW + "+")
    print(CYAN + "|                                                                          |")
    print(CYAN + "|" + RESET + "  " + YELLOW + "[1]" + RESET + "  " + WHITE + "Tambah Data Film Bioskop Baru (Add)" + RESET + "                                " + CYAN + "|")
    print(CYAN + "|" + RESET + "  " + YELLOW + "[2]" + RESET + "  " + WHITE + "Tampilkan Tabel Lengkap Seluruh Data (Show)" + RESET + "                        " + CYAN + "|")
    print(CYAN + "|" + RESET + "  " + YELLOW + "[3]" + RESET + "  " + WHITE + "Keluar dari Program (Exit)" + RESET + "                                         " + CYAN + "|")
    print(CYAN + "|                                                                          |")
    print(YELLOW + "+" + CYAN + ("=" * 74) + YELLOW + "+\n" + RESET, end="")

# Prosedur menampilkan banner penutup saat keluar dari program (hardcode)
def tampilkanOutro():
    print()
    print(YELLOW + "+" + CYAN + ("=" * 74) + YELLOW + "+")
    print(CYAN + "|" + RESET + YELLOW + "       TERIMA KASIH TELAH MENGGUNAKAN SISTEM DATABASE FILM BIOSKOP!       " + RESET + CYAN + "|")
    print(CYAN + "|" + RESET + YELLOW + "           Praktikum DPBO Python Multilevel Inheritance Selesai           " + RESET + CYAN + "|")
    print(YELLOW + "+" + CYAN + ("=" * 74) + YELLOW + "+\n\n" + RESET, end="")

# ============================================================================
# MAIN PROGRAM
# ============================================================================
def main():
    # Inisialisasi 5 objek data film awal langsung di dalam main
    daftarFilm.append(FilmBioskop(
        "V01", "Zootopia 2", 108, 2025, "Animasi", 7.8,
        "Byron Howard", "Walt Disney Animation Studios",
        "SU", "Walt Disney Studios", 150
    ))

    daftarFilm.append(FilmBioskop(
        "V02", "Furious 7", 137, 2015, "Action", 7.1,
        "James Wan", "Original Film",
        "13+", "Universal Pictures", 190
    ))

    daftarFilm.append(FilmBioskop(
        "V03", "Titanic", 195, 1997, "Romance", 7.9,
        "James Cameron", "Lightstorm Entertainment",
        "13+", "Paramount Pictures", 200
    ))

    daftarFilm.append(FilmBioskop(
        "V04", "The Avengers", 143, 2012, "Action", 8.0,
        "Joss Whedon", "Marvel Studios",
        "13+", "Walt Disney Studios", 220
    ))

    daftarFilm.append(FilmBioskop(
        "V05", "Oppenheimer", 180, 2023, "Biografi", 8.9,
        "Christopher Nolan", "Syncopy Inc.",
        "17+", "Universal Pictures", 100
    ))

    # Menampilkan banner pembuka program
    tampilkanBanner()

    # Perulangan menu utama yang hanya menerima input valid 1, 2, atau 3
    running = True

    while running:
        # Tampilkan kotak tabel menu
        tampilkanMenu()
        try:
            input_menu = input(YELLOW + ">> Masukkan Pilihan Menu [1 / 2 / 3]: " + RESET)
        except EOFError:
            # Berhenti jika akhir stream input / EOF tercapai
            break

        input_menu = trim(input_menu)
        if not input_menu:
            continue

        # Validasi ketat: HANYA opsi 1, 2, atau 3 yang diizinkan
        if input_menu == "1":
            tambahData()
        elif input_menu == "2":
            # Tabel dinamis HANYA ditampilkan di sini ketika user memilih opsi 2
            tampilkanTabel()
        elif input_menu == "3":
            tampilkanOutro()
            running = False
        else:
            # Menolak input lain (termasuk jika user mencoba mengetikkan ID film langsung)
            print("\n" + RED, end="")
            print(f"  [ERROR] Pilihan menu '{input_menu}' TIDAK VALID!")
            print("  Harap hanya memasukkan angka [1], [2], atau [3] sesuai menu yang tersedia.\n")
            print(RESET, end="")

if __name__ == "__main__":
    main()
