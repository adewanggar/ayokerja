// Fungsi untuk mengecek apakah user masih bisa menggunakan fitur
async function checkFeatureLimit(featureType) {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) return false;

    try {
        const response = await fetch(`api/check_usage_limit.php?user_id=${user.id}&feature_type=${featureType}&subscription_type=${user.subscription_type}`);
        const data = await response.json();

        if (!data.success) {
            throw new Error(data.error || 'Terjadi kesalahan saat mengecek batasan fitur');
        }

        if (!data.can_use_feature) {
            // Tampilkan pesan upgrade jika sudah melebihi batas
            Swal.fire({
                title: 'Batas Penggunaan Tercapai',
                html: `
                    <p>Anda telah mencapai batas penggunaan ${featureType} untuk bulan ini.</p>
                    <p>Sisa penggunaan: ${data.remaining_usage} dari ${data.usage_limit}</p>
                    <p>Upgrade ke paket Basic atau Pro untuk mendapatkan lebih banyak penggunaan!</p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Upgrade Sekarang',
                cancelButtonText: 'Nanti Saja',
                confirmButtonColor: '#3b82f6'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'pricing.php';
                }
            });
        }

        return data.can_use_feature;

    } catch (error) {
        console.error('Error checking feature limit:', error);
        return false;
    }
}

// Fungsi untuk mencatat penggunaan fitur
async function logFeatureUsage(featureType, details = '') {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) return false;

    try {
        const formData = new FormData();
        formData.append('user_id', user.id);
        formData.append('feature_type', featureType);
        formData.append('details', details);

        const response = await fetch('api/log_activity.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        return data.success;

    } catch (error) {
        console.error('Error logging feature usage:', error);
        return false;
    }
}

// Fungsi untuk menangani penggunaan fitur
async function handleFeatureUsage(featureType, callback, details = '') {
    // Cek batasan penggunaan
    const canUseFeature = await checkFeatureLimit(featureType);
    
    if (canUseFeature) {
        // Catat penggunaan fitur
        const logged = await logFeatureUsage(featureType, details);
        
        if (logged) {
            // Jalankan callback jika berhasil
            callback();
        } else {
            Swal.fire({
                title: 'Terjadi Kesalahan',
                text: 'Gagal mencatat penggunaan fitur',
                icon: 'error'
            });
        }
    }
} 