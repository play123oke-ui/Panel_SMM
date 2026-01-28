# Buat folder
mkdir -p panel
mkdir -p panel/css
mkdir -p panel/js
mkdir -p panel/img

# Buat file index.php
cat > panel/index.php <<EOF
<?php
  // Konfigurasi
  \$DB_FILE = "database.db";
  \$API_KEY = "YOUR_YOUTUBE_API_KEY";

  // Fungsi login
  function login() {
    // Tampilkan form login
    echo "<form action='index.php' method='post'>";
    echo "<input type='text' name='username' placeholder='Username'>";
    echo "<input type='password' name='password' placeholder='Password'>";
    echo "<button type='submit'>Login</button>";
    echo "</form>";
  }

  // Fungsi dashboard
  function dashboard() {
    // Tampilkan menu dashboard
    echo "<h1>Dashboard</h1>";
    echo "<ul>";
    echo "<li><a href='layanan.php'>Layanan</a></li>";
    echo "<li><a href='order.php'>Order</a></li>";
    echo "<li><a href='member.php'>Member</a></li>";
    echo "</ul>";
  }

  // Fungsi layanan
  function layanan() {
    // Tampilkan daftar layanan
    echo "<h1>Layanan</h1>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Nama Layanan</th><th>Harga</th></tr>";
    // Ambil data layanan dari database
    \$db = new PDO("sqlite:database.db");
    \$stmt = \$db->prepare("SELECT * FROM layanan");
    \$stmt->execute();
    while (\$row = \$stmt->fetch()) {
      echo "<tr><td>" . \$row['id'] . "</td><td>" . \$row['nama'] . "</td><td>" . \$row['harga'] . "</td></tr>";
    }
    echo "</table>";
  }

  // Fungsi order
  function order() {
    // Tampilkan form order
    echo "<h1>Order</h1>";
    echo "<form action='order.php' method='post'>";
    echo "<input type='text' name='id_produk' placeholder='ID Produk'>";
    echo "<input type='number' name='jumlah_order' placeholder='Jumlah Order'>";
    echo "<button type='submit'>Order</button>";
    echo "</form>";
  }

  // Fungsi member
  function member() {
    // Tampilkan daftar member
    echo "<h1>Member</h1>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Username</th><th>Saldo</th></tr>";
    // Ambil data member dari database
    \$db = new PDO("sqlite:database.db");
    \$stmt = \$db->prepare("SELECT * FROM member");
    \$stmt->execute();
    while (\$row = \$stmt->fetch()) {
      echo "<tr><td>" . \$row['id'] . "</td><td>" . \$row['username'] . "</td><td>" . \$row['saldo'] . "</td></tr>";
    }
    echo "</table>";
  }

  // Main program
  if (isset(\$_POST['username']) && isset(\$_POST['password'])) {
    // Proses login
    \$username = \$_POST['username'];
    \$password = \$_POST['password'];
    // Ambil data member dari database
    \$db = new PDO("sqlite:database.db");
    \$stmt = \$db->prepare("SELECT * FROM member WHERE username = ? AND password = ?");
    \$stmt->execute(array(\$username, \$password));
    if (\$stmt->fetch()) {
      // Login berhasil, tampilkan dashboard
      dashboard();
    } else {
      // Login gagal, tampilkan pesan error
      echo "Login gagal!";
    }
  } else {
    // Tampilkan form login
    login();
  }
?>
EOF

# Buat file layanan.php
cat > panel/layanan.php <<EOF
<?php
  // Konfigurasi
  \$DB_FILE = "database.db";
  \$API_KEY = "YOUR_YOUTUBE_API_KEY";

  // Fungsi layanan
  function layanan() {
    // Tampilkan daftar layanan
    echo "<h1>Layanan</h1>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Nama Layanan</th><th>Harga</th></tr>";
    // Ambil data layanan dari database
    \$db = new PDO("sqlite:database.db");
    \$stmt = \$db->prepare("SELECT * FROM layanan");
    \$stmt->execute();
    while (\$row = \$stmt->fetch()) {
      echo "<tr><td>" . \$row['id'] . "</td><td>" . \$row['nama'] . "</td><td>" . \$row['harga'] . "</td></tr>";
    }
    echo "</table>";
  }

  // Main program
  layanan();
?>
EOF

# Buat file order.php
cat > panel/order.php <<EOF
<?php
  // Konfigurasi
  \$DB_FILE = "database.db";
  \$API_KEY = "YOUR_YOUTUBE_API_KEY";

  // Fungsi order
  function order() {
    // Tampilkan form order
    echo "<h1>Order</h1>";
    echo "<form action='order.php' => 'order.php' method='post'>";
    echo "<input type='text' name='id_produk' placeholder='ID Produk'>";
    echo "<input type='number' name='jumlah_order' placeholder='Jumlah Order'>";
    echo "<button type='submit'>Order</button>";
    echo "</form>";
  }

  // Main program
  if (isset(\$_POST['id_produk']) && isset(\$_POST['jumlah_order'])) {
    // Proses order
    \$id_produk = \$_POST['id_produk'];
    \$jumlah_order = \$_POST['jumlah_order'];
    // Ambil data produk dari database
    \$db = new PDO("sqlite:database.db");
    \$stmt = \$db->prepare("SELECT * FROM layanan WHERE id = ?");
    \$stmt->execute(array(\$id_produk));
    if (\$row = \$stmt->fetch()) {
      // Produk ditemukan, proses order
      \$harga = \$row['harga'];
      \$saldo = \$row['saldo'];
      if (\$saldo >= \$jumlah_order) {
        // Saldo cukup, update saldo dan tambah order
        \$db->exec("UPDATE layanan SET saldo = saldo - \$jumlah_order WHERE id = \$id_produk");
        \$db->exec("INSERT INTO order (id_produk, jumlah_order) VALUES (\$id_produk, \$jumlah_order)");
        echo "Order berhasil!";
      } else {
        // Saldo tidak cukup, tampilkan pesan error
        echo "Saldo tidak cukup!";
      }
    } else {
      // Produk tidak ditemukan, tampilkan pesan error
      echo "Produk tidak ditemukan!";
    }
  } else {
    // Tampilkan form order
    order();
  }
?>
EOF

# Buat file member.php
cat > panel/member.php <<EOF
<?php
  // Konfigurasi
  \$DB_FILE = "database.db";
  \$API_KEY = "YOUR_YOUTUBE_API_KEY";

  // Fungsi member
  function member() {
    // Tampilkan daftar member
    echo "<h1>Member</h1>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Username</th><th>Saldo</th></tr>";
    // Ambil data member dari database
    \$db = new PDO("sqlite:database.db");
    \$stmt = \$db->prepare("SELECT * FROM member");
    \$stmt->execute();
    while (\$row = \$stmt->fetch()) {
      echo "<tr><td>" . \$row['id'] . "</td><td>" . \$row['username'] . "</td><td>" . \$row['saldo'] . "</td></tr>";
    }
    echo "</table>";
  }

  // Main program
  member();
?>
EOF
   # Script panel.css
<link rel="stylesheet" href="css/panel.css">
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
}

.container {
  max-width: 800px;
  margin: 40px auto;
  padding: 20px;
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

header {
  background-color: #333;
  color: #fff;
  padding: 20px;
  text-align: center;
}

header h1 {
  margin: 0;
}

nav {
  background-color: #444;
  padding: 10px;
  text-align: center;
}

nav ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

nav li {
  display: inline-block;
  margin-right: 20px;
}

nav a {
  color: #fff;
  text-decoration: none;
}

main {
  padding: 20px;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: left;
}

th {
  background-color: #f0f0f0;
}

form {
  padding: 20px;
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 10px;
}

label {
  display: block;
  margin-bottom: 10px;
}

input[type="text"], input[type="password"], input[type="number"] {
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  border: 1px solid #ccc;
}

button[type="submit"] {
  background-color: #333;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button[type="submit"]:hover {
  background-color: #444;
}

  <script src="js/panel.js"></script>
  
// Fungsi untuk menampilkan pesan sukses
function showSuccess(message) {
  const successMessage = document.createElement('div');
  successMessage.classList.add('success-message');
  successMessage.innerHTML = message;
  document.body.appendChild(successMessage);
  setTimeout(() => {
    successMessage.remove();
  }, 3000);
}

// Fungsi untuk menampilkan pesan error
function showError(message) {
  const errorMessage = document.createElement('div');
  errorMessage.classList.add('error-message');
  errorMessage.innerHTML = message;
  document.body.appendChild(errorMessage);
  setTimeout(() => {
    errorMessage.remove();
  }, 3000);
}

// Fungsi untuk mengirimkan form
function submitForm(form) {
  const formData = new FormData(form);
  fetch(form.action, {
    method: form.method,
    body: formData,
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showSuccess(data.message);
    } else {
      showError(data.message);
    }
  })
  .catch(error => {
    console.error(error);
  });
}

// Tambahkan event listener ke form
document.addEventListener('submit', (event) => {
  event.preventDefault();
  const form = event.target;
  submitForm(form);
});
  
.success-message {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 10px;
  background-color: #4CAF50;
  color: #fff;
  border-radius: 5px;
  z-index: 1000;
}

.error-message {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 10px;
  background-color: #f44336;
  color: #fff;
  border-radius: 5px;
  z-index: 1000;
}
