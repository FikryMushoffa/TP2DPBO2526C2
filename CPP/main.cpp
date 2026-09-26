#include <algorithm>
#include <iomanip>
#include <iostream>
#include <limits>
#include <sstream>
#include <string>
#include <vector>

using namespace std;

#include "FilmBioskop.cpp"

// ============================================================================
// DEKLARASI MAKRO WARNA ANSI TERMINAL (STANDAR)
// ============================================================================
#define RESET "\033[0m" // Reset format teks ke default terminal
#define DIM "\033[2m"   // Format teks redup (dim)

// Warna Teks Standar (Bukan Bright)
#define BLACK "\033[30m"   // Teks hitam
#define RED "\033[31m"     // Teks merah
#define GREEN "\033[32m"   // Teks hijau
#define YELLOW "\033[33m"  // Teks kuning
#define BLUE "\033[34m"    // Teks biru
#define MAGENTA "\033[35m" // Teks magenta
#define CYAN "\033[36m"    // Teks cyan
#define WHITE "\033[37m"   // Teks putih

// ============================================================================
// PENYIMPANAN DATA UTAMA & DEFINISI HEADER TABEL
// ============================================================================

// Vector dinamis penampung seluruh data objek FilmBioskop di memori
vector<FilmBioskop> daftarFilm;

// Array 11 nama kolom tabel (keterangan satuan disematkan pada header)
const string HEADERS[11] = {
    "ID",    "Judul Film",  "Durasi (mnt)",       "Tahun",
    "Genre", "Rating",      "Sutradara",          "Studio Produksi",
    "Usia",  "Distributor", "Biaya (Juta Dollar)"};

// Array penampung lebar karakter dinamis untuk masing-masing kolom tabel data
int colWidths[11];

// ============================================================================
// FUNGSI-FUNGSI PEMBANTU (UTILITY FUNCTIONS)
// ============================================================================

// Fungsi untuk membersihkan dan menghapus spasi, tab, newline, serta karakter
// non-printable di awal/akhir
string trim(const string &str) {
  string cleaned = "";
  for (char c : str) {
    // Ambil karakter ASCII yang dapat dicetak (abaikan karakter kontrol / BOM)
    if ((unsigned char)c >= 32 && (unsigned char)c <= 126) {
      cleaned += c;
    }
  }
  size_t first = cleaned.find_first_not_of(" \t\n\r");
  if (first == string::npos) {
    return "";
  }
  size_t last = cleaned.find_last_not_of(" \t\n\r");
  return cleaned.substr(first, (last - first + 1));
}

// Fungsi untuk membandingkan dua string tanpa membedakan huruf besar/kecil
bool equalsIgnoreCase(const string &a, const string &b) {
  if (a.length() != b.length()) {
    return false;
  }
  for (size_t i = 0; i < a.length(); i++) {
    if (tolower(a[i]) != tolower(b[i])) {
      return false;
    }
  }
  return true;
}

// Fungsi untuk memeriksa apakah ID film tertentu sudah ada di dalam database
bool isIdExists(const string &id) {
  for (const auto &f : daftarFilm) {
    if (equalsIgnoreCase(f.getId(), id)) {
      return true;
    }
  }
  return false;
}

// Fungsi untuk memformat angka rating menjadi string 1 angka di belakang koma
// (misal: 7.9)
string formatRating(double r) {
  stringstream ss;
  ss << fixed << setprecision(1) << r;
  return ss.str();
}

// Fungsi untuk menempatkan teks di posisi tengah sesuai lebar kolom (center
// alignment pada tabel dinamis)
string centerText(const string &text, int width) {
  if ((int)text.length() >= width) {
    return text;
  }
  int padLeft = (width - (int)text.length()) / 2;
  int padRight = width - (int)text.length() - padLeft;
  return string(padLeft, ' ') + text + string(padRight, ' ');
}

// ============================================================================
// MANAJEMEN TABEL DINAMIS (DYNAMIC TABLE FORMATTING KHUSUS SHOW DATA)
// Digunakan karena panjang isi data film bisa berbeda-beda
// ============================================================================

