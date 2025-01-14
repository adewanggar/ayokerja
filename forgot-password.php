<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SmartRecruit</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <a href="index.html" class="auth-logo">SmartRecruit</a>
            <h1>Lupa Password?</h1>
            <p class="auth-subtitle">Masukkan email Anda untuk mereset password</p>
            
            <form class="auth-form" id="forgot-password-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
                </div>
                <button type="submit" class="auth-button">Kirim Link Reset Password</button>
            </form>

            <div class="auth-footer">
                <p>Ingat password Anda? <a href="login.html">Masuk sekarang</a></p>
            </div>

            <!-- Success Message (Hidden by default) -->
            <div class="success-message" style="display: none;">
                <i class="fas fa-check-circle"></i>
                <h3>Email Terkirim!</h3>
                <p>Kami telah mengirimkan link reset password ke email Anda. Silakan cek inbox atau folder spam Anda.</p>
                <button class="auth-button" onclick="window.location.href='login.html'">Kembali ke Login</button>
            </div>
        </div>
    </div>

    <script>
        // Handle form submission
        document.getElementById('forgot-password-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitButton = this.querySelector('.auth-button');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            
            // Simulate API call
            setTimeout(() => {
                // Hide form and show success message
                this.style.display = 'none';
                document.querySelector('.success-message').style.display = 'block';
                
                // Also hide the subtitle
                document.querySelector('.auth-subtitle').style.display = 'none';
            }, 2000);
        });
    </script>

    <style>
        .success-message {
            text-align: center;
            padding: 20px 0;
        }

        .success-message i {
            font-size: 48px;
            color: var(--accent);
            margin-bottom: 20px;
        }

        .success-message h3 {
            color: var(--primary);
            margin-bottom: 10px;
        }

        .success-message p {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .success-message .auth-button {
            background: var(--primary);
        }

        .success-message .auth-button:hover {
            background: var(--secondary);
        }
    </style>
</body>
</html> 