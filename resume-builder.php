<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Resume Builder - SmartRecruit</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/resume-builder.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
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
                <a href="admin-panel.html" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="nav-item active">
                    <i class="fas fa-file-alt"></i>
                    <span>AI Resume Builder</span>
                </a>
                <a href="cover-letter.html" class="nav-item">
                    <i class="fas fa-envelope"></i>
                    <span>AI Cover Letter</span>
                </a>
                <a href="interview.html" class="nav-item">
                    <i class="fas fa-comments"></i>
                    <span>AI Interview Practice</span>
                </a>
                <a href="translator.html" class="nav-item">
                    <i class="fas fa-language"></i>
                    <span>Resume Translator</span>
                </a>
                <a href="profile.html" class="nav-item">
                    <i class="fas fa-user"></i>
                    <span>Profil Saya</span>
                </a>
                <a href="settings.html" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="logout.html" class="nav-item">
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
                    <span class="notification">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </span>
                    <div class="user-info">
                        <img src="https://placehold.co/40x40/1f2937/ffffff?text=JD" alt="User Avatar" class="avatar">
                        <span class="username">John Doe</span>
                    </div>
                </div>
            </nav>

            <!-- Resume Builder Content -->
            <div class="resume-builder">
                <div class="page-header">
                    <h2>AI Resume Builder</h2>
                    <p>Buat CV profesional dengan bantuan AI yang disesuaikan dengan posisi dan industri yang dituju.</p>
                </div>

                <div class="resume-container">
                    <!-- Form Section -->
                    <div class="resume-form">
                        <div class="form-section">
                            <h3>Informasi Dasar</h3>
                            <div class="mb-3">
                                <label for="position" class="form-label">Posisi yang Dilamar</label>
                                <input type="text" class="form-control" id="position" placeholder="Contoh: UI/UX Designer">
                            </div>
                            <div class="mb-3">
                                <label for="industry" class="form-label">Industri</label>
                                <input type="text" class="form-control" id="industry" placeholder="Contoh: Teknologi">
                            </div>
                            <div class="mb-3">
                                <label for="experience" class="form-label">Pengalaman Kerja (Tahun)</label>
                                <input type="number" class="form-control" id="experience" min="0" max="50">
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Keahlian & Pengalaman</h3>
                            <div class="mb-3">
                                <label for="skills" class="form-label">Keahlian Utama</label>
                                <textarea class="form-control" id="skills" rows="3" placeholder="Masukkan keahlian Anda, pisahkan dengan koma"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="experience-desc" class="form-label">Pengalaman Kerja</label>
                                <textarea class="form-control" id="experience-desc" rows="5" placeholder="Ceritakan pengalaman kerja Anda"></textarea>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Preferensi CV</h3>
                            <div class="mb-3">
                                <label class="form-label">Gaya Penulisan</label>
                                <div class="writing-style-options">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="writingStyle" id="professional" checked>
                                        <label class="form-check-label" for="professional">
                                            Profesional
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="writingStyle" id="creative">
                                        <label class="form-check-label" for="creative">
                                            Kreatif
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="writingStyle" id="technical">
                                        <label class="form-check-label" for="technical">
                                            Teknikal
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="template" class="form-label">Template CV</label>
                                <select class="form-select" id="template">
                                    <option value="modern">Modern</option>
                                    <option value="classic">Klasik</option>
                                    <option value="minimal">Minimalis</option>
                                    <option value="creative">Kreatif</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button class="btn btn-primary generate-btn">
                                <i class="fas fa-magic"></i>
                                Generate dengan AI
                            </button>
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-save"></i>
                                Simpan Draft
                            </button>
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="resume-preview">
                        <div class="preview-header">
                            <h3>Preview CV</h3>
                            <div class="preview-actions">
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i>
                                    Download PDF
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                    Edit Manual
                                </button>
                            </div>
                        </div>
                        <div class="preview-content">
                            <div class="preview-placeholder">
                                <i class="fas fa-file-alt"></i>
                                <p>CV Anda akan muncul di sini setelah di-generate</p>
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
                                <p>Tambahkan pencapaian kuantitatif untuk memperkuat pengalaman kerja Anda</p>
                                <button class="btn btn-sm btn-link">Terapkan</button>
                            </div>
                        </div>
                        <div class="suggestion-item">
                            <i class="fas fa-lightbulb"></i>
                            <div class="suggestion-content">
                                <p>Gunakan kata kerja aktif untuk mendeskripsikan tanggung jawab kerja</p>
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
        const adminContainer = document.querySelector('.admin-container');
        const overlay = document.querySelector('.mobile-overlay');
        const sidebarToggle = document.getElementById('sidebar-toggle');

        // Toggle Sidebar
        sidebarToggle.addEventListener('click', function() {
            adminContainer.classList.toggle('sidebar-collapsed');
            overlay.classList.toggle('active');
        });

        // Close menu when clicking overlay
        overlay.addEventListener('click', function() {
            adminContainer.classList.remove('sidebar-collapsed');
            overlay.classList.remove('active');
        });

        // Active Navigation
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelector('.nav-item.active').classList.remove('active');
                this.classList.add('active');
                // Close menu on mobile when clicking nav item
                if (window.innerWidth <= 1024) {
                    adminContainer.classList.remove('sidebar-collapsed');
                    overlay.classList.remove('active');
                }
            });
        });

        // Generate Resume
        document.querySelector('.generate-btn').addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';

            // Simulate API call
            setTimeout(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-magic"></i> Generate dengan AI';

                // Show success message
                alert('CV berhasil di-generate!');

                // Update preview (in real implementation, this would show the actual generated CV)
                document.querySelector('.preview-content').innerHTML = `
                    <div class="generated-cv">
                        <h4>John Doe</h4>
                        <p>UI/UX Designer</p>
                        <!-- More CV content would go here -->
                    </div>
                `;
            }, 2000);
        });
    </script>
</body>

</html>