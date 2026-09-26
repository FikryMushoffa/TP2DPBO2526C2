import java.util.ArrayList;
import java.util.Locale;
import java.util.Scanner;

// ============================================================================
// KELAS UTAMA: Main (Program Utama Sistem Database Film Bioskop)
// ============================================================================
public class Main {
	// ============================================================================
	// DEKLARASI KONSTANTA WARNA ANSI TERMINAL (STANDAR)
	// ============================================================================
	public static final String RESET = "\033[0m"; // Reset format teks ke default terminal
	public static final String DIM = "\033[2m";   // Format teks redup (dim)

	// Warna Teks Standar (Bukan Bright)
	public static final String BLACK = "\033[30m";   // Teks hitam
	public static final String RED = "\033[31m";     // Teks merah
	public static final String GREEN = "\033[32m";   // Teks hijau
	public static final String YELLOW = "\033[33m";  // Teks kuning
	public static final String BLUE = "\033[34m";    // Teks biru
	public static final String MAGENTA = "\033[35m"; // Teks magenta
	public static final String CYAN = "\033[36m";    // Teks cyan
	public static final String WHITE = "\033[37m";   // Teks putih

	// ============================================================================
	// PENYIMPANAN DATA UTAMA & DEFINISI HEADER TABEL
	// ============================================================================

	// ArrayList dinamis penampung seluruh data objek FilmBioskop di memori
	private static ArrayList<FilmBioskop> daftarFilm = new ArrayList<>();

	// ----------------------------------------------------------------------------
	// Fungsi pembantu membaca satu baris masukan langsung byte-per-byte dari System.in
	// tanpa pre-fetch buffer (berbeda dengan Scanner/BufferedReader yang mem-buffer
	// hingga 1024-8192 byte sekaligus). Hal ini memastikan terminal console echo
	// tersinkronisasi sempurna dan tetap rapi per prompt (persis seperti C++)
	// ketika pengguna menempelkan (paste) masukan multiline secara langsung.
	// ----------------------------------------------------------------------------
	public static String readLine() {
		try {
			java.io.ByteArrayOutputStream baos = new java.io.ByteArrayOutputStream();
			int c;
			while ((c = System.in.read()) != -1) {
				if (c == '\n') {
					break; // Berhenti saat menemukan pemisah baris baru (LF)
				}
				if (c != '\r') {
					baos.write(c); // Simpan byte karakter masukan (abaikan Carriage Return / CR)
				}
			}
			// Kembalikan null bila mencapai akhir stream (EOF) tanpa ada data yang terbaca
			if (c == -1 && baos.size() == 0) {
				return null;
			}
			// Mengonversi byte array menjadi string teks dengan pengkodean UTF-8 standar
			return baos.toString(java.nio.charset.StandardCharsets.UTF_8.name());
		} catch (Exception e) {
			return null;
		}
	}

	// Array 11 nama kolom tabel (keterangan satuan disematkan pada header)
	private static final String[] HEADERS = {
		"ID", "Judul Film", "Durasi (mnt)", "Tahun",
		"Genre", "Rating", "Sutradara", "Studio Produksi",
		"Usia", "Distributor", "Biaya (Juta Dollar)"
	};

	// Array penampung lebar karakter dinamis untuk masing-masing kolom tabel data
	private static int[] colWidths = new int[11];

	// ============================================================================
	// FUNGSI-FUNGSI PEMBANTU (UTILITY FUNCTIONS)
	// ============================================================================

	// Fungsi pembantu untuk mengulang sebuah karakter sebanyak n kali
	private static String repeat(char c, int count) {
		if (count <= 0) {
			return "";
		}
		StringBuilder sb = new StringBuilder(count);
		for (int i = 0; i < count; i++) {
			sb.append(c);
		}
		return sb.toString();
	}

	// Fungsi untuk membersihkan dan menghapus spasi, tab, newline, serta karakter
	// non-printable di awal/akhir
	public static String trim(String str) {
		if (str == null) {
			return "";
		}
		StringBuilder cleaned = new StringBuilder();
		for (int i = 0; i < str.length(); i++) {
			char c = str.charAt(i);
			// Ambil karakter ASCII yang dapat dicetak (abaikan karakter kontrol / BOM)
			if (c >= 32 && c <= 126) {
				cleaned.append(c);
			}
		}
		return cleaned.toString().trim();
	}

	// Fungsi untuk membandingkan dua string tanpa membedakan huruf besar/kecil
	public static boolean equalsIgnoreCase(String a, String b) {
		if (a == null || b == null) {
			return false;
		}
		return a.equalsIgnoreCase(b);
	}