// Fungsi untuk menghitung lebar setiap kolom secara dinamis berdasarkan data
// terpanjang
void hitungLebarKolom() {
  // 1. Inisialisasi awal dengan panjang karakter header masing-masing kolom
  for (int i = 0; i < 11; i++) {
    colWidths[i] = (int)HEADERS[i].length();
  }

  // 2. Iterasi ke seluruh data film untuk mencari panjang teks terpanjang
  for (const auto &f : daftarFilm) {
    colWidths[0] = max(colWidths[0], (int)f.getId().length());
    colWidths[1] = max(colWidths[1], (int)f.getJudul().length());
    colWidths[2] = max(colWidths[2], (int)to_string(f.getDurasi()).length());
    colWidths[3] =
        max(colWidths[3], (int)to_string(f.getTahunRilis()).length());
    colWidths[4] = max(colWidths[4], (int)f.getGenre().length());
    colWidths[5] = max(colWidths[5], (int)formatRating(f.getRating()).length());
    colWidths[6] = max(colWidths[6], (int)f.getSutradara().length());
    colWidths[7] = max(colWidths[7], (int)f.getStudioProduksi().length());
    colWidths[8] = max(colWidths[8], (int)f.getUsiaRating().length());
    colWidths[9] = max(colWidths[9], (int)f.getDistributor().length());
    colWidths[10] = max(colWidths[10], (int)to_string(f.getBiayaProduksi()).length());
  }
}

// Fungsi untuk menghitung total lebar horizontal keseluruhan tabel secara
// presisi
int hitungTotalLebarTabel() {
  int total = 1; // pembatas paling kiri '|'
  for (int i = 0; i < 11; i++) {
    total += (colWidths[i] + 2) + 1; // padding 2 spasi + 1 pembatas kolom '|'
  }
  return total;
}

// Prosedur untuk mencetak garis pembatas horizontal tabel dinamis
void cetakGarisPemisah(char persimpangan = '+', char garis = '-') {
  cout << YELLOW << persimpangan;
  for (int i = 0; i < 11; i++) {
    cout << CYAN << string(colWidths[i] + 2, garis) << YELLOW << persimpangan;
  }
  cout << RESET << "\n";
}

// Prosedur untuk mencetak baris header judul kolom tabel dinamis
void cetakHeaderKolom() {
  cout << CYAN << "|" << RESET;
  for (int i = 0; i < 11; i++) {
    cout << " " << YELLOW << centerText(HEADERS[i], colWidths[i]) << RESET
         << " " << CYAN << "|" << RESET;
  }
  cout << "\n";
}

// Prosedur untuk mencetak satu baris data film
void cetakBarisData(const FilmBioskop &f) {
  string cols[11];
  cols[0] = f.getId();
  cols[1] = f.getJudul();
  cols[2] = to_string(f.getDurasi());
  cols[3] = to_string(f.getTahunRilis());
  cols[4] = f.getGenre();
  cols[5] = formatRating(f.getRating());
  cols[6] = f.getSutradara();
  cols[7] = f.getStudioProduksi();
  cols[8] = f.getUsiaRating();
  cols[9] = f.getDistributor();
  cols[10] = to_string(f.getBiayaProduksi());

  // Pembatas kolom awal (warna cyan)
  cout << CYAN << "|" << RESET;

  for (int i = 0; i < 11; i++) {
    cout << " ";
    // Isi data sel tabel tetap polos tanpa warna
    if (i == 0 || i == 2 || i == 3 || i == 5 || i == 8 || i == 10) {
      cout << centerText(cols[i], colWidths[i]);
    } else {
      cout << cols[i] << string(colWidths[i] - (int)cols[i].length(), ' ');
    }
    // Pembatas kolom penutup (warna cyan)
    cout << " " << CYAN << "|" << RESET;
  }
  cout << "\n";
}

