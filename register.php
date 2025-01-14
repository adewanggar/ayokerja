<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SmartRecruit</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="auth-container">
        <div class="auth-box">
            <a href="index.php" class="auth-logo">SmartRecruit</a>
            <h1>Buat Akun Baru</h1>
            <p class="auth-subtitle">Mulai perjalanan karir Anda bersama SmartRecruit</p>

            <form class="auth-form" method="POST" action="api/register.php">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap Anda" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-input">
                        <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required>
                        <button type="button" class="toggle-password">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Konfirmasi Password</label>
                    <div class="password-input">
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Masukkan ulang password" required>
                        <button type="button" class="toggle-password">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="checkbox-container">
                        <input type="checkbox" name="terms" required>
                        <span class="checkmark"></span>
                        Saya setuju dengan <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi</a>
                    </label>
                </div>
                <button type="submit" class="auth-button">Daftar</button>
            </form>

            <div class="divider">
                <span>atau daftar dengan</span>
            </div>

            <button class="google-button">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" alt="Google Logo">
                Lanjutkan dengan Google
            </button>

            <p class="auth-footer">
                Sudah punya akun? <a href="login.php">Masuk sekarang</a>
            </p>
        </div>
    </div>

    <script src="js/auth.js"></script>
</body>

</html>