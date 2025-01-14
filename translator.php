<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Translator - SmartRecruit</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/translator.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <script>
        // Inisialisasi PDF.js
        pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
    </script>

    <!-- Mammoth.js untuk DOC/DOCX -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"></script>

    <!-- html2pdf untuk konversi ke PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.11.0/css/flag-icons.min.css" rel="stylesheet">

    <style>
        .preview-content {
            display: flex;
            gap: 2rem;
            margin: 1rem 0;
        }

        .original-text,
        .translated-text {
            flex: 1;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .text-content {
            min-height: 500px;
            white-space: pre-wrap;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 1rem 0;
        }

        .text-content.pdf-view {
            background: white;
            padding: 40px;
            margin: 20px auto;
            max-width: 210mm;
            min-height: 297mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Upload Box Styles */
        .upload-box {
            border: 2px dashed #e2e8f0;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            background: #f8fafc;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .upload-box:hover {
            border-color: #2563eb;
            background: #f1f5f9;
        }

        .upload-box.drag-over {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .upload-box i.fa-cloud-upload-alt {
            font-size: 3rem;
            color: #2563eb;
            margin-bottom: 1rem;
        }

        .upload-box p {
            color: #64748b;
            margin: 0.5rem 0;
        }

        .upload-box label.btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #2563eb;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 1rem;
            font-size: 0.95rem;
        }

        .upload-box label.btn:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .upload-box label.btn i {
            font-size: 1.1rem;
        }

        .file-info {
            margin-top: 1rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        .file-info.selected {
            color: #2563eb;
            font-weight: 500;
        }

        /* Language Switch Styles */
        .language-switch .switch-btn {
            width: 40px;
            height: 40px;
            padding: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border-color: #2563eb;
            color: #2563eb;
        }

        .language-switch .switch-btn:hover {
            background: #2563eb;
            color: white;
        }

        .language-switch .switch-btn i {
            transition: transform 0.3s ease;
        }

        @media print {
            .text-content.pdf-view {
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }

        /* Preview Container Styles */
        .preview-container {
            background: #f8fafc;
            border-radius: 12px;
            padding: 2rem;
            margin: 2rem 0;
        }

        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .preview-header h3 {
            margin: 0;
            font-size: 1.5rem;
            color: #1f2937;
        }

        .preview-actions {
            display: flex;
            gap: 1rem;
        }

        /* Quality Check Styles */
        .quality-check {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .quality-metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 1rem;
        }

        .metric {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .metric .label {
            font-size: 0.875rem;
            color: #4b5563;
        }

        .metric .progress {
            height: 6px;
            background: #e5e7eb;
            border-radius: 999px;
            overflow: hidden;
        }

        .metric .progress-bar {
            height: 100%;
            background: #2563eb;
            border-radius: 999px;
            transition: width 0.3s ease;
        }

        .metric .value {
            font-size: 0.875rem;
            color: #2563eb;
            font-weight: 500;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .preview-content {
                flex-direction: column;
                gap: 1.5rem;
            }

            .original-text,
            .translated-text {
                width: 100%;
                padding: 1.5rem;
            }

            .text-content {
                min-height: 300px;
            }

            .quality-metrics {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .preview-container {
                padding: 1.5rem;
                margin: 1.5rem 0;
            }

            .preview-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .preview-actions {
                width: 100%;
            }

            .preview-actions .btn {
                flex: 1;
            }
        }

        .history-section {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .history-list {
            margin-top: 15px;
        }

        .history-item {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .history-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .history-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .translation-details {
            display: flex;
            gap: 15px;
            align-items: center;
            color: #6c757d;
        }

        .file-name {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #495057;
            font-weight: 500;
            background: #e9ecef;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .file-name i {
            color: #6c757d;
        }

        .language-pair {
            background: #e9ecef;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .language-pair .flag-icon {
            width: 1.5em;
            height: 1em;
            border-radius: 2px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            display: inline-block;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        .language-pair .fa-arrow-right {
            color: #6c757d;
            font-size: 0.8em;
            margin: 0 4px;
        }

        .translation-content {
            margin-top: 10px;
            padding: 10px;
            background: #fff;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }

        .translation-content h6 {
            color: #495057;
            margin-bottom: 8px;
        }

        .translation-content p {
            margin: 0;
            color: #212529;
            line-height: 1.5;
        }

        .history-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .history-actions button {
            padding: 6px 12px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .history-actions button:hover {
            transform: translateY(-1px);
        }

        .quality-badge {
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85em;
            color: #495057;
        }

        .copy-btn {
            min-width: 80px;
        }

        .copy-btn i {
            margin-right: 4px;
        }

        /* Loading spinner styles */
        .spinner-border {
            width: 2rem;
            height: 2rem;
            color: #0d6efd;
        }

        /* Alert styles */
        .alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .alert-info {
            background-color: #cff4fc;
            border-color: #b6effb;
            color: #055160;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c2c7;
            color: #842029;
        }
    </style>
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
                <a href="cover-letter.php" class="nav-item">
                    <i class="fas fa-envelope"></i>
                    <span>AI Cover Letter</span>
                </a>
                <a href="interview.php" class="nav-item">
                    <i class="fas fa-comments"></i>
                    <span>AI Interview Practice</span>
                </a>
                <a href="#" class="nav-item active">
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

            <!-- Resume Translator Content -->
            <div class="translator-content">
                <div class="page-header">
                    <h2>Resume Translator</h2>
                    <p>Terjemahkan CV Anda ke berbagai bahasa dengan tetap mempertahankan format profesional.</p>
                </div>

                <!-- Translation Form -->
                <div class="translation-container">
                    <div class="upload-section">
                        <h3>Upload CV</h3>
                        <div class="upload-box" id="dropZone">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drag & drop file CV Anda di sini atau</p>
                            <label for="cv-upload" class="btn">
                                <i class="fas fa-file-upload"></i>
                                Pilih File
                            </label>
                            <input type="file" id="cv-upload" hidden accept=".pdf,.doc,.docx">
                            <p class="file-info">Format yang didukung: PDF, DOC, DOCX (Max. 5MB)</p>
                        </div>
                    </div>

                    <div class="language-section">
                        <div class="source-language">
                            <h3>Bahasa Asal</h3>
                            <select class="form-select">
                                <option value="id">Bahasa Indonesia (Indonesian)</option>
                                <option value="en">English (Inggris)</option>
                                <option value="ja">日本語 (Japanese)</option>
                                <option value="ko">한국어 (Korean)</option>
                                <option value="zh-CN">中文简体 (Chinese Simplified)</option>
                                <option value="zh-TW">中文繁體 (Chinese Traditional)</option>
                                <option value="ar">العربية (Arabic)</option>
                                <option value="hi">हिन्दी (Hindi)</option>
                                <option value="bn">বাংলা (Bengali)</option>
                                <option value="ur">اردو (Urdu)</option>
                                <option value="fa">فارسی (Persian)</option>
                                <option value="th">ไทย (Thai)</option>
                                <option value="vi">Tiếng Việt (Vietnamese)</option>
                                <option value="ms">Bahasa Melayu (Malay)</option>
                                <option value="tl">Tagalog (Filipino)</option>
                                <option value="my">မြန်မာစာ (Burmese)</option>
                                <option value="km">ខ្មែរ (Khmer)</option>
                                <option value="ru">Русский (Russian)</option>
                                <option value="fr">Français (French)</option>
                                <option value="de">Deutsch (German)</option>
                                <option value="es">Español (Spanish)</option>
                                <option value="pt">Português (Portuguese)</option>
                                <option value="it">Italiano (Italian)</option>
                                <option value="nl">Nederlands (Dutch)</option>
                                <option value="tr">Türkçe (Turkish)</option>
                            </select>
                        </div>

                        <div class="language-switch">
                            <button class="btn btn-outline-primary switch-btn">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </div>

                        <div class="target-language">
                            <h3>Bahasa Tujuan</h3>
                            <select class="form-select">
                                <option value="en">English (Inggris)</option>
                                <option value="id">Bahasa Indonesia (Indonesian)</option>
                                <option value="ja">日本語 (Japanese)</option>
                                <option value="ko">한국어 (Korean)</option>
                                <option value="zh-CN">中文简体 (Chinese Simplified)</option>
                                <option value="zh-TW">中文繁體 (Chinese Traditional)</option>
                                <option value="ar">العربية (Arabic)</option>
                                <option value="hi">हिन्दी (Hindi)</option>
                                <option value="bn">বাংলা (Bengali)</option>
                                <option value="ur">اردو (Urdu)</option>
                                <option value="fa">فارسی (Persian)</option>
                                <option value="th">ไทย (Thai)</option>
                                <option value="vi">Tiếng Việt (Vietnamese)</option>
                                <option value="ms">Bahasa Melayu (Malay)</option>
                                <option value="tl">Tagalog (Filipino)</option>
                                <option value="my">မြန်မာစာ (Burmese)</option>
                                <option value="km">ខ្មែរ (Khmer)</option>
                                <option value="ru">Русский (Russian)</option>
                                <option value="fr">Français (French)</option>
                                <option value="de">Deutsch (German)</option>
                                <option value="es">Español (Spanish)</option>
                                <option value="pt">Português (Portuguese)</option>
                                <option value="it">Italiano (Italian)</option>
                                <option value="nl">Nederlands (Dutch)</option>
                                <option value="tr">Türkçe (Turkish)</option>
                            </select>
                        </div>
                    </div>

                    <div class="translation-options">
                        <h3>Opsi Terjemahan</h3>
                        <div class="options-grid">
                            <div class="option-item">
                                <input type="checkbox" class="form-check-input" id="formal-tone" checked>
                                <label class="form-check-label" for="formal-tone">
                                    <i class="fas fa-user-tie"></i>
                                    Nada Formal
                                </label>
                            </div>
                            <div class="option-item">
                                <input type="checkbox" class="form-check-input" id="preserve-format" checked>
                                <label class="form-check-label" for="preserve-format">
                                    <i class="fas fa-file-alt"></i>
                                    Pertahankan Format
                                </label>
                            </div>
                            <div class="option-item">
                                <input type="checkbox" class="form-check-input" id="industry-terms">
                                <label class="form-check-label" for="industry-terms">
                                    <i class="fas fa-industry"></i>
                                    Istilah Industri
                                </label>
                            </div>
                            <div class="option-item">
                                <input type="checkbox" class="form-check-input" id="review-needed">
                                <label class="form-check-label" for="review-needed">
                                    <i class="fas fa-check-double"></i>
                                    Review Manual
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="translation-actions">
                        <button class="btn btn-primary translate-btn" disabled>
                            <i class="fas fa-language"></i>
                            Mulai Terjemahkan
                        </button>
                        <button class="btn btn-outline-secondary reset-btn" disabled>
                            <i class="fas fa-redo"></i>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Translation Preview -->
                <div class="preview-container" style="display: none;">
                    <div class="preview-header">
                        <h3>Preview Terjemahan</h3>
                        <div class="preview-actions">
                            <button class="btn btn-primary download-btn">
                                <i class="fas fa-download"></i>
                                Download
                            </button>
                        </div>
                    </div>

                    <div class="preview-content">
                        <div class="original-text">
                            <h4>Teks Asli</h4>
                            <div class="text-content">
                                <!-- Original text will be displayed here -->
                            </div>
                        </div>
                        <div class="translated-text">
                            <h4>Hasil Terjemahan</h4>
                            <div class="text-content">
                                <!-- Translated text will be displayed here -->
                            </div>
                        </div>
                    </div>

                    <div class="quality-check">
                        <h4>Pemeriksaan Kualitas</h4>
                        <div class="quality-metrics">
                            <div class="metric">
                                <span class="label">Akurasi Terjemahan</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 95%"></div>
                                </div>
                                <span class="value">95%</span>
                            </div>
                            <div class="metric">
                                <span class="label">Konsistensi Istilah</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 90%"></div>
                                </div>
                                <span class="value">90%</span>
                            </div>
                            <div class="metric">
                                <span class="label">Kesesuaian Format</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="value">100%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Translation History -->
                <div class="history-section mt-4">
                    <h3>Riwayat Terjemahan</h3>
                    <div id="translation-history" class="history-list">
                        <!-- Riwayat terjemahan akan dimuat secara dinamis di sini -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/translator.js"></script>
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

        // Logout handler
        document.getElementById('logout-btn').addEventListener('click', async function(e) {
            e.preventDefault();

            try {
                const response = await fetch('api/logout.php', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });

                const data = await response.json();

                if (data.success) {
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    window.location.href = 'login.php';
                } else {
                    throw new Error(data.error || 'Terjadi kesalahan saat logout');
                }
            } catch (error) {
                alert(error.message);
            }
        });

        // File upload handling
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('cv-upload');
        const fileInfo = document.querySelector('.file-info');

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop zone when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropZone.addEventListener('drop', handleDrop, false);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight(e) {
            dropZone.classList.add('drag-over');
        }

        function unhighlight(e) {
            dropZone.classList.remove('drag-over');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        // Handle selected files
        fileInput.addEventListener('change', function(e) {
            handleFiles(this.files);
        });

        function handleFiles(files) {
            const file = files[0];
            if (validateFile(file)) {
                fileInfo.textContent = `File terpilih: ${file.name}`;
                fileInfo.classList.add('selected');
                document.querySelector('.translate-btn').disabled = false;
                document.querySelector('.reset-btn').disabled = false;
            }
        }

        // Click anywhere in dropzone to trigger file input
        dropZone.addEventListener('click', function(e) {
            // Jangan buka file picker jika yang diklik adalah input file
            if (e.target !== fileInput && !e.target.closest('label')) {
                fileInput.click();
            }
        });

        // Tambahkan event listener untuk label button
        const uploadLabel = dropZone.querySelector('label.btn');
        uploadLabel.addEventListener('click', function(e) {
            // Hentikan event bubbling agar tidak memicu event click dropZone
            e.stopPropagation();
        });

        // Reset button handler
        document.querySelector('.reset-btn').addEventListener('click', function() {
            // Reset file input
            fileInput.value = '';
            // Reset file info
            fileInfo.textContent = 'Format yang didukung: PDF, DOC, DOCX (Max. 5MB)';
            fileInfo.classList.remove('selected');
            // Disable buttons
            document.querySelector('.translate-btn').disabled = true;
            this.disabled = true;
            // Hide preview if visible
            document.querySelector('.preview-container').style.display = 'none';
        });

        // Language switch functionality
        const switchBtn = document.querySelector('.switch-btn');
        const sourceLanguage = document.querySelector('.source-language select');
        const targetLanguage = document.querySelector('.target-language select');

        switchBtn.addEventListener('click', function() {
            // Simpan nilai bahasa saat ini
            const sourceValue = sourceLanguage.value;
            const targetValue = targetLanguage.value;

            // Tukar nilai bahasa
            sourceLanguage.value = targetValue;
            targetLanguage.value = sourceValue;

            // Animasi rotasi ikon
            const icon = this.querySelector('i');
            icon.style.transform = 'rotate(180deg)';
            setTimeout(() => {
                icon.style.transform = '';
            }, 300);
        });
    </script>
</body>

</html>