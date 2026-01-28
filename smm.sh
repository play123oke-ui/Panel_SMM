#!/bin/bash

# Buat folder
mkdir -p panel
cd panel

# Buat file database
touch database.db

# Buat file panel.sh
cat > panel.sh <<EOF
#!/bin/bash

# Konfigurasi
DB_FILE="database.db"
API_KEY="YOUR_YOUTUBE_API_KEY"

# Fungsi login
login() {
  echo "Pilih jenis login:"
  echo "1. Admin"
  echo "2. Member"
  read -p "Pilih menu: " menu
  case \$menu in
    1) login_admin ;;
    2) login_member ;;
    *) echo "Menu tidak tersedia!" ;;
  esac
}

# Fungsi login admin
login_admin() {
  echo "Masukkan username admin:"
  read username
  echo "Masukkan password admin:"
  read -s password
  if [ "\$username" == "admin" ] && [ "\$password" == "password" ]; then
    echo "Login admin berhasil!"
    dashboard_admin
  else
    echo "Login admin gagal!"
  fi
}

# Fungsi login member
login_member() {
  echo "Masukkan username member:"
  read username
  echo "Masukkan password member:"
  read -s password
  if sqlite3 \$DB_FILE "SELECT * FROM member WHERE username = '\$username' AND password = '\$password'" | grep -q .; then
    echo "Login member berhasil!"
    dashboard_member
  else
    echo "Login member gagal!"
  fi
}

# Fungsi dashboard admin
dashboard_admin() {
  echo "Selama di dashboard admin"
  echo "1. Layanan"
  echo "2. Database"
  echo "3. API Key"
  echo "4. Table Layanan"
  echo "5. Isi Saldo Member"
  echo "6. Order"
  echo "7. Lihat Order"
  read -p "Pilih menu: " menu
  case \$menu in
    1) layanan ;;
    2) database ;;
    3) api_key ;;
    4) table_layanan ;;
    5) isi_saldo_member ;;
    6) order ;;
    7) lihat_order ;;
    *) echo "Menu tidak tersedia!" ;;
  esac
}

# Fungsi dashboard member
dashboard_member() {
  echo "Selama di dashboard member"
  echo "1. Lihat Layanan"
  echo "2. Order"
  echo "3. Lihat Order"
  read -p "Pilih menu: " menu
  case \$menu in
    1) lihat_layanan ;;
    2) order_member ;;
    3) lihat_order_member ;;
    *) echo "Menu tidak tersedia!" ;;
  esac
}

# Fungsi isi saldo member
isi_saldo_member() {
  echo "Isi Saldo Member"
  echo "Masukkan ID Member:"
  read id_member
  echo "Masukkan jumlah saldo:"
  read jumlah_saldo
  sqlite3 \$DB_FILE "UPDATE member SET saldo = saldo + \$jumlah_saldo WHERE id = \$id_member"
  echo "Saldo member berhasil diupdate!"
}

# Fungsi layanan
layanan() {
  echo "Layanan"
  echo "1. Tambah Layanan"
  echo "2. Lihat Layanan"
  read -p "Pilih menu: " menu
  case \$menu in
    1) tambah_layanan ;;
    2) lihat_layanan ;;
    *) echo "Menu tidak tersedia!" ;;
  esac
}

# Fungsi database
database() {
  echo "Database"
  echo "1. Lihat Database"
  echo "2. Tambah Data"
  read -p "Pilih menu: " menu
  case \$menu in
    1) lihat_database ;;
    2) tambah_data ;;
    *) echo "Menu tidak tersedia!" ;;
  esac
}

# Fungsi API Key
api_key() {
  echo "API Key: \$API_KEY"
}

# Fungsi table layanan
table_layanan() {
  echo "Table Layanan"
  echo "ID Produk | Nama Layanan | Harga"
  sqlite3 \$DB_FILE "SELECT * FROM layanan"
}

# Fungsi order
order() {
  echo "Order"
  echo "Masukkan ID Produk:"
  read id_produk
  echo "Masukkan jumlah order:"
  read jumlah_order
  saldo=\$(sqlite3 \$DB_FILE "SELECT saldo FROM layanan WHERE id = \$id_produk")
  if [ \$saldo -ge \$jumlah_order ]; then
    sqlite3 \$DB_FILE "UPDATE layanan SET saldo = saldo - \$jumlah_order WHERE id = \$id_produk"
    sqlite3 \$DB_FILE "INSERT INTO order (id_produk, jumlah_order) VALUES (\$id_produk, \$jumlah_order)"
    echo "Order berhasil!"
  else
    echo "Saldo tidak cukup!"
  fi
}