// Prosedur untuk menampilkan seluruh data dalam tabel dinamis lengkap
void tampilkanTabel() {
  if (daftarFilm.empty()) {
    cout << YELLOW << "\n  [INFO] Belum ada data film di dalam database.\n\n"
         << RESET;
    return;
  }

  hitungLebarKolom();
  int totalLebar = hitungTotalLebarTabel();

  cout << "\n";
  // 1. Garis penutup atas tabel (garis cyan, persimpangan kuning)
  cout << YELLOW << "+" << CYAN << string(totalLebar - 2, '=') << YELLOW
       << "+\n"
       << RESET;

  // 2. Baris judul utama tabel (pembatas cyan, teks judul kuning)
  cout << CYAN << "|" << RESET << YELLOW
       << centerText("DAFTAR LENGKAP KOLEKSI FILM BIOSKOP", totalLebar - 2)
       << RESET << CYAN << "|\n"
       << RESET;

  // 3. Garis pemisah antara judul tabel dan header kolom
  cetakGarisPemisah('+', '=');

  // 4. Header kolom-kolom tabel (pembatas cyan, judul kolom kuning)
  cetakHeaderKolom();

  // 5. Garis pemisah di bawah header kolom
  cetakGarisPemisah('+', '=');

  // 6. Cetak seluruh baris data film
  for (size_t i = 0; i < daftarFilm.size(); i++) {
    cetakBarisData(daftarFilm[i]);
    if (i + 1 < daftarFilm.size()) {
      cetakGarisPemisah('+', '-');
    } else {
      cetakGarisPemisah('+', '=');
    }
  }

  // 7. Keterangan ringkasan jumlah data di bawah tabel
  cout << GREEN << "  [INFO] Total Koleksi: " << daftarFilm.size()
       << " Film tersimpan dalam sistem.\n\n"
       << RESET;
}

// ============================================================================
// VALIDASI & PEMBACAAN INPUT USER
// ============================================================================

// Fungsi untuk membaca input teks dari pengguna dengan validasi wajib diisi
string inputString(const string &prompt) {
  string val;
  while (true) {
    cout << prompt;
    if (!getline(cin, val)) {
      return "";
    }
    val = trim(val);
    // Menghapus tanda petik ganda bila ada
    if (val.length() >= 2 && val.front() == '"' && val.back() == '"') {
      val = val.substr(1, val.length() - 2);
      val = trim(val);
    }
    if (!val.empty()) {
      return val;
    }
    cout << RED
         << "    [!] Masukan tidak boleh kosong. Silakan ketik kembali.\n"
         << RESET;
  }
}

// Fungsi untuk membaca input bilangan bulat (integer) dengan rentang batasan
int inputInt(const string &prompt, int minVal, int maxVal) {
  string raw;
  while (true) {
    cout << prompt;
    if (!getline(cin, raw)) {
      return -1;
    }
    raw = trim(raw);
    try {
      size_t idx;
      int val = stoi(raw, &idx);
      if (idx == raw.length() && val >= minVal && val <= maxVal) {
        return val;
      }
    } catch (...) {
    }

    cout << RED << "    [!] Masukan harus berupa angka bulat antara " << minVal
         << " s.d. " << maxVal << "!\n"
         << RESET;
  }
}

// Fungsi untuk membaca input angka desimal (double) dengan rentang nilai
double inputDouble(const string &prompt, double minVal, double maxVal) {
  string raw;
  while (true) {
    cout << prompt;
    if (!getline(cin, raw)) {
      return -1.0;
    }
    raw = trim(raw);
    try {
      size_t idx;
      double val = stod(raw, &idx);
      if (idx == raw.length() && val >= minVal && val <= maxVal) {
        return val;
      }
    } catch (...) {
    }

    cout << RED << "    [!] Masukan harus berupa angka desimal antara "
         << minVal << " s.d. " << maxVal << " (contoh: 7.5 atau 252.2)!\n"
         << RESET;
  }
}

