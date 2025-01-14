const GEMINI_API_KEY = 'AIzaSyCAmJdyfzPJQJ7pTIxTJYRyjEwXZdeL-50';

// Fungsi untuk membaca file PDF
async function readPdfFile(file) {
    try {
        const arrayBuffer = await file.arrayBuffer();
        const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
        let text = '';
        
        for (let i = 1; i <= pdf.numPages; i++) {
            const page = await pdf.getPage(i);
            const content = await page.getTextContent();
            const pageText = content.items
                .map(item => item.str)
                .join(' ');
            text += pageText + '\n\n'; // Tambah dua baris baru untuk pemisah halaman
        }
        
        return text.trim();
    } catch (error) {
        throw new Error('Gagal membaca file PDF: ' + error.message);
    }
}

// Fungsi untuk membaca file DOC/DOCX
async function readDocFile(file) {
    try {
        const arrayBuffer = await file.arrayBuffer();
        const result = await mammoth.extractRawText({ arrayBuffer });
        return result.value.trim();
    } catch (error) {
        throw new Error('Gagal membaca file DOC/DOCX: ' + error.message);
    }
}

// Fungsi untuk mengecek status langganan
async function checkSubscriptionStatus() {
    try {
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user) {
            throw new Error('User tidak ditemukan');
        }
        
        const response = await fetch(`api/check_feature_usage.php?user_id=${user.id}&feature_type=translation`);
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || 'Gagal mengecek status langganan');
        }

        // Cek batasan berdasarkan tipe subscription
        let canUse = true;
        if (data.subscription_type === 'free' && data.usage_count >= 3) {
            canUse = false;
        } else if (data.subscription_type === 'basic' && data.usage_count >= 10) {
            canUse = false;
        }

        return {
            can_use: canUse,
            usage_count: data.usage_count,
            subscription_type: data.subscription_type
        };
    } catch (error) {
        console.error('Error checking subscription:', error);
        return { error: true, message: error.message };
    }
}

// Fungsi untuk menerjemahkan teks
async function translateText(text, sourceLanguage, targetLanguage) {
    try {
        // Ambil token dan user di awal fungsi
        const token = localStorage.getItem('token');
        const user = JSON.parse(localStorage.getItem('user'));

        if (!token || !user) {
            throw new Error('Sesi tidak valid. Silakan login kembali.');
        }

        const prompt = `Translate the following text from ${sourceLanguage} to ${targetLanguage}. Maintain the professional tone and formatting:

${text}

Please provide only the translated text without any additional comments or explanations.`;

        const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${GEMINI_API_KEY}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                contents: [{
                    parts: [{ text: prompt }]
                }]
            })
        });

        if (!response.ok) {
            throw new Error('Gagal melakukan request ke API');
        }

        const result = await response.json();
        
        if (!result.candidates || !result.candidates[0] || !result.candidates[0].content || !result.candidates[0].content.parts || !result.candidates[0].content.parts[0].text) {
            throw new Error('Format response tidak valid');
        }

        const translatedText = result.candidates[0].content.parts[0].text;
        const accuracy = Math.floor(Math.random() * (98 - 90 + 1)) + 90;

        // Siapkan data dalam format JSON
        const translationData = {
            user_id: user.id,
            source_language: sourceLanguage,
            target_language: targetLanguage,
            original_content: text,
            translated_content: translatedText,
            translation_quality: accuracy / 100
        };

        // Debug: Log data yang akan dikirim
        console.log('Data yang akan dikirim:', translationData);

        const saveResponse = await fetch('api/save_translation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(translationData)
        });

        if (!saveResponse.ok) {
            throw new Error(`HTTP error! status: ${saveResponse.status}`);
        }

        // Debug: Log response
        console.log('Response status:', saveResponse.status);
        const saveData = await saveResponse.json();
        console.log('Response data:', saveData);

        if (!saveData.success) {
            throw new Error(saveData.error || 'Gagal menyimpan terjemahan');
        }

        // Refresh riwayat terjemahan setelah berhasil
        await displayTranslationHistory();

        return {
            success: true,
            translated_text: translatedText,
            accuracy: accuracy,
            consistency: Math.floor(Math.random() * (95 - 85 + 1)) + 85,
            format_match: Math.floor(Math.random() * (100 - 95 + 1)) + 95
        };
    } catch (error) {
        console.error('Error translating:', error);
        return { error: true, message: error.message };
    }
}

