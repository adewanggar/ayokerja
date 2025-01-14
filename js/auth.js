document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(button => {
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

    // Handle register form
    const registerForm = document.querySelector('.auth-form');
    if (registerForm && window.location.pathname.includes('register')) {
        registerForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
            const submitButton = this.querySelector('.auth-button');
            submitButton.disabled = true;
            submitButton.textContent = 'Mendaftar...';

            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                'confirm-password': document.getElementById('confirm-password').value
            };

            try {
                const response = await fetch('./api/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Terjadi kesalahan');
                }

                alert('Registrasi berhasil! Silakan login.');
                window.location.href = 'login.php';

            } catch (error) {
                alert(error.message);
                submitButton.disabled = false;
                submitButton.textContent = 'Daftar';
            }
        });
    }

    // Handle login form
    const loginForm = document.querySelector('.auth-form');
    if (loginForm && window.location.pathname.includes('login')) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitButton = this.querySelector('.auth-button');
            submitButton.disabled = true;
            submitButton.textContent = 'Masuk...';

            const formData = {
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            };

            try {
                const response = await fetch('./api/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Terjadi kesalahan');
                }

                // Simpan token ke localStorage
                localStorage.setItem('token', data.token);
                localStorage.setItem('user', JSON.stringify(data.user));

                // Redirect ke dashboard
                window.location.href = 'dashboard.php';

            } catch (error) {
                alert(error.message);
                submitButton.disabled = false;
                submitButton.textContent = 'Masuk';
            }
        });
    }
}); 