// ============================================================================
// FORMULIR TAMBAH DATA (BINGKAI HARDCODE KARENA PANJANGNYA TETAP)
// ============================================================================
void tambahData() {
  cout << "\n";
  cout << YELLOW << "+" << CYAN
       << "===================================================================="
          "======"
       << YELLOW << "+\n";
  cout << CYAN << "|" << RESET << YELLOW
       << "                    FORMULIR TAMBAH DATA FILM BIOSKOP               "
          "      "
       << RESET << CYAN << "|\n";
  cout << YELLOW << "+" << CYAN
       << "===================================================================="
          "======"
       << YELLOW << "+\n"
       << RESET;

  // 1. Input ID Film unik
  string id;
  while (true) {
    id = inputString("  [ 1/11] ID Film                        : ");
    if (id.empty()) {
      return;
    }
    if (!isIdExists(id)) {
      break;
    }
    cout << RED << "    [!] ID Film '" << id
         << "' sudah digunakan! Silakan gunakan ID lain.\n"
         << RESET;
  }

  // 2-4. Input atribut kelas Video
  string judul = inputString("  [ 2/11] Judul Film                     : ");
  int durasi = inputInt("  [ 3/11] Durasi (Menit)                 : ", 1, 1000);
  int tahunRilis =
      inputInt("  [ 4/11] Tahun Rilis                    : ", 1888, 2100);

  // 5-8. Input atribut kelas Film (Derived Class Level 1)
  string genre = inputString("  [ 5/11] Genre                          : ");
  double rating =
      inputDouble("  [ 6/11] Rating (0.0 - 10.0)            : ", 0.0, 10.0);
  string sutradara = inputString("  [ 7/11] Sutradara                      : ");
  string studio = inputString("  [ 8/11] Studio Produksi                : ");

  // 9-11. Input atribut kelas FilmBioskop (Derived Class Level 2)
  string usia = inputString("  [ 9/11] Usia Rating                    : ");
  string dist = inputString("  [10/11] Distributor                    : ");
  int biaya =
      inputInt("  [11/11] Biaya Produksi (Juta Dollar)   : ", 0, 999999);

  // Buat objek baru FilmBioskop dan masukkan ke dalam vector daftarFilm
  FilmBioskop filmBaru(id, judul, durasi, tahunRilis, genre, rating, sutradara,
                       studio, usia, dist, biaya);
  daftarFilm.push_back(filmBaru);

  cout << YELLOW << "+" << CYAN
       << "===================================================================="
          "======"
       << YELLOW << "+\n"
       << RESET;
  cout << GREEN << "  [BERHASIL] Data Film '" << judul << "' (" << id
       << ") berhasil ditambahkan!\n\n"
       << RESET;
}

// ============================================================================
// TAMPILAN BANNER, MENU UTAMA, & OUTRO (TABEL HARDCODE KARENA PANJANGNYA TETAP)
// ============================================================================

// Prosedur menampilkan banner pembuka program (hardcode)
void tampilkanBanner() {
  cout << "\n";
  cout << YELLOW << "+" << CYAN
       << "===================================================================="
          "======"
       << YELLOW << "+\n";
  cout << CYAN << "|" << RESET << YELLOW
       << "                       SISTEM DATABASE FILM BIOSKOP                 "
          "      "
       << RESET << CYAN << "|\n";
  cout << CYAN << "|" << RESET << YELLOW
       << "     Tugas Praktikum 2 (TP2) - Desain Pemrograman Berorientasi "
          "Objek      "
       << RESET << CYAN << "|\n";
  cout << YELLOW << "+" << CYAN
       << "===================================================================="
          "======"
       << YELLOW << "+\n\n"
       << RESET;
}

// Prosedur menampilkan tabel menu utama (hardcode)
void tampilkanMenu() {
  cout << YELLOW << "+" << CYAN
       << "===================================================================="
          "======"
       << YELLOW << "+\n";
  cout << CYAN << "|" << RESET << YELLOW
       << "                            MENU UTAMA SISTEM                       "
          "      "
       << RESET << CYAN << "|\n";
  cout << YELLOW << "+" << CYAN
       << "===================================================================="
          "======"
       << YELLOW << "+\n";
  cout << CYAN
       << "|                                                                   "
          "       |\n";
  cout << CYAN << "|" << RESET << "  " << YELLOW << "[1]" << RESET << "  "
       << WHITE << "Tambah Data Film Bioskop Baru (Add)" << RESET
       << "                                " << CYAN << "|\n";
  cout << CYAN << "|" << RESET << "  " << YELLOW << "[2]" << RESET << "  "
       << WHITE << "Tampilkan Tabel Lengkap Seluruh Data (Show)" << RESET
       << "                        " << CYAN << "|\n";
  cout << CYAN << "|" << RESET << "  " << YELLOW << "[3]" << RESET << "  "
       << WHITE << "Keluar dari Program (Exit)" << RESET
       << "                                         " << CYAN << "|\n";
  cout << CYAN
       << "|                                                                   "
          "       |\n";
  cout << YELLOW << "+" << CYAN
       << "===================================================================="
          "======"
       << YELLOW << "+\n"
       << RESET;
}

