<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - SmartRecruit</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/pricing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

            <!-- Pricing Content -->
            <div class="pricing-container">
                <div class="page-header">
                    <h2>Pilih Paket Langganan</h2>
                    <p>Tingkatkan pengalaman rekrutmen Anda dengan fitur premium</p>
                </div>

                <!-- Toggle Bulanan/Tahunan -->
                <div class="pricing-toggle">
                    <span class="toggle-label">Bulanan</span>
                    <label class="switch">
                        <input type="checkbox" id="billingToggle">
                        <span class="slider round"></span>
                    </label>
                    <span class="toggle-label">Tahunan <span class="save-badge">Hemat 20%</span></span>
                </div>

                <!-- Pricing Cards -->
                <div class="pricing-cards">
                    <!-- Free Plan -->
                    <div class="pricing-card">
                        <div class="card-header">
                            <h3>Free</h3>
                            <div class="price monthly">
                                <span class="amount">Rp 0</span>
                                <span class="period">/bulan</span>
                            </div>
                            <div class="price yearly" style="display: none;">
                                <span class="amount">Rp 0</span>
                                <span class="period">/tahun</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul>
                                <li><i class="fas fa-check"></i><span>3 Resume/bulan</span></li>
                                <li><i class="fas fa-check"></i><span>3 Cover Letter/bulan</span></li>
                                <li><i class="fas fa-check"></i><span>3 Interview Practice/bulan</span></li>
                                <li><i class="fas fa-check"></i><span>3 Resume Translation/bulan</span></li>
                                <li><i class="fas fa-times"></i><span>AI Suggestions</span></li>
                                <li><i class="fas fa-times"></i><span>Premium Templates</span></li>
                                <li><i class="fas fa-times"></i><span>Priority Support</span></li>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <button class="plan-button current">Paket Saat Ini</button>
                        </div>
                    </div>

                    <!-- Basic Plan -->
                    <div class="pricing-card">
                        <div class="card-header">
                            <h3>Basic</h3>
                            <div class="price monthly">
                                <span class="amount">Rp 50.000</span>
                                <span class="period">/bulan</span>
                            </div>
                            <div class="price yearly" style="display: none;">
                                <span class="amount">Rp 480.000</span>
                                <span class="period">/tahun</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul>
                                <li><i class="fas fa-check"></i><span>10 Resume/bulan</span></li>
                                <li><i class="fas fa-check"></i><span>10 Cover Letter/bulan</span></li>
                                <li><i class="fas fa-check"></i><span>10 Interview Practice/bulan</span></li>
                                <li><i class="fas fa-check"></i><span>10 Resume Translation/bulan</span></li>
                                <li><i class="fas fa-check"></i><span>AI Suggestions</span></li>
                                <li><i class="fas fa-check"></i><span>Premium Templates</span></li>
                                <li><i class="fas fa-times"></i><span>Priority Support</span></li>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <button class="plan-button upgrade">Pilih Paket</button>
                        </div>
                    </div>

                    <!-- Pro Plan -->
                    <div class="pricing-card featured">
                        <div class="featured-badge">Popular</div>
                        <div class="card-header">
                            <h3>Pro</h3>
                            <div class="price monthly">
                                <span class="amount">Rp 100.000</span>
                                <span class="period">/bulan</span>
                            </div>
                            <div class="price yearly" style="display: none;">
                                <span class="amount">Rp 960.000</span>
                                <span class="period">/tahun</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul>
                                <li><i class="fas fa-check"></i><span>Unlimited Resume</span></li>
                                <li><i class="fas fa-check"></i><span>Unlimited Cover Letter</span></li>
                                <li><i class="fas fa-check"></i><span>Unlimited Interview Practice</span></li>
                                <li><i class="fas fa-check"></i><span>Unlimited Resume Translation</span></li>
                                <li><i class="fas fa-check"></i><span>AI Suggestions</span></li>
                                <li><i class="fas fa-check"></i><span>Premium Templates</span></li>
                                <li><i class="fas fa-check"></i><span>Priority Support</span></li>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <button class="plan-button upgrade">Pilih Paket</button>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="faq-section">
                    <h3>Pertanyaan Umum</h3>
                    <div class="faq-grid">
                        <div class="faq-item">
                            <h4>Bagaimana cara berlangganan?</h4>
                            <p>Pilih paket yang sesuai dengan kebutuhan Anda, klik tombol "Pilih Paket", dan ikuti petunjuk pembayaran yang tersedia.</p>
                        </div>
                        <div class="faq-item">
                            <h4>Apakah saya bisa membatalkan langganan?</h4>
                            <p>Ya, Anda dapat membatalkan langganan kapan saja. Pembayaran yang sudah dilakukan tidak dapat dikembalikan.</p>
                        </div>
                        <div class="faq-item">
                            <h4>Metode pembayaran apa yang tersedia?</h4>
                            <p>Kami menerima pembayaran melalui kartu kredit, transfer bank, dan e-wallet populer di Indonesia.</p>
                        </div>
                        <div class="faq-item">
                            <h4>Apakah ada trial period?</h4>
                            <p>Ya, Anda bisa mencoba fitur basic selama 7 hari pertama secara gratis.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        // Toggle Bulanan/Tahunan
        const billingToggle = document.getElementById('billingToggle');
        const monthlyPrices = document.querySelectorAll('.price.monthly');
        const yearlyPrices = document.querySelectorAll('.price.yearly');

        billingToggle.addEventListener('change', function() {
            monthlyPrices.forEach(price => {
                price.style.display = this.checked ? 'none' : 'block';
            });
            yearlyPrices.forEach(price => {
                price.style.display = this.checked ? 'block' : 'none';
            });
        });

        // Handle logout
        document.getElementById('logout-btn').addEventListener('click', async function(e) {
            e.preventDefault();

            try {
                const token = localStorage.getItem('token');
                const response = await fetch(`api/logout.php?token=${token}`);
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
    </script>
</body>

</html>