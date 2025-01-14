<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Cover Letter - SmartRecruit</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/cover-letter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="https://placehold.co/40x40/2563eb/ffffff?text=SR" alt="SmartRecruit Logo" class="sidebar-logo">
                <h1>SmartRecruit</h1>
            </div>

            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="resume-builder.php" class="nav-item">
                    <i class="fas fa-file-alt"></i>
                    <span>AI Resume Builder</span>
                </a>
                <a href="#" class="nav-item active">
                    <i class="fas fa-envelope"></i>
                    <span>AI Cover Letter</span>
                </a>
                <a href="interview.php" class="nav-item">
                    <i class="fas fa-comments"></i>
                    <span>AI Interview Practice</span>
                </a>
                <a href="translator.php" class="nav-item">
                    <i class="fas fa-language"></i>
                    <span>Resume Translator</span>
                </a>
                <a href="profile.php" class="nav-item">
                    <i class="fas fa-user"></i>
                    <span>Profil Saya</span>
                </a>
                <a href="settings.php" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="#" class="nav-item" id="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <nav class="top-nav">
                <button id="sidebar-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="user-menu">
                    <div id="subscription-indicator">
                        <!-- Akan diisi oleh JavaScript -->
                    </div>
                    <span class="notification">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </span>
                    <div class="user-info">
                        <img id="userAvatar" src="https://placehold.co/40x40/1f2937/ffffff?text=U" alt="User Avatar" class="avatar">
                        <span id="userName" class="username">Loading...</span>
                    </div>
                </div>
            </nav>

            <!-- Cover Letter Builder Content -->
            <div class="cover-letter-builder">
                <div class="page-header">
                    <h2>Cover Letter Builder</h2>
                    <p>Buat surat lamaran kerja profesional dengan bantuan AI</p>
                </div>

                <button class="my-letters-btn">
                    <i class="fas fa-envelope"></i>
                    Cover Letter Saya
                </button>

                <!-- Overlay untuk menampilkan daftar cover letter -->
                <div class="letters-overlay">
                    <div class="letters-modal">
                        <div class="letters-modal-header">
                            <h3>Cover Letter Saya</h3>
                            <button class="close-modal">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="letters-modal-body">
                            <div class="letters-grid">
                                <!-- Cover letters akan dimuat di sini -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cover-letter-container">
                    <!-- Form Section -->
                    <div class="cover-letter-form">
                        <div class="form-section">
                            <h3>Informasi Perusahaan</h3>
                            <div class="mb-3">
                                <label for="company" class="form-label">Nama Perusahaan</label>
                                <input type="text" class="form-control" id="company" placeholder="Contoh: Google">
                            </div>
                            <div class="mb-3">
                                <label for="position" class="form-label">Posisi yang Dilamar</label>
                                <input type="text" class="form-control" id="position" placeholder="Contoh: UI/UX Designer">
                            </div>
                            <div class="mb-3">
                                <label for="recipient" class="form-label">Penerima Surat (Opsional)</label>
                                <input type="text" class="form-control" id="recipient" placeholder="Contoh: HR Manager">
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Pengalaman & Kualifikasi</h3>
                            <div class="mb-3">
                                <label for="job-desc" class="form-label">Deskripsi Pekerjaan</label>
                                <textarea class="form-control" id="job-desc" rows="4" placeholder="Salin deskripsi pekerjaan dari lowongan"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="key-skills" class="form-label">Keahlian Utama</label>
                                <textarea class="form-control" id="key-skills" rows="3" placeholder="Masukkan keahlian yang relevan dengan posisi"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="achievements" class="form-label">Pencapaian Relevan</label>
                                <textarea class="form-control" id="achievements" rows="3" placeholder="Sebutkan pencapaian yang sesuai dengan posisi"></textarea>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Preferensi Surat</h3>
                            <div class="mb-3">
                                <label class="form-label">Gaya Penulisan</label>
                                <div class="writing-style-options">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="writingStyle" id="formal" checked>
                                        <label class="form-check-label" for="formal">
                                            Formal
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="writingStyle" id="semi-formal">
                                        <label class="form-check-label" for="semi-formal">
                                            Semi Formal
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="writingStyle" id="casual">
                                        <label class="form-check-label" for="casual">
                                            Casual
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="tone" class="form-label">Nada Penulisan</label>
                                <select class="form-select" id="tone">
                                    <option value="confident">Percaya Diri</option>
                                    <option value="enthusiastic">Antusias</option>
                                    <option value="professional">Profesional</option>
                                    <option value="friendly">Ramah</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="length" class="form-label">Panjang Surat</label>
                                <select class="form-select" id="length">
                                    <option value="short">Singkat (250 kata)</option>
                                    <option value="medium" selected>Sedang (350 kata)</option>
                                    <option value="long">Panjang (500 kata)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button class="btn btn-primary generate-btn">
                                <i class="fas fa-magic"></i>
                                Generate dengan AI
                            </button>
                            <button class="btn btn-outline-primary save-draft-btn">
                                <i class="fas fa-save"></i>
                                Simpan Draft
                            </button>
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="cover-letter-preview">
                        <div class="preview-header">
                            <h3>Preview Surat</h3>
                            <div class="preview-actions">
                                <button class="btn btn-sm btn-outline-secondary copy-text-btn">
                                    <i class="fas fa-copy"></i>
                                    Salin Teks
                                </button>
                            </div>
                        </div>
                        <div class="preview-content">
                            <div class="preview-placeholder">
                                <i class="fas fa-envelope"></i>
                                <p>Surat lamaran Anda akan muncul di sini setelah di-generate</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Suggestions -->
                <div class="ai-suggestions">
                    <h3>Saran AI</h3>
                    <div class="suggestion-list">
                        <div class="suggestion-item">
                            <i class="fas fa-lightbulb"></i>
                            <div class="suggestion-content">
                                <p>Tambahkan contoh spesifik dari pengalaman Anda yang relevan dengan posisi</p>
                                <button class="btn btn-sm btn-link">Terapkan</button>
                            </div>
                        </div>
                        <div class="suggestion-item">
                            <i class="fas fa-lightbulb"></i>
                            <div class="suggestion-content">
                                <p>Sesuaikan nada penulisan dengan budaya perusahaan</p>
                                <button class="btn btn-sm btn-link">Terapkan</button>
                            </div>
                        </div>
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

        // Handle logout
        document.getElementById('logout-btn').addEventListener('click', async function(e) {
            e.preventDefault();

            try {
                const token = localStorage.getItem('token');
                const response = await fetch(`api/logout.php?token=${token}`);
                const data = await response.json();

                if (data.success) {
                    // Hapus data user dari localStorage
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');

                    // Redirect ke halaman login
                    window.location.href = 'login.php';
                } else {
                    throw new Error(data.error || 'Terjadi kesalahan saat logout');
                }
            } catch (error) {
                alert(error.message);
            }
        });

        // Sidebar toggle code
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

        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                const currentActive = document.querySelector('.nav-item.active');
                if (currentActive) {
                    currentActive.classList.remove('active');
                }
                this.classList.add('active');

                if (window.innerWidth <= 1024) {
                    adminContainer.classList.remove('sidebar-collapsed');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                adminContainer.classList.remove('sidebar-collapsed');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    </script>
    <script src="js/cover-letter.js"></script>
</body>

</html>