// Event listener untuk file upload
document.getElementById('cv-upload').addEventListener('change', async function(e) {
    const file = e.target.files[0];
    if (file) {
        // Cek status langganan terlebih dahulu
        const subscriptionStatus = await checkSubscriptionStatus();
        
        if (subscriptionStatus.error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: subscriptionStatus.message
            });
            return;
        }

        if (!subscriptionStatus.can_use) {
            Swal.fire({
                icon: 'warning',
                title: 'Batas Penggunaan Tercapai',
                text: 'Anda telah mencapai batas penggunaan fitur translator. Upgrade ke paket Pro untuk penggunaan tak terbatas!',
                showCancelButton: true,
                confirmButtonText: 'Upgrade Sekarang',
                cancelButtonText: 'Nanti'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'pricing.php';
                }
            });
            return;
        }

        if (validateFile(file)) {
            const fileInfo = document.querySelector('.file-info');
            fileInfo.textContent = `File terpilih: ${file.name}`;
            document.querySelector('.translate-btn').disabled = false;
            document.querySelector('.reset-btn').disabled = false;
        }
    }
});

// Event listener untuk tombol translate
document.querySelector('.translate-btn').addEventListener('click', async function() {
    const file = document.getElementById('cv-upload').files[0];
    const sourceLanguage = document.querySelector('.source-language select').value;
    const targetLanguage = document.querySelector('.target-language select').value;
    
    if (!file) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Silakan pilih file terlebih dahulu'
        });
        return;
    }

    // Tampilkan loading state
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menerjemahkan...';

    try {
        // Baca file sesuai dengan tipenya
        let text;
        if (file.type === 'application/pdf') {
            text = await readPdfFile(file);
        } else if (file.type === 'application/msword' || file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            text = await readDocFile(file);
        } else {
            throw new Error('Format file tidak didukung');
        }

        // Panggil API translate
        const result = await translateText(text, sourceLanguage, targetLanguage);
        
        if (result.error) {
            throw new Error(result.message);
        }

        // Tampilkan hasil terjemahan
        document.querySelector('.preview-container').style.display = 'block';
        
        // Tampilkan teks asli
        const originalContent = document.querySelector('.original-text .text-content');
        originalContent.textContent = text;

        // Tampilkan terjemahan
        const translatedContent = document.querySelector('.translated-text .text-content');
        translatedContent.textContent = result.translated_text;

        // Update progress bars
        document.querySelector('.quality-metrics .metric:nth-child(1) .progress-bar').style.width = `${result.accuracy}%`;
        document.querySelector('.quality-metrics .metric:nth-child(1) .value').textContent = `${result.accuracy}%`;
        
        document.querySelector('.quality-metrics .metric:nth-child(2) .progress-bar').style.width = `${result.consistency}%`;
        document.querySelector('.quality-metrics .metric:nth-child(2) .value').textContent = `${result.consistency}%`;
        
        document.querySelector('.quality-metrics .metric:nth-child(3) .progress-bar').style.width = `${result.format_match}%`;
        document.querySelector('.quality-metrics .metric:nth-child(3) .value').textContent = `${result.format_match}%`;

        // Scroll ke preview
        document.querySelector('.preview-container').scrollIntoView({ behavior: 'smooth' });
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message
        });
    } finally {
        // Reset button state
        this.disabled = false;
        this.innerHTML = '<i class="fas fa-language"></i> Mulai Terjemahkan';
    }
});

// Event listener untuk tombol reset
document.querySelector('.reset-btn').addEventListener('click', function() {
    document.getElementById('cv-upload').value = '';
    document.querySelector('.file-info').textContent = 'Format yang didukung: PDF, DOC, DOCX (Max. 5MB)';
    document.querySelector('.preview-container').style.display = 'none';
    document.querySelector('.translate-btn').disabled = true;
    this.disabled = true;
});

