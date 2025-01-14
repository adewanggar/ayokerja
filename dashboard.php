<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SmartRecruit</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

        <!-- Include Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Include Top Navigation -->
            <?php include 'includes/topnav.php'; ?>

            <!-- Dashboard Content -->
            <div class="dashboard">
                <h2>Selamat Datang, <span id="welcomeName">Loading...</span>!</h2>
                <p>Mulai tingkatkan peluang karir Anda dengan fitur AI kami.</p>

                <!-- Quick Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="fas fa-file-alt"></i>
                        <div class="stat-info">
                            <h3>Resume Dibuat</h3>
                            <p id="resumeCount">0</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-envelope"></i>
                        <div class="stat-info">
                            <h3>Cover Letter</h3>
                            <p id="coverLetterCount">0</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-comments"></i>
                        <div class="stat-info">
                            <h3>Interview Practice</h3>
                            <p id="interviewCount">0</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-language"></i>
                        <div class="stat-info">
                            <h3>Resume Diterjemahkan</h3>
                            <p id="translationCount">0</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="recent-activity">
                    <h3>Aktivitas Terbaru</h3>
                    <div class="activity-list" id="activityList">
                        <div class="activity-item">
                            <i class="fas fa-spinner fa-spin"></i>
                            <div class="activity-info">
                                <p>Memuat aktivitas...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h3>Aksi Cepat</h3>
                    <div class="action-buttons">
                        <button class="action-btn">
                            <i class="fas fa-plus"></i>
                            Buat Resume Baru
                        </button>
                        <button class="action-btn">
                            <i class="fas fa-edit"></i>
                            Buat Cover Letter
                        </button>
                        <button class="action-btn">
                            <i class="fas fa-play"></i>
                            Mulai Interview Practice
                        </button>
                        <button class="action-btn">
                            <i class="fas fa-language"></i>
                            Terjemahkan Resume
                        </button>
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
            document.getElementById('welcomeName').textContent = user.name;

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

            // Ambil statistik user
            fetchUserStats(user.id);
        }

        // Set menu dashboard sebagai aktif
        document.querySelector('a[href="dashboard.php"]').classList.add('active');

        // Fungsi untuk mengambil statistik user
        async function fetchUserStats(userId) {
            try {
                const response = await fetch(`api/get_user_stats.php?user_id=${userId}`);
                const data = await response.json();

                // Update statistik
                document.getElementById('resumeCount').textContent = data.stats?.resume_count || '0';
                document.getElementById('coverLetterCount').textContent = data.stats?.cover_letter_count || '0';
                document.getElementById('interviewCount').textContent = data.stats?.interview_count || '0';
                document.getElementById('translationCount').textContent = data.stats?.translation_count || '0';

                // Update aktivitas terbaru
                const activityList = document.getElementById('activityList');
                activityList.innerHTML = ''; // Bersihkan loading state

                if (!data.success) {
                    throw new Error(data.error || 'Gagal memuat data');
                }

                if (!Array.isArray(data.recent_activities) || data.recent_activities.length === 0) {
                    activityList.innerHTML = `
                        <div class="activity-item">
                            <i class="fas fa-info-circle"></i>
                            <div class="activity-info">
                                <p>Belum ada aktivitas</p>
                            </div>
                        </div>
                    `;
                    return;
                }

                data.recent_activities.forEach(activity => {
                    if (!activity) return; // Skip jika activity undefined

                    const timeAgo = getTimeAgo(new Date(activity.created_at));
                    let icon = 'fa-circle-info';

                    // Set icon berdasarkan tipe aktivitas
                    switch (activity.activity_type) {
                        case 'resume':
                            icon = 'fa-file-alt';
                            break;
                        case 'cover_letter':
                            icon = 'fa-envelope';
                            break;
                        case 'interview':
                            icon = 'fa-comments';
                            break;
                        case 'translation':
                            icon = 'fa-language';
                            break;
                    }

                    activityList.innerHTML += `
                        <div class="activity-item">
                            <i class="fas ${icon}"></i>
                            <div class="activity-info">
                                <p>${activity.details || 'Aktivitas tidak diketahui'}</p>
                                <span>${timeAgo}</span>
                            </div>
                        </div>
                    `;
                });
            } catch (error) {
                console.error('Error fetching user stats:', error);
                const activityList = document.getElementById('activityList');
                activityList.innerHTML = `
                    <div class="activity-item">
                        <i class="fas fa-exclamation-circle"></i>
                        <div class="activity-info">
                            <p>Terjadi kesalahan saat memuat aktivitas</p>
                        </div>
                    </div>
                `;
            }
        }

        // Fungsi untuk format waktu yang lalu
        function getTimeAgo(date) {
            const seconds = Math.floor((new Date() - date) / 1000);

            let interval = Math.floor(seconds / 31536000);
            if (interval > 1) return interval + ' tahun yang lalu';

            interval = Math.floor(seconds / 2592000);
            if (interval > 1) return interval + ' bulan yang lalu';

            interval = Math.floor(seconds / 86400);
            if (interval > 1) return interval + ' hari yang lalu';

            interval = Math.floor(seconds / 3600);
            if (interval > 1) return interval + ' jam yang lalu';

            interval = Math.floor(seconds / 60);
            if (interval > 1) return interval + ' menit yang lalu';

            return 'Baru saja';
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
</body>

</html>