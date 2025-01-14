<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - SmartRecruit</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Cek apakah user sudah login -->
    <script>
        // Ambil data user dari localStorage
        const userData = localStorage.getItem('user');
        const token = localStorage.getItem('token');

        if (!userData || !token) {
            window.location.href = 'login.php';
        }
    </script>

    <div class="admin-container">
        <!-- Overlay for mobile menu -->
        <div class="mobile-overlay"></div>

        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Include Top Navigation -->
            <?php include 'includes/topnav.php'; ?>

            <!-- Profile Content -->
            <div class="profile-content">
                <div class="page-header">
                    <h2>Profil Saya</h2>
                    <p>Kelola informasi profil dan preferensi akun Anda.</p>
                </div>

                <!-- Profile Overview -->
                <div class="profile-overview">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <img src="https://placehold.co/120x120/1f2937/ffffff?text=U" alt="Profile Picture">
                            <button class="btn btn-sm btn-light change-avatar" disabled>
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                        <div class="profile-info">
                            <h3>Loading...</h3>
                            <p>Loading...</p>
                            <div class="profile-stats">
                                <div class="stat-item">
                                    <i class="fas fa-file-alt"></i>
                                    <span>0 CV</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>0 Cover Letter</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-comments"></i>
                                    <span>0 Interview</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Form -->
                <div class="profile-form">
                    <div class="form-section">
                        <h3>Informasi Pribadi</h3>
                        <form>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="Masukkan email" readonly>
                                    <small class="text-muted">Email tidak dapat diubah</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control" disabled placeholder="Fitur akan datang">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Posisi Saat Ini</label>
                                    <input type="text" name="current_position" class="form-control" placeholder="Contoh: UI/UX Designer">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Perusahaan</label>
                                    <input type="text" name="company" class="form-control" placeholder="Masukkan nama perusahaan">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label class="form-label">Bio</label>
                                    <textarea name="bio" class="form-control" rows="4" placeholder="Ceritakan sedikit tentang diri Anda"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="form-section">
                        <h3>Preferensi Akun</h3>
                        <form>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Bahasa</label>
                                    <select name="language" class="form-select">
                                        <option value="id">Bahasa Indonesia</option>
                                        <option value="en">English</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Zona Waktu</label>
                                    <select class="form-select" disabled>
                                        <option value="asia/jakarta" selected>Asia/Jakarta (GMT+7)</option>
                                        <option value="asia/makassar">Asia/Makassar (GMT+8)</option>
                                        <option value="asia/jayapura">Asia/Jayapura (GMT+9)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label class="form-label">Notifikasi</label>
                                    <div class="notification-preferences">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="email-notif">
                                            <label class="form-check-label" for="email-notif">
                                                Notifikasi Email
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="browser-notif">
                                            <label class="form-check-label" for="browser-notif">
                                                Notifikasi Browser
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="form-section">
                        <h3>Keamanan</h3>
                        <form>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Password Lama</label>
                                    <div class="password-input">
                                        <input type="password" class="form-control" placeholder="Masukkan password lama" disabled>
                                        <button type="button" class="btn btn-link toggle-password" disabled>
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Password Baru</label>
                                    <div class="password-input">
                                        <input type="password" class="form-control" placeholder="Masukkan password baru" disabled>
                                        <button type="button" class="btn btn-link toggle-password" disabled>
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <div class="password-input">
                                        <input type="password" class="form-control" placeholder="Konfirmasi password baru" disabled>
                                        <button type="button" class="btn btn-link toggle-password" disabled>
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="password-requirements">
                                        <p class="text-muted">Fitur ganti password akan datang</p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-outline-secondary">Batal</button>
                        <button type="button" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update user info
        const user = JSON.parse(localStorage.getItem('user'));
        if (user) {
            // Update nama user
            document.getElementById('userName').textContent = user.name;

            // Update avatar dengan inisial jika tidak ada foto
            if (!user.profile_picture) {
                const initials = user.name.split(' ').map(n => n[0]).join('').toUpperCase();
                document.getElementById('userAvatar').src = `https://placehold.co/40x40/1f2937/ffffff?text=${initials}`;
            } else {
                document.getElementById('userAvatar').src = user.profile_picture;
            }

            // Update subscription indicator
            const subscriptionIndicator = document.getElementById('subscription-indicator');
            switch (user.subscription_type) {
                case 'pro':
                    subscriptionIndicator.innerHTML = `
                        <div class="subscription-badge pro">
                            <i class="fas fa-gem"></i> Pro
                        </div>
                    `;
                    break;
                case 'basic':
                    subscriptionIndicator.innerHTML = `
                        <div class="subscription-badge basic">
                            <i class="fas fa-star"></i> Basic
                        </div>
                    `;
                    break;
                default: // free
                    subscriptionIndicator.innerHTML = `
                        <a href="pricing.php" class="upgrade-btn">
                            <i class="fas fa-crown"></i> Upgrade
                        </a>
                    `;
            }
        }

        // Fungsi untuk memuat data profil
        async function loadProfile() {
            try {
                const user = JSON.parse(localStorage.getItem('user'));
                const response = await fetch(`api/get_user_stats.php?user_id=${user.id}`);
                const data = await response.json();

                if (data.success) {
                    const userData = data.user;
                    const stats = data.stats;

                    // Update form fields
                    document.querySelector('input[name="name"]').value = userData.name;
                    document.querySelector('input[name="email"]').value = userData.email;
                    document.querySelector('input[name="current_position"]').value = userData.current_position;
                    document.querySelector('input[name="company"]').value = userData.company;
                    document.querySelector('textarea[name="bio"]').value = userData.bio;

                    // Update semua tampilan nama dan info profil
                    const userNameElements = document.querySelectorAll('.username, .profile-info h3');
                    userNameElements.forEach(el => el.textContent = userData.name);

                    // Update posisi di profile overview
                    document.querySelector('.profile-info p').textContent = userData.current_position || 'Belum diatur';

                    // Update avatar di semua lokasi
                    const avatarElements = document.querySelectorAll('.avatar, .profile-avatar img');
                    const avatarText = userData.name ? userData.name.split(' ')[0][0].toUpperCase() : 'U';
                    avatarElements.forEach(el => {
                        if (!userData.profile_picture) {
                            el.src = `https://placehold.co/${el.width}x${el.height}/1f2937/ffffff?text=${avatarText}`;
                        } else {
                            el.src = userData.profile_picture;
                        }
                    });

                    // Update stats
                    document.querySelector('.stat-item:nth-child(1) span').textContent = `${stats.resume_count || 0} CV`;
                    document.querySelector('.stat-item:nth-child(2) span').textContent = `${stats.cover_letter_count || 0} Cover Letter`;
                    document.querySelector('.stat-item:nth-child(3) span').textContent = `${stats.interview_count || 0} Interview`;

                    // Update settings
                    document.querySelector('select[name="language"]').value = userData.language || 'id';
                    document.getElementById('email-notif').checked = userData.email_notifications === 1;
                    document.getElementById('browser-notif').checked = userData.browser_notifications === 1;

                    // Update subscription indicator
                    const subscriptionIndicator = document.getElementById('subscription-indicator');
                    switch (userData.subscription_type) {
                        case 'pro':
                            subscriptionIndicator.innerHTML = `
                                <div class="subscription-badge pro">
                                    <i class="fas fa-gem"></i> Pro
                                </div>
                            `;
                            break;
                        case 'basic':
                            subscriptionIndicator.innerHTML = `
                                <div class="subscription-badge basic">
                                    <i class="fas fa-star"></i> Basic
                                </div>
                            `;
                            break;
                        default: // free
                            subscriptionIndicator.innerHTML = `
                                <a href="pricing.php" class="upgrade-btn">
                                    <i class="fas fa-crown"></i> Upgrade
                                </a>
                            `;
                    }
                }
            } catch (error) {
                console.error('Error loading profile:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal memuat data profil'
                });
            }
        }

        // Handle form submission
        document.querySelector('.btn-primary').addEventListener('click', async function(e) {
            e.preventDefault();

            // Tampilkan konfirmasi terlebih dahulu
            const result = await Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menyimpan perubahan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });

            // Jika user membatalkan
            if (!result.isConfirmed) {
                return;
            }

            // Tampilkan loading state
            Swal.fire({
                title: 'Menyimpan...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const user = JSON.parse(localStorage.getItem('user'));
                const formData = {
                    name: document.querySelector('input[name="name"]').value,
                    current_position: document.querySelector('input[name="current_position"]').value,
                    company: document.querySelector('input[name="company"]').value,
                    bio: document.querySelector('textarea[name="bio"]').value,
                    language: document.querySelector('select[name="language"]').value,
                    email_notifications: document.getElementById('email-notif').checked ? 1 : 0,
                    browser_notifications: document.getElementById('browser-notif').checked ? 1 : 0
                };

                const response = await fetch(`api/get_user_stats.php?user_id=${user.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    // Update localStorage (hanya nama, email tetap sama)
                    const userData = JSON.parse(localStorage.getItem('user'));
                    userData.name = formData.name;
                    localStorage.setItem('user', JSON.stringify(userData));

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 1500
                    }).then(() => {
                        // Reload halaman untuk memperbarui data
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Gagal memperbarui profil');
                }
            } catch (error) {
                console.error('Error saving profile:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Gagal menyimpan perubahan'
                });
            }
        });

        // Load profile when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadProfile();
        });

        // Mobile menu functionality
        const adminContainer = document.querySelector('.admin-container');
        const overlay = document.querySelector('.mobile-overlay');
        const sidebarToggle = document.getElementById('sidebar-toggle');

        sidebarToggle.addEventListener('click', function() {
            adminContainer.classList.toggle('sidebar-collapsed');
            overlay.classList.toggle('active');
            document.body.style.overflow = adminContainer.classList.contains('sidebar-collapsed') ? 'hidden' : '';
        });

        overlay.addEventListener('click', function() {
            adminContainer.classList.remove('sidebar-collapsed');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });

        // Password visibility toggle
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
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
    </script>
</body>

</html>