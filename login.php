<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SmartRecruit</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    require_once 'includes/session.php';

    // Redirect ke dashboard jika sudah login
    if (checkUserSession()) {
        header('Location: dashboard.php');
        exit;
    }
    ?>

    <div class="auth-container">
        <div class="auth-box">
            <a href="dashboard.php" class="auth-logo">SmartRecruit</a>
            <h1>Masuk ke Akun Anda</h1>
            <p class="auth-subtitle">Selamat datang kembali! Silakan masuk untuk melanjutkan.</p>

            <form class="auth-form" id="loginForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-input">
                        <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required>
                        <button type="button" class="toggle-password">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group form-flex">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember">
                        <span class="checkmark"></span>
                        Ingat saya
                    </label>
                    <a href="forgot-password.php" class="forgot-password">Lupa password?</a>
                </div>
                <button type="submit" class="auth-button">Masuk</button>
            </form>

            <div class="divider">
                <span>atau masuk dengan</span>
            </div>

            <button class="google-button">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" alt="Google Logo">
                Lanjutkan dengan Google
            </button>

            <p class="auth-footer">
                Belum punya akun? <a href="register.php">Daftar sekarang</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.querySelector('#password');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            // Handle form submission
            const loginForm = document.getElementById('loginForm');
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const remember = document.querySelector('input[name="remember"]').checked;

                try {
                    const response = await fetch('api/login.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            email,
                            password,
                            remember
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Simpan token ke localStorage jika "Ingat saya" dicentang
                        if (remember) {
                            localStorage.setItem('token', data.token);
                        } else {
                            sessionStorage.setItem('token', data.token);
                        }

                        // Redirect ke dashboard
                        window.location.href = 'dashboard.php';
                    } else {
                        alert(data.error || 'Terjadi kesalahan saat login');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghubungi server');
                }
            });
        });
    </script>
</body>

</html>