// Prosedur menampilkan banner penutup saat keluar dari program (hardcode)
void tampilkanOutro() {
  cout << "\n";
  cout << YELLOW << "+" << CYAN
       << "===================================================================="
          "======"
       << YELLOW << "+\n";
  cout << CYAN << "|" << RESET << YELLOW
       << "       TERIMA KASIH TELAH MENGGUNAKAN SISTEM DATABASE FILM BIOSKOP! "
          "      "
       << RESET << CYAN << "|\n";
  cout << CYAN << "|" << RESET << YELLOW
       << "            Praktikum DPBO C++ Multilevel Inheritance Selesai       "
          "      "
       << RESET << CYAN << "|\n";
  cout << YELLOW << "+" << CYAN
       << "===================================================================="
          "======"
       << YELLOW << "+\n\n"
       << RESET;
}

// ============================================================================
// MAIN PROGRAM
// ============================================================================
int main() {
  // Inisialisasi 5 objek data film awal langsung di dalam main
  daftarFilm.push_back(FilmBioskop("V01", "Avatar", 162, 2009, "Sci-Fi", 7.9,
                                   "James Cameron", "Lightstorm Entertainment",
                                   "13+", "20th Century Fox", 237));

  daftarFilm.push_back(FilmBioskop("V02", "Titanic", 195, 1997, "Romance", 7.9,
                                   "James Cameron", "Lightstorm Entertainment",
                                   "13+", "Paramount Pictures", 200));

  daftarFilm.push_back(FilmBioskop("V03", "The Avengers", 143, 2012, "Action",
                                   8.0, "Joss Whedon", "Marvel Studios", "13+",
                                   "Walt Disney Studios", 220));

  daftarFilm.push_back(FilmBioskop("V04", "Oppenheimer", 180, 2023, "Biografi",
                                   8.9, "Christopher Nolan", "Syncopy Inc.",
                                   "17+", "Universal Pictures", 100));

  daftarFilm.push_back(FilmBioskop(
      "V05", "Frozen II", 103, 2019, "Animasi", 6.8, "Chris Buck",
      "Walt Disney Animation Studios", "SU", "Walt Disney Studios", 150));

  // Menampilkan banner pembuka program
  tampilkanBanner();

  // Perulangan menu utama yang hanya menerima input valid 1, 2, atau 3
  string inputMenu;
  bool running = true;

  while (running) {
    // Tampilkan kotak tabel menu
    tampilkanMenu();
    cout << YELLOW << ">> Masukkan Pilihan Menu [1 / 2 / 3]: " << RESET;

    // Membaca masukan menu
    if (!getline(cin, inputMenu)) {
      // Berhenti jika akhir stream input / EOF tercapai
      break;
    }

    inputMenu = trim(inputMenu);
    if (inputMenu.empty()) {
      continue;
    }

    // Validasi ketat: HANYA opsi 1, 2, atau 3 yang diizinkan
    if (inputMenu == "1") {
      tambahData();
    } else if (inputMenu == "2") {
      // Tabel dinamis HANYA ditampilkan di sini ketika user memilih opsi 2
      tampilkanTabel();
    } else if (inputMenu == "3") {
      tampilkanOutro();
      running = false;
    } else {
      // Menolak input lain (termasuk jika user mencoba mengetikkan ID film
      // langsung)
      cout << "\n" << RED;
      cout << "  [ERROR] Pilihan menu '" << inputMenu << "' TIDAK VALID!\n";
      cout << "  Harap hanya memasukkan angka [1], [2], atau [3] sesuai menu "
              "yang tersedia.\n\n";
      cout << RESET;
    }
  }

  return 0;
}