# Fungsi order member
order_member() {
  echo "Order"
  echo "Masukkan ID Produk:"
  read id_produk
  echo "Masukkan jumlah order:"
  read jumlah_order
  saldo=\$(sqlite3 \$DB_FILE "SELECT saldo FROM member WHERE id = 1")
  harga=\$(sqlite3 \$DB_FILE "SELECT harga FROM layanan WHERE id = \$id_produk")
  if [ \$saldo -ge \$((\$harga * \$jumlah_order)) ]; then
    sqlite3 \$DB_FILE "UPDATE member SET saldo = saldo - \$((\$harga * \$jumlah_order)) WHERE id = 1"
    sqlite3 \$DB_FILE "INSERT INTO order (id_produk, jumlah_order) VALUES (\$id_produk, \$jumlah_order)"
    echo "Order berhasil!"
  else
    echo "Saldo tidak cukup!"
  fi
}

# Fungsi lihat order
lihat_order() {
  echo "Lihat Order"
  sqlite3 \$DB_FILE "SELECT * FROM order"
}

# Fungsi lihat order member
lihat_order_member() {
  echo "LiAlamat Order"
  sqlite3 \$DB_FILE "SELECT * FROM order WHERE id_member = 1"
}

# Fungsi tambah layanan
tambah_layanan() {
  echo "Tambah Layanan"
  echo "Masukkan nama layanan:"
  read nama_layanan
  echo "Masukkan harga:"
  read harga
  sqlite3 \$DB_FILE "INSERT INTO layanan (nama, harga, saldo) VALUES ('\$nama_layanan', \$harga, 0)"
  echo "Layanan berhasil ditambahkan!"
}

# Fungsi lihat layanan
lihat_layanan() {
  echo "Lihat Layanan"
  sqlite3 \$DB_FILE "SELECT * FROM layanan"
}

# Fungsi lihat database
lihat_database() {
  echo "Lihat Database"
  sqlite3 \$DB_FILE ".tables"
  sqlite3 \$DB_FILE ".schema layanan"
  sqlite3 \$DB_FILE ".schema order"
  sqlite3 \$DB_FILE ".schema member"
}

# Fungsi tambah data
tambah_data() {
  echo "Tambah Data"
  echo "Masukkan nama data:"
  read nama_data
  echo "Masukkan nilai data:"
  read nilai_data
  sqlite3 \$DB_FILE "INSERT INTO data (nama, nilai) VALUES ('\$nama_data', '\$nilai_data')"
  echo "Data berhasil ditambahkan!"
}

# Buat database jika belum ada
if [ ! -f \$DB_FILE ]; then
  sqlite3 \$DB_FILE "CREATE TABLE layanan (id INTEGER PRIMARY KEY, nama TEXT, harga REAL, saldo REAL)"
  sqlite3 \$DB_FILE "CREATE TABLE order (id INTEGER PRIMARY KEY, id_produk INTEGER, jumlah_order INTEGER, id_member INTEGER)"
  sqlite3 \$DB_FILE "CREATE TABLE member (id INTEGER PRIMARY KEY, username TEXT, password TEXT, saldo REAL)"
  sqlite3 \$DB_FILE "CREATE TABLE data (id INTEGER PRIMARY KEY, nama TEXT, nilai TEXT)"
  sqlite3 \$DB_FILE "INSERT INTO member (username, password, saldo) VALUES ('member', 'password', 1000)"
fi

login
EOF

# Buat file database.db
sqlite3 database.db "CREATE TABLE layanan (id INTEGER PRIMARY KEY, nama TEXT, harga REAL, saldo REAL)"
sqlite3 database.db "CREATE TABLE order (id INTEGER PRIMARY KEY, id_produk INTEGER, jumlah_order INTEGER, id_member INTEGER)"
sqlite3 database.db "CREATE TABLE member (id INTEGER PRIMARY KEY, username TEXT, password TEXT, saldo REAL)"
sqlite3 database.db "CREATE TABLE data (id INTEGER PRIMARY KEY, nama TEXT, nilai TEXT)"
sqlite3 database.db "INSERT INTO member (username, password, saldo) VALUES ('member', 'password', 1000)"

# Buat file panel.sh executable
chmod +x panel.sh