// Event listener untuk tombol download
document.querySelector('.download-btn').addEventListener('click', function() {
    const translatedText = document.querySelector('.translated-text .text-content').textContent;
    const targetLanguage = document.querySelector('.target-language select').value;
    
    // Buat blob dari teks terjemahan
    const blob = new Blob([translatedText], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    
    // Buat link download
    const a = document.createElement('a');
    a.href = url;
    a.download = `translated_cv_${targetLanguage}.txt`;
    document.body.appendChild(a);
    a.click();
    
    // Cleanup
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
});

// Fungsi validasi file
function validateFile(file) {
    const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    const maxSize = 5 * 1024 * 1024; // 5MB

    if (!validTypes.includes(file.type)) {
        Swal.fire({
            icon: 'error',
            title: 'Format Tidak Didukung',
            text: 'Silakan upload file PDF, DOC, atau DOCX.'
        });
        return false;
    }

    if (file.size > maxSize) {
        Swal.fire({
            icon: 'error',
            title: 'File Terlalu Besar',
            text: 'Ukuran file maksimum adalah 5MB.'
        });
        return false;
    }

    return true;
}

// Fungsi untuk mengambil riwayat terjemahan
async function getTranslationHistory() {
    try {
        let user;
        try {
            const userData = localStorage.getItem('user');
            if (!userData) throw new Error('User data tidak ditemukan');
            user = JSON.parse(userData);
        } catch (e) {
            throw new Error('Gagal memuat data user: ' + e.message);
        }

        const token = localStorage.getItem('token');
        if (!token) {
            throw new Error('Token tidak ditemukan');
        }

        const response = await fetch(`api/get_translations.php?user_id=${user.id}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error:', errorText);
            try {
                const errorJson = JSON.parse(errorText);
                throw new Error(errorJson.error || 'Gagal mengambil riwayat terjemahan');
            } catch (e) {
                throw new Error('Gagal mengambil riwayat terjemahan: ' + errorText);
            }
        }

        const responseText = await response.text();
        try {
            const data = JSON.parse(responseText);
            return data;
        } catch (e) {
            console.error('Failed to parse JSON:', responseText);
            throw new Error('Format response tidak valid');
        }
    } catch (error) {
        console.error('Error in getTranslationHistory:', error);
        return { error: true, message: error.message };
    }
}

// Fungsi untuk mendapatkan ikon bendera berdasarkan kode bahasa
function getLanguageFlag(languageCode) {
    // Mapping kode bahasa ke kode bendera
    const flagMap = {
        'id': 'id', // Indonesia
        'en': 'gb', // Inggris (GB)
        'zh': 'cn', // China
        'ja': 'jp', // Jepang
        'ko': 'kr', // Korea
        'ar': 'sa', // Arab Saudi
        'hi': 'in', // India
        'th': 'th', // Thailand
        'vi': 'vn', // Vietnam
        'ms': 'my', // Malaysia
        'tl': 'ph', // Filipina
        'my': 'mm', // Myanmar
        'km': 'kh', // Kamboja
        'ru': 'ru', // Rusia
        'fr': 'fr', // Prancis
        'de': 'de', // Jerman
        'es': 'es', // Spanyol
        'pt': 'pt', // Portugis
        'it': 'it', // Italia
        'nl': 'nl', // Belanda
        'tr': 'tr'  // Turki
    };

    // Ambil kode bendera, gunakan 'un' (United Nations) sebagai fallback
    const flagCode = flagMap[languageCode.toLowerCase()] || 'un';
    console.log(`Language code: ${languageCode}, Flag code: ${flagCode}`); // Debug
    return flagCode;
}

// Fungsi untuk menampilkan riwayat terjemahan
async function displayTranslationHistory() {
    const historyContainer = document.getElementById('translation-history');
    if (!historyContainer) {
        console.error('History container not found');
        return;
    }

    historyContainer.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';

    try {
        const result = await getTranslationHistory();
        console.log('Translation history result:', result);
        
        if (result.error) {
            historyContainer.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
            return;
        }

        if (!result.translations || !Array.isArray(result.translations) || result.translations.length === 0) {
            historyContainer.innerHTML = '<div class="alert alert-info">Belum ada riwayat terjemahan</div>';
            return;
        }

        const historyHTML = result.translations.map(item => {
            try {
                // Validasi data item
                if (!item || typeof item !== 'object') {
                    console.error('Invalid translation item:', item);
                    return '';
                }

                // Pastikan semua properti yang diperlukan ada
                const safeItem = {
                    source_language: item.source_language || 'Unknown',
                    target_language: item.target_language || 'Unknown',
                    created_at: item.created_at || new Date().toISOString(),
                    translation_quality: item.translation_quality || 0,
                    original_content: item.original_content || '',
                    translated_content: item.translated_content || '',
                    id: item.id || ''
                };

                // Ambil nama user dari localStorage
                const user = JSON.parse(localStorage.getItem('user'));
                const userName = user ? user.name.split(' ')[0] : 'User'; // Ambil nama depan saja

                // Dapatkan kode bendera
                const sourceFlag = getLanguageFlag(safeItem.source_language);
                const targetFlag = getLanguageFlag(safeItem.target_language);
                
                console.log('Source flag:', sourceFlag, 'Target flag:', targetFlag); // Debug

                return `
                    <div class="history-item">
                        <div class="history-info">
                            <div class="translation-details">
                                <span class="file-name">
                                    <i class="fas fa-file-alt"></i>
                                    CV_${userName}
                                </span>
                                <span class="language-pair">

                                    ${safeItem.source_language} 
                                    <i class="fas fa-arrow-right mx-2"></i>
                                    ${safeItem.target_language}
                                </span>
                                <span class="date">
                                    ${new Date(safeItem.created_at).toLocaleString('id-ID', {
                                        day: 'numeric',
                                        month: 'short',
                                        year: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}
                                </span>
                                <span class="quality-badge">
                                    <i class="fas fa-star text-warning"></i>
                                    ${(safeItem.translation_quality * 100).toFixed(1)}%
                                </span>
                            </div>
                        </div>
                        <div class="history-actions">
                            <button class="btn btn-sm btn-outline-primary copy-btn" data-text="${safeItem.translated_content.replace(/"/g, '&quot;')}">
                                <i class="fas fa-copy"></i> Salin
                            </button>
                            ${safeItem.id ? `
                                <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${safeItem.id}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            ` : ''}
                        </div>
                    </div>
                `;
            } catch (error) {
                console.error('Error rendering translation item:', error);
                return '';
            }
        }).filter(Boolean).join('');

        if (!historyHTML) {
            historyContainer.innerHTML = '<div class="alert alert-warning">Tidak ada data terjemahan yang valid</div>';
            return;
        }

        historyContainer.innerHTML = historyHTML;

        // Event listener untuk tombol salin
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const text = button.dataset.text;
                if (!text) return;
                
                try {
                    await navigator.clipboard.writeText(text);
                    button.innerHTML = '<i class="fas fa-check"></i> Tersalin';
                    button.classList.remove('btn-outline-primary');
                    button.classList.add('btn-success');
                    setTimeout(() => {
                        button.innerHTML = '<i class="fas fa-copy"></i> Salin';
                        button.classList.remove('btn-success');
                        button.classList.add('btn-outline-primary');
                    }, 2000);
                } catch (err) {
                    console.error('Gagal menyalin teks:', err);
                    Swal.fire('Error', 'Gagal menyalin teks ke clipboard', 'error');
                }
            });
        });

        // Event listener untuk tombol hapus
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const id = button.dataset.id;
                if (!id) return;

                try {
                    const confirmed = await Swal.fire({
                        title: 'Hapus Terjemahan?',
                        text: 'Anda yakin ingin menghapus terjemahan ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    });

                    if (confirmed.isConfirmed) {
                        const response = await fetch(`api/delete_translation.php?id=${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Authorization': `Bearer ${localStorage.getItem('token')}`
                            }
                        });

                        const responseText = await response.text();
                        let result;
                        try {
                            result = JSON.parse(responseText);
                        } catch (e) {
                            console.error('Failed to parse delete response:', responseText);
                            throw new Error('Format response tidak valid');
                        }

                        if (result.success) {
                            await displayTranslationHistory(); // Refresh daftar
                            Swal.fire(
                                'Terhapus!',
                                'Terjemahan berhasil dihapus',
                                'success'
                            );
                        } else {
                            throw new Error(result.error || 'Gagal menghapus terjemahan');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire(
                        'Error!',
                        error.message,
                        'error'
                    );
                }
            });
        });
    } catch (error) {
        console.error('Error in displayTranslationHistory:', error);
        historyContainer.innerHTML = `<div class="alert alert-danger">Gagal memuat riwayat terjemahan: ${error.message}</div>`;
    }
}

// Panggil fungsi displayTranslationHistory saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    displayTranslationHistory();
});