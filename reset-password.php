<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SmartRecruit</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <a href="index.html" class="auth-logo">SmartRecruit</a>
            <h1>Reset Password</h1>
            <p class="auth-subtitle">Buat password baru untuk akun Anda</p>
            
            <form class="auth-form" id="reset-password-form">
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <div class="password-input">
                        <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required>
                        <button type="button" class="toggle-password">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Konfirmasi Password Baru</label>
                    <div class="password-input">
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Masukkan ulang password" required>
                        <button type="button" class="toggle-password">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="password-requirements">
                    <p>Password harus memenuhi kriteria berikut:</p>
                    <ul>
                        <li id="length"><i class="fas fa-circle"></i> Minimal 8 karakter</li>
                        <li id="uppercase"><i class="fas fa-circle"></i> Minimal 1 huruf besar</li>
                        <li id="lowercase"><i class="fas fa-circle"></i> Minimal 1 huruf kecil</li>
                        <li id="number"><i class="fas fa-circle"></i> Minimal 1 angka</li>
                        <li id="special"><i class="fas fa-circle"></i> Minimal 1 karakter spesial (!@#$%^&*)</li>
                    </ul>
                </div>
                <button type="submit" class="auth-button">Reset Password</button>
            </form>

            <!-- Success Message (Hidden by default) -->
            <div class="success-message" style="display: none;">
                <i class="fas fa-check-circle"></i>
                <h3>Password Berhasil Direset!</h3>
                <p>Password Anda telah berhasil diperbarui. Silakan login dengan password baru Anda.</p>
                <button class="auth-button" onclick="window.location.href='login.html'">Login Sekarang</button>
            </div>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Password Validation
        const password = document.getElementById('password');
        const requirements = {
            length: /.{8,}/,
            uppercase: /[A-Z]/,
            lowercase: /[a-z]/,
            number: /[0-9]/,
            special: /[!@#$%^&*]/
        };

        password.addEventListener('input', function() {
            const value = this.value;
            
            // Check each requirement
            for (const [key, regex] of Object.entries(requirements)) {
                const li = document.getElementById(key);
                if (regex.test(value)) {
                    li.classList.add('valid');
                    li.querySelector('i').classList.remove('fa-circle');
                    li.querySelector('i').classList.add('fa-check-circle');
                } else {
                    li.classList.remove('valid');
                    li.querySelector('i').classList.remove('fa-check-circle');
                    li.querySelector('i').classList.add('fa-circle');
                }
            }
        });

        // Form Submission
        document.getElementById('reset-password-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            // Validate password requirements
            const isValid = Object.values(requirements).every(regex => regex.test(password));
            
            if (!isValid) {
                alert('Password tidak memenuhi semua persyaratan');
                return;
            }
            
            if (password !== confirmPassword) {
                alert('Password tidak cocok');
                return;
            }
            
            // Show loading state
            const submitButton = this.querySelector('.auth-button');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            
            // Simulate API call
            setTimeout(() => {
                // Hide form and show success message
                this.style.display = 'none';
                document.querySelector('.success-message').style.display = 'block';
                document.querySelector('.auth-subtitle').style.display = 'none';
            }, 2000);
        });
    </script>

    <style>
        .password-requirements {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .password-requirements p {
            color: var(--text);
            font-size: 14px;
            margin-bottom: 10px;
        }

        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .password-requirements li {
            color: #666;
            font-size: 13px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .password-requirements li i {
            font-size: 12px;
            color: #ccc;
        }

        .password-requirements li.valid {
            color: var(--accent);
        }

        .password-requirements li.valid i {
            color: var(--accent);
        }

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