	// Fungsi untuk memeriksa apakah ID film tertentu sudah ada di dalam database
	public static boolean isIdExists(String id) {
		for (FilmBioskop f : daftarFilm) {
			if (equalsIgnoreCase(f.getId(), id)) {
				return true;
			}
		}
		return false;
	}

	// Fungsi untuk memformat angka rating menjadi string 1 angka di belakang koma (misal: 7.9)
	public static String formatRating(double r) {
		return String.format(Locale.US, "%.1f", r);
	}

	// Fungsi untuk memformat angka biaya produksi bertipe integer (misal: 237)
	public static String formatBiaya(int b) {
		return String.valueOf(b);
	}

	// Fungsi untuk menempatkan teks di posisi tengah sesuai lebar kolom (center alignment pada tabel dinamis)
	public static String centerText(String text, int width) {
		if (text.length() >= width) {
			return text;
		}
		int padLeft = (width - text.length()) / 2;
		int padRight = width - text.length() - padLeft;
		return repeat(' ', padLeft) + text + repeat(' ', padRight);
	}

	// ============================================================================
	// MANAJEMEN TABEL DINAMIS (DYNAMIC TABLE FORMATTING KHUSUS SHOW DATA)
	// Digunakan karena panjang isi data film bisa berbeda-beda
	// ============================================================================

	// Fungsi untuk menghitung lebar setiap kolom secara dinamis berdasarkan data terpanjang
	public static void hitungLebarKolom() {
		// 1. Inisialisasi awal dengan panjang karakter header masing-masing kolom
		for (int i = 0; i < 11; i++) {
			colWidths[i] = HEADERS[i].length();
		}

		// 2. Iterasi ke seluruh data film untuk mencari panjang teks terpanjang
		for (FilmBioskop f : daftarFilm) {
			colWidths[0] = Math.max(colWidths[0], f.getId().length());
			colWidths[1] = Math.max(colWidths[1], f.getJudul().length());
			colWidths[2] = Math.max(colWidths[2], String.valueOf(f.getDurasi()).length());
			colWidths[3] = Math.max(colWidths[3], String.valueOf(f.getTahunRilis()).length());
			colWidths[4] = Math.max(colWidths[4], f.getGenre().length());
			colWidths[5] = Math.max(colWidths[5], formatRating(f.getRating()).length());
			colWidths[6] = Math.max(colWidths[6], f.getSutradara().length());
			colWidths[7] = Math.max(colWidths[7], f.getStudioProduksi().length());
			colWidths[8] = Math.max(colWidths[8], f.getUsiaRating().length());
			colWidths[9] = Math.max(colWidths[9], f.getDistributor().length());
			colWidths[10] = Math.max(colWidths[10], formatBiaya(f.getBiayaProduksi()).length());
		}
	}

	// Fungsi untuk menghitung total lebar horizontal keseluruhan tabel secara presisi
	public static int hitungTotalLebarTabel() {
		int total = 1; // pembatas paling kiri '|'
		for (int i = 0; i < 11; i++) {
			total += (colWidths[i] + 2) + 1; // padding 2 spasi + 1 pembatas kolom '|'
		}
		return total;
	}

	// Prosedur untuk mencetak garis pembatas horizontal tabel dinamis
	public static void cetakGarisPemisah(char persimpangan, char garis) {
		System.out.print(YELLOW + persimpangan);
		for (int i = 0; i < 11; i++) {
			System.out.print(CYAN + repeat(garis, colWidths[i] + 2) + YELLOW + persimpangan);
		}
		System.out.print(RESET + "\n");
	}

	// Prosedur untuk mencetak baris header judul kolom tabel dinamis
	public static void cetakHeaderKolom() {
		System.out.print(CYAN + "|" + RESET);
		for (int i = 0; i < 11; i++) {
			System.out.print(" " + YELLOW + centerText(HEADERS[i], colWidths[i]) + RESET
				+ " " + CYAN + "|" + RESET);
		}
		System.out.print("\n");
	}

	// Prosedur untuk mencetak satu baris data film
	public static void cetakBarisData(FilmBioskop f) {
		String[] cols = new String[11];
		cols[0] = f.getId();
		cols[1] = f.getJudul();
		cols[2] = String.valueOf(f.getDurasi());
		cols[3] = String.valueOf(f.getTahunRilis());
		cols[4] = f.getGenre();
		cols[5] = formatRating(f.getRating());
		cols[6] = f.getSutradara();
		cols[7] = f.getStudioProduksi();
		cols[8] = f.getUsiaRating();
		cols[9] = f.getDistributor();
		cols[10] = formatBiaya(f.getBiayaProduksi());

		// Pembatas kolom awal (warna cyan)
		System.out.print(CYAN + "|" + RESET);

		for (int i = 0; i < 11; i++) {
			System.out.print(" ");
			// Isi data sel tabel tetap polos tanpa warna
			if (i == 0 || i == 2 || i == 3 || i == 5 || i == 8 || i == 10) {
				System.out.print(centerText(cols[i], colWidths[i]));
			} else {
				System.out.print(cols[i] + repeat(' ', colWidths[i] - cols[i].length()));
			}
			// Pembatas kolom penutup (warna cyan)
			System.out.print(" " + CYAN + "|" + RESET);
		}
		System.out.print("\n");
	}

	// Prosedur untuk menampilkan seluruh data dalam tabel dinamis lengkap
	public static void tampilkanTabel() {
		if (daftarFilm.isEmpty()) {
			System.out.print(YELLOW + "\n  [INFO] Belum ada data film di dalam database.\n\n" + RESET);
			return;
		}

		hitungLebarKolom();
		int totalLebar = hitungTotalLebarTabel();

		System.out.print("\n");
		// 1. Garis penutup atas tabel (garis cyan, persimpangan kuning)
		System.out.print(YELLOW + "+" + CYAN + repeat('=', totalLebar - 2) + YELLOW + "+\n" + RESET);

		// 2. Baris judul utama tabel (pembatas cyan, teks judul kuning)
		System.out.print(CYAN + "|" + RESET + YELLOW
			+ centerText("DAFTAR LENGKAP KOLEKSI FILM BIOSKOP", totalLebar - 2)
			+ RESET + CYAN + "|\n" + RESET);

		// 3. Garis pemisah antara judul tabel dan header kolom
		cetakGarisPemisah('+', '=');

		// 4. Header kolom-kolom tabel (pembatas cyan, judul kolom kuning)
		cetakHeaderKolom();

		// 5. Garis pemisah di bawah header kolom
		cetakGarisPemisah('+', '=');

		// 6. Cetak seluruh baris data film
		for (int i = 0; i < daftarFilm.size(); i++) {
			cetakBarisData(daftarFilm.get(i));
			if (i + 1 < daftarFilm.size()) {
				cetakGarisPemisah('+', '-');
			} else {
				cetakGarisPemisah('+', '=');
			}
		}

		// 7. Keterangan ringkasan jumlah data di bawah tabel
		System.out.print(GREEN + "  [INFO] Total Koleksi: " + daftarFilm.size()
			+ " Film tersimpan dalam sistem.\n\n" + RESET);
	}

	// ============================================================================
	// VALIDASI & PEMBACAAN INPUT USER
	// ============================================================================

	// Fungsi untuk membaca input teks dari pengguna dengan validasi wajib diisi
	public static String inputString(String prompt) {
		String val;
		while (true) {
			System.out.print(prompt);
			System.out.flush();
			val = readLine();
			if (val == null) {
				return "";
			}
			val = trim(val);
			// Menghapus tanda petik ganda bila ada
			if (val.length() >= 2 && val.startsWith("\"") && val.endsWith("\"")) {
				val = val.substring(1, val.length() - 1);
				val = trim(val);
			}
			if (!val.isEmpty()) {
				return val;
			}
			System.out.print(RED + "    [!] Masukan tidak boleh kosong. Silakan ketik kembali.\n" + RESET);
		}
	}

	// Fungsi untuk membaca input bilangan bulat (integer) dengan rentang batasan
	public static int inputInt(String prompt, int minVal, int maxVal) {
		String raw;
		while (true) {
			System.out.print(prompt);
			System.out.flush();
			raw = readLine();
			if (raw == null) {
				return -1;
			}
			raw = trim(raw);
			try {
				int val = Integer.parseInt(raw);
				if (val >= minVal && val <= maxVal) {
					return val;
				}
			} catch (Exception e) {
			}

			System.out.print(RED + "    [!] Masukan harus berupa angka bulat antara " + minVal
				+ " s.d. " + maxVal + "!\n" + RESET);
		}
	}

	// Fungsi untuk membaca input angka desimal (double) dengan rentang nilai
	public static double inputDouble(String prompt, double minVal, double maxVal) {
		String raw;
		while (true) {
			System.out.print(prompt);
			System.out.flush();
			raw = readLine();
			if (raw == null) {
				return -1.0;
			}
			raw = trim(raw);
			try {
				double val = Double.parseDouble(raw);
				if (val >= minVal && val <= maxVal) {
					return val;
				}
			} catch (Exception e) {
			}

			System.out.print(RED + "    [!] Masukan harus berupa angka desimal antara "
				+ minVal + " s.d. " + maxVal + " (contoh: 7.5 atau 252.2)!\n" + RESET);
		}
	}

	// ============================================================================
	// FORMULIR TAMBAH DATA (BINGKAI HARDCODE KARENA PANJANGNYA TETAP)
	// ============================================================================
	public static void tambahData() {
		System.out.print("\n");
		System.out.print(YELLOW + "+" + CYAN + "==========================================================================" + YELLOW + "+\n");
		System.out.print(CYAN + "|" + RESET + YELLOW + "                    FORMULIR TAMBAH DATA FILM BIOSKOP                     " + RESET + CYAN + "|\n");
		System.out.print(YELLOW + "+" + CYAN + "==========================================================================" + YELLOW + "+\n" + RESET);

		// 1. Input ID Film unik
		String id;
		while (true) {
			id = inputString("  [ 1/11] ID Film                        : ");
			if (id.isEmpty()) {
				return;
			}
			if (!isIdExists(id)) {
				break;
			}
			System.out.print(RED + "    [!] ID Film '" + id
				+ "' sudah digunakan! Silakan gunakan ID lain.\n" + RESET);
		}

		// 2-4. Input atribut kelas Video
		String judul = inputString("  [ 2/11] Judul Film                     : ");
		int durasi = inputInt("  [ 3/11] Durasi (Menit)                 : ", 1, 1000);
		int tahunRilis = inputInt("  [ 4/11] Tahun Rilis                    : ", 1888, 2100);

		// 5-8. Input atribut kelas Film (Derived Class Level 1)
		String genre = inputString("  [ 5/11] Genre                          : ");
		double rating = inputDouble("  [ 6/11] Rating (0.0 - 10.0)            : ", 0.0, 10.0);
		String sutradara = inputString("  [ 7/11] Sutradara                      : ");
		String studio = inputString("  [ 8/11] Studio Produksi                : ");

		// 9-11. Input atribut kelas FilmBioskop (Derived Class Level 2)
		String usia = inputString("  [ 9/11] Usia Rating                    : ");
		String dist = inputString("  [10/11] Distributor                    : ");
		int biaya = inputInt("  [11/11] Biaya Produksi (Juta Dollar)   : ", 0, 999999);

		// Buat objek baru FilmBioskop dan masukkan ke dalam ArrayList daftarFilm
		FilmBioskop filmBaru = new FilmBioskop(
			id, judul, durasi, tahunRilis,
			genre, rating, sutradara, studio,
			usia, dist, biaya
		);
		daftarFilm.add(filmBaru);

		System.out.print(YELLOW + "+" + CYAN + "==========================================================================" + YELLOW + "+\n" + RESET);
		System.out.print(GREEN + "  [BERHASIL] Data Film '" + judul + "' (" + id
			+ ") berhasil ditambahkan!\n\n" + RESET);
	}

	// ============================================================================
	// TAMPILAN BANNER, MENU UTAMA, & OUTRO (TABEL HARDCODE KARENA PANJANGNYA TETAP)
	// ============================================================================

	// Prosedur menampilkan banner pembuka program (hardcode)
	public static void tampilkanBanner() {
		System.out.print("\n");
		System.out.print(YELLOW + "+" + CYAN + "==========================================================================" + YELLOW + "+\n");
		System.out.print(CYAN + "|" + RESET + YELLOW + "                       SISTEM DATABASE FILM BIOSKOP                       " + RESET + CYAN + "|\n");
		System.out.print(CYAN + "|" + RESET + YELLOW + "     Tugas Praktikum 2 (TP2) - Desain Pemrograman Berorientasi Objek      " + RESET + CYAN + "|\n");
		System.out.print(YELLOW + "+" + CYAN + "==========================================================================" + YELLOW + "+\n\n" + RESET);
	}

	// Prosedur menampilkan tabel menu utama (hardcode)
	public static void tampilkanMenu() {
		System.out.print(YELLOW + "+" + CYAN + "==========================================================================" + YELLOW + "+\n");
		System.out.print(CYAN + "|" + RESET + YELLOW + "                            MENU UTAMA SISTEM                             " + RESET + CYAN + "|\n");
		System.out.print(YELLOW + "+" + CYAN + "==========================================================================" + YELLOW + "+\n");
		System.out.print(CYAN + "|                                                                          |\n");
		System.out.print(CYAN + "|" + RESET + "  " + YELLOW + "[1]" + RESET + "  " + WHITE + "Tambah Data Film Bioskop Baru (Add)" + RESET + "                                " + CYAN + "|\n");
		System.out.print(CYAN + "|" + RESET + "  " + YELLOW + "[2]" + RESET + "  " + WHITE + "Tampilkan Tabel Lengkap Seluruh Data (Show)" + RESET + "                        " + CYAN + "|\n");
		System.out.print(CYAN + "|" + RESET + "  " + YELLOW + "[3]" + RESET + "  " + WHITE + "Keluar dari Program (Exit)" + RESET + "                                         " + CYAN + "|\n");
		System.out.print(CYAN + "|                                                                          |\n");
		System.out.print(YELLOW + "+" + CYAN + "==========================================================================" + YELLOW + "+\n" + RESET);
	}

	// Prosedur menampilkan banner penutup saat keluar dari program (hardcode)
	public static void tampilkanOutro() {
		System.out.print("\n");
		System.out.print(YELLOW + "+" + CYAN + "==========================================================================" + YELLOW + "+\n");
		System.out.print(CYAN + "|" + RESET + YELLOW + "       TERIMA KASIH TELAH MENGGUNAKAN SISTEM DATABASE FILM BIOSKOP!       " + RESET + CYAN + "|\n");
		System.out.print(CYAN + "|" + RESET + YELLOW + "            Praktikum DPBO Java Multilevel Inheritance Selesai            " + RESET + CYAN + "|\n");
		System.out.print(YELLOW + "+" + CYAN + "==========================================================================" + YELLOW + "+\n\n" + RESET);
	}

	// ============================================================================
	// MAIN PROGRAM
	// ============================================================================
	public static void main(String[] args) {
		// Inisialisasi 5 objek data film awal langsung di dalam main
		daftarFilm.add(new FilmBioskop(
			"V01", "Zootopia 2", 108, 2025, "Animasi", 7.8,
			"Byron Howard", "Walt Disney Animation Studios",
			"SU", "Walt Disney Studios", 150
		));

		daftarFilm.add(new FilmBioskop(
			"V02", "Oppenheimer", 180, 2023, "Biografi", 8.9,
			"Christopher Nolan", "Syncopy Inc.",
			"17+", "Universal Pictures", 100
		));

		daftarFilm.add(new FilmBioskop(
			"V03", "Furious 7", 137, 2015, "Action", 7.1,
			"James Wan", "Original Film",
			"13+", "Universal Pictures", 190
		));

		daftarFilm.add(new FilmBioskop(
			"V04", "The Avengers", 143, 2012, "Action", 8.0,
			"Joss Whedon", "Marvel Studios",
			"13+", "Walt Disney Studios", 220
		));

		daftarFilm.add(new FilmBioskop(
			"V05", "Titanic", 195, 1997, "Romance", 7.9,
			"James Cameron", "Lightstorm Entertainment",
			"13+", "Paramount Pictures", 200
		));

		// Menampilkan banner pembuka program
		tampilkanBanner();

		// Perulangan menu utama yang hanya menerima input valid 1, 2, atau 3
		String inputMenu;
		boolean running = true;

		while (running) {
			// Tampilkan kotak tabel menu
			tampilkanMenu();
			System.out.print(YELLOW + ">> Masukkan Pilihan Menu [1 / 2 / 3]: " + RESET);
			System.out.flush();

			// Membaca masukan menu
			inputMenu = readLine();
			if (inputMenu == null) {
				// Berhenti jika akhir stream input / EOF tercapai
				break;
			}
			inputMenu = trim(inputMenu);
			if (inputMenu.isEmpty()) {
				continue;
			}

			// Validasi ketat: HANYA opsi 1, 2, atau 3 yang diizinkan
			if (inputMenu.equals("1")) {
				tambahData();
			} else if (inputMenu.equals("2")) {
				// Tabel dinamis HANYA ditampilkan di sini ketika user memilih opsi 2
				tampilkanTabel();
			} else if (inputMenu.equals("3")) {
				tampilkanOutro();
				running = false;
			} else {
				// Menolak input lain (termasuk jika user mencoba mengetikkan ID film langsung)
				System.out.print("\n" + RED);
				System.out.print("  [ERROR] Pilihan menu '" + inputMenu + "' TIDAK VALID!\n");
				System.out.print("  Harap hanya memasukkan angka [1], [2], atau [3] sesuai menu yang tersedia.\n\n");
				System.out.print(RESET);
			}
		}
	}
}
