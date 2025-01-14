const GEMINI_API_KEY = 'AIzaSyCAmJdyfzPJQJ7pTIxTJYRyjEwXZdeL-50';

// Fungsi untuk menyimpan cover letter ke database
async function saveCoverLetter(content, formData) {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) {
        alert('Silakan login terlebih dahulu');
        return;
    }

    // Bersihkan konten dari format HTML
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = content;
    const cleanContent = tempDiv.innerText;

    const data = {
        user_id: user.id,
        company_name: formData.company,
        position: formData.position,
        recipient_name: formData.recipient,
        content: cleanContent
    };

    try {
        const response = await fetch('api/save_cover_letter.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.error || 'Terjadi kesalahan saat menyimpan');
        }

        alert('Cover letter berhasil disimpan!');
        return result.id;
    } catch (error) {
        console.error('Error saving cover letter:', error);
        throw error;
    }
}

// Fungsi untuk membersihkan konten dari header dan footer yang tidak diinginkan
function cleanGeneratedContent(content) {
    return content
        // Hapus header
        .replace(/Jakarta,.*\n*/g, '')
        .replace(/Kepada Yth\.[\s\S]*?(?=\n\n|\n[A-Z])/g, '')
        // Hapus footer/tanda tangan
        .replace(/\n*Hormat saya,?\n*.*$/g, '')
        .replace(/\n*\[.*\]$/g, '')
        // Hapus whitespace
        .replace(/^[\s\n]*/, '')
        .replace(/[\s\n]*$/, '')
        .trim();
}

// Fungsi untuk mengecek batasan penggunaan cover letter
async function checkCoverLetterUsage() {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) return false;

    try {
        const response = await fetch(`api/check_feature_usage.php?user_id=${user.id}&feature_type=cover_letter`);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Terjadi kesalahan saat memeriksa penggunaan');
        }

        // Cek batasan berdasarkan tipe subscription
        if (user.subscription_type === 'free' && data.usage_count >= 3) {
            Swal.fire({
                title: '<span style="font-size: 24px; font-weight: 600;">🔒 Batas Penggunaan Tercapai</span>',
                html: `
                    <div style="margin: 20px 0; text-align: left;">
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                            <p style="margin: 0; color: #4b5563;">
                                <i class="fas fa-info-circle" style="color: #3b82f6;"></i>
                                Anda telah menggunakan <strong>${data.usage_count}/3</strong> cover letter dari akun free.
                            </p>
                        </div>
                        <p style="color: #4b5563; margin-bottom: 10px;">
                            Upgrade ke premium untuk mendapatkan:
                        </p>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="margin-bottom: 8px; color: #4b5563;">
                                <i class="fas fa-check" style="color: #10b981;"></i>
                                Pembuatan cover letter tanpa batas
                            </li>
                            <li style="margin-bottom: 8px; color: #4b5563;">
                                <i class="fas fa-check" style="color: #10b981;"></i>
                                Template premium eksklusif
                            </li>
                            <li style="color: #4b5563;">
                                <i class="fas fa-check" style="color: #10b981;"></i>
                                Fitur AI tingkat lanjut
                            </li>
                        </ul>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-crown"></i> Upgrade Sekarang',
                cancelButtonText: 'Nanti Saja',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                customClass: {
                    confirmButton: 'btn-lg',
                    cancelButton: 'btn-lg'
                },
                buttonsStyling: true,
                showCloseButton: true,
                background: '#ffffff',
                backdrop: 'rgba(0, 0, 0, 0.4)'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'pricing.php';
                }
            });
            return false;
        } else if (user.subscription_type === 'basic' && data.usage_count >= 10) {
            Swal.fire({
                title: '<span style="font-size: 24px; font-weight: 600;">🔒 Batas Penggunaan Tercapai</span>',
                html: `
                    <div style="margin: 20px 0; text-align: left;">
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                            <p style="margin: 0; color: #4b5563;">
                                <i class="fas fa-info-circle" style="color: #3b82f6;"></i>
                                Anda telah menggunakan <strong>${data.usage_count}/10</strong> cover letter dari akun basic.
                            </p>
                        </div>
                        <p style="color: #4b5563; margin-bottom: 10px;">
                            Upgrade ke pro untuk mendapatkan:
                        </p>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="margin-bottom: 8px; color: #4b5563;">
                                <i class="fas fa-check" style="color: #10b981;"></i>
                                Pembuatan cover letter tanpa batas
                            </li>
                            <li style="margin-bottom: 8px; color: #4b5563;">
                                <i class="fas fa-check" style="color: #10b981;"></i>
                                Prioritas dukungan pelanggan
                            </li>
                            <li style="color: #4b5563;">
                                <i class="fas fa-check" style="color: #10b981;"></i>
                                Fitur AI tingkat lanjut
                            </li>
                        </ul>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-crown"></i> Upgrade Sekarang',
                cancelButtonText: 'Nanti Saja',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                customClass: {
                    confirmButton: 'btn-lg',
                    cancelButton: 'btn-lg'
                },
                buttonsStyling: true,
                showCloseButton: true,
                background: '#ffffff',
                backdrop: 'rgba(0, 0, 0, 0.4)'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'pricing.php';
                }
            });
            return false;
        }

        return true;
    } catch (error) {
        console.error('Error checking usage:', error);
        throw error;
    }
}

async function generateCoverLetter(data) {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) {
        alert('Silakan login terlebih dahulu');
        return;
    }

    // Cek batasan penggunaan
    const canGenerate = await checkCoverLetterUsage();
    if (!canGenerate) return;

    const userName = user ? user.name : '[Nama Anda]';

    const prompt = `Buatkan surat lamaran kerja dengan detail berikut:
    - Nama Pelamar: ${userName}
    - Perusahaan: ${data.company}
    - Posisi: ${data.position}
    - Penerima: ${data.recipient || 'HR Manager'}
    - Deskripsi Pekerjaan: ${data.jobDesc}
    - Keahlian Utama: ${data.keySkills}
    - Pencapaian: ${data.achievements}
    - Gaya: ${data.writingStyle}
    - Nada: ${data.tone}
    - Panjang: ${data.length}

    Format surat harus formal dalam Bahasa Indonesia.
    Langsung mulai dengan paragraf pembuka, JANGAN sertakan tanggal, alamat penerima, atau tanda tangan.
    Gunakan nama pelamar (${userName}) dalam isi surat untuk menunjukkan pengalaman dan kualifikasi.
    
    Struktur konten:
    - Paragraf pembuka yang menarik
    - Isi surat (2-3 paragraf) yang menjelaskan kualifikasi dan pengalaman
    - Paragraf penutup yang kuat`;

    try {
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
        
        // Bersihkan konten dari header dan footer yang tidak diinginkan
        const cleanedContent = cleanGeneratedContent(result.candidates[0].content.parts[0].text);
        
        if (!cleanedContent || cleanedContent.trim() === '') {
            throw new Error('Konten yang dihasilkan kosong');
        }

        return cleanedContent;
    } catch (error) {
        console.error('Error generating cover letter:', error);
        Swal.fire({
            title: 'Error',
            text: 'Gagal generate surat lamaran. Silakan coba lagi.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return null;
    }
}

// Fungsi untuk memformat tanggal
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
}

// Fungsi untuk menampilkan overlay
function showLettersOverlay() {
    const overlay = document.querySelector('.letters-overlay');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
    fetchCoverLetters();
}

// Fungsi untuk menyembunyikan overlay
function hideLettersOverlay() {
    const overlay = document.querySelector('.letters-overlay');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
}

// Fungsi untuk mengambil daftar cover letter
async function fetchCoverLetters() {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) return;

    const container = document.querySelector('.letters-grid');
    
    try {
        // Tampilkan loading state
        container.innerHTML = `
            <div class="loading-state" style="text-align: center; padding: 2rem; width: 100%;">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #3b82f6; margin-bottom: 1rem;"></i>
                <p style="color: #6b7280; margin: 0;">Memuat daftar cover letter...</p>
            </div>
        `;

        const response = await fetch(`api/get_cover_letters.php?user_id=${user.id}`);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Terjadi kesalahan saat memuat data');
        }
        
        if (!data.data || data.data.length === 0) {
            container.innerHTML = `
                <div class="no-letters-found">
                    <i class="fas fa-envelope mb-3 d-block"></i>
                    <p class="mb-0">Belum ada cover letter yang dibuat</p>
                </div>
            `;
            return;
        }

        container.innerHTML = data.data.map(letter => `
            <div class="cover-letter-card">
                <div class="card-body">
                    <div class="company-name">
                        <i class="fas fa-building me-2"></i>
                        ${letter.company_name}
                    </div>
                    <div class="position">
                        <i class="fas fa-briefcase me-2"></i>
                        ${letter.position}
                    </div>
                    <div class="date">
                        <i class="fas fa-calendar me-2"></i>
                        ${formatDate(letter.created_at)}
                    </div>
                    <div class="actions">
                        <button class="btn btn-sm btn-outline-secondary copy-btn" data-id="${letter.id}">
                            <i class="fas fa-copy"></i> Salin
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteCoverLetter(${letter.id})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        `).join('');

        // Tambahkan event listener untuk tombol copy setelah render
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                copyCardLetterText(this.dataset.id, this);
            });
        });

    } catch (error) {
        console.error('Error fetching cover letters:', error);
        container.innerHTML = `
            <div class="error-state" style="text-align: center; padding: 2rem; width: 100%;">
                <i class="fas fa-exclamation-circle" style="font-size: 2rem; color: #ef4444; margin-bottom: 1rem;"></i>
                <p style="color: #4b5563; margin: 0 0 1rem 0;">Gagal memuat daftar cover letter</p>
                <button onclick="fetchCoverLetters()" class="btn btn-sm btn-primary">
                    <i class="fas fa-redo"></i> Coba Lagi
                </button>
            </div>
        `;
    }
}

// Fungsi untuk menghapus cover letter
async function deleteCoverLetter(id) {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) return;

    try {
        // Konfirmasi penghapusan
        const result = await Swal.fire({
            title: 'Hapus Surat Lamaran?',
            text: 'Surat lamaran yang dihapus tidak dapat dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        });

        if (!result.isConfirmed) {
            return;
        }

        // Tampilkan loading
        Swal.fire({
            title: 'Menghapus...',
            html: 'Mohon tunggu sebentar...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        const token = localStorage.getItem('token');
        const response = await fetch(`api/delete_cover_letter.php?id=${id}&user_id=${user.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response server tidak valid');
        }

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Terjadi kesalahan');
        }

        // Refresh daftar cover letter
        fetchCoverLetters();
        
        // Tampilkan pesan sukses
        Swal.fire({
            title: 'Berhasil!',
            text: 'Surat lamaran berhasil dihapus',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    } catch (error) {
        console.error('Error deleting cover letter:', error);
        Swal.fire({
            title: 'Error',
            text: 'Gagal menghapus surat lamaran. Silakan coba lagi.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
}

// Fungsi untuk menyalin teks surat lamaran
const copyLetterText = () => {
    const letterContent = document.querySelector('.generated-letter');
    if (!letterContent) {
        alert('Harap generate surat terlebih dahulu sebelum menyalin');
        return;
    }

    try {
        // Buat elemen temporary untuk menyalin teks
        const tempTextArea = document.createElement('textarea');
        tempTextArea.value = letterContent.innerText;
        document.body.appendChild(tempTextArea);
        tempTextArea.select();
        document.execCommand('copy');
        document.body.removeChild(tempTextArea);

        // Feedback sukses
        const copyBtn = document.querySelector('.copy-text-btn');
        const originalText = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
        setTimeout(() => {
            copyBtn.innerHTML = originalText;
        }, 2000);
    } catch (err) {
        console.error('Gagal menyalin teks:', err);
        alert('Gagal menyalin teks. Silakan coba lagi.');
    }
};

// Fungsi untuk membersihkan HTML dan mendapatkan teks bersih
function cleanHtmlContent(html) {
    // Buat elemen temporary
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = html;
    
    // Dapatkan teks tanpa HTML tags
    let text = tempDiv.innerText || tempDiv.textContent;
    
    // Bersihkan whitespace berlebih
    text = text.replace(/\s+/g, ' ').trim();
    
    return text;
}

// Fungsi untuk menyalin teks dari kartu cover letter
async function copyCardLetterText(id, buttonElement) {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) {
        alert('Silakan login terlebih dahulu');
        return;
    }

    try {
        const response = await fetch(`api/get_cover_letter.php?id=${id}&user_id=${user.id}`);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Terjadi kesalahan');
        }

        if (!data.success || !data.data.content) {
            throw new Error('Konten surat tidak ditemukan');
        }

        // Salin teks langsung karena sudah dalam format teks biasa
        const tempTextArea = document.createElement('textarea');
        tempTextArea.value = data.data.content;
        document.body.appendChild(tempTextArea);
        tempTextArea.select();
        document.execCommand('copy');
        document.body.removeChild(tempTextArea);

        // Feedback sukses
        const originalText = buttonElement.innerHTML;
        buttonElement.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
        setTimeout(() => {
            buttonElement.innerHTML = originalText;
        }, 2000);
    } catch (error) {
        console.error('Error copying letter text:', error);
        alert('Gagal menyalin teks. Silakan coba lagi.');
    }
}

// Fungsi untuk menampilkan konten surat
function displayLetterContent(content, recipient = '', company = '') {
    // Validasi konten
    if (!content) {
        return `
            <div class="generated-letter">
                <div class="letter-content">
                    <p>Terjadi kesalahan saat memuat konten surat.</p>
                </div>
            </div>
        `;
    }

    const today = new Date().toLocaleDateString('id-ID', { 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric' 
    });

    // Ambil nama user
    const user = JSON.parse(localStorage.getItem('user'));
    const userName = user ? user.name : '[Nama Anda]';

    // Format konten dengan header dan footer
    const formattedContent = `
        <div class="generated-letter">
            <div class="letter-header">
                <p class="date">Jakarta, ${today}</p>
                <p class="recipient">
                    Kepada Yth.<br>
                    ${recipient || 'HR Manager'}<br>
                    ${company}
                </p>
            </div>
            <div class="letter-content">
                ${content.replace(/\n/g, '<br>')}
                <br><br>
                Hormat saya,<br><br>
                ${userName}
            </div>
        </div>
    `;

    return formattedContent;
}

// Event listener untuk form submission
document.addEventListener('DOMContentLoaded', () => {
    // Load existing cover letters
    fetchCoverLetters();

    const generateBtn = document.querySelector('.generate-btn');
    const saveDraftBtn = document.querySelector('.save-draft-btn');
    const previewContent = document.querySelector('.preview-content');
    const copyBtn = document.querySelector('.copy-text-btn');

    // Tambahkan event listener untuk tombol copy
    copyBtn.addEventListener('click', copyLetterText);

    // Tambahkan event listener untuk tombol save draft
    saveDraftBtn.addEventListener('click', async () => {
        const letterContent = document.querySelector('.letter-content');
        if (!letterContent) {
            alert('Harap generate surat terlebih dahulu sebelum menyimpan');
            return;
        }

        // Cek batasan penggunaan sebelum menyimpan
        const canSave = await checkCoverLetterUsage();
        if (!canSave) return;

        saveDraftBtn.disabled = true;
        saveDraftBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

        try {
            const formData = {
                company: document.getElementById('company').value,
                position: document.getElementById('position').value,
                recipient: document.getElementById('recipient').value
            };
            await saveCoverLetter(letterContent.innerHTML, formData);
            saveDraftBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Draft';
            saveDraftBtn.disabled = false;
        } catch (error) {
            alert(error.message);
            saveDraftBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Draft';
            saveDraftBtn.disabled = false;
        }
    });

    generateBtn.addEventListener('click', async () => {
        // Mengumpulkan data dari form
        const data = {
            company: document.getElementById('company').value,
            position: document.getElementById('position').value,
            recipient: document.getElementById('recipient').value,
            jobDesc: document.getElementById('job-desc').value,
            keySkills: document.getElementById('key-skills').value,
            achievements: document.getElementById('achievements').value,
            writingStyle: document.querySelector('input[name="writingStyle"]:checked').id,
            tone: document.getElementById('tone').value,
            length: document.getElementById('length').value
        };

        // Validasi form
        if (!data.company || !data.position || !data.jobDesc || !data.keySkills) {
            Swal.fire({
                title: 'Data Tidak Lengkap',
                text: 'Mohon lengkapi informasi wajib (Perusahaan, Posisi, Deskripsi Pekerjaan, dan Keahlian Utama)',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Konfirmasi sebelum generate
        try {
            const result = await Swal.fire({
                title: '<span style="font-size: 24px; font-weight: 600;">✨ Generate Surat Lamaran?</span>',
                html: `
                    <div style="margin: 20px 0; text-align: left;">
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                            <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #374151;">Detail Surat:</h3>
                            <div style="display: grid; grid-template-columns: auto 1fr; gap: 8px; color: #4b5563;">
                                <div style="display: flex; align-items: center;">
                                    <span style="margin-right: 8px;">Perusahaan:</span>
                                </div>
                                <div><strong>${data.company}</strong></div>
                                
                                <div style="display: flex; align-items: center;">
                                    <span style="margin-right: 8px;">Penerima:</span>
                                </div>
                                <div><strong>${data.recipient || 'HR Manager'}</strong></div>
                                
                                <div style="display: flex; align-items: center;">
                                    <span style="margin-right: 8px;">Gaya:</span>
                                </div>
                                <div><strong>${data.writingStyle}</strong></div>
                                
                                <div style="display: flex; align-items: center;">
                                    <span style="margin-right: 8px;">Nada:</span>
                                </div>
                                <div><strong>${data.tone}</strong></div>
                                
                                <div style="display: flex; align-items: center;">
                                    <span style="margin-right: 8px;">Panjang:</span>
                                </div>
                                <div><strong>${data.length}</strong></div>
                            </div>
                        </div>
                        <p style="margin: 0; color: #6b7280; font-size: 14px;">
                            <i class="fas fa-info-circle" style="color: #3b82f6;"></i>
                            AI akan menggunakan detail di atas untuk membuat surat lamaran yang sesuai.
                        </p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-magic"></i> Ya, Generate',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                customClass: {
                    confirmButton: 'btn-lg',
                    cancelButton: 'btn-lg'
                },
                buttonsStyling: true,
                showCloseButton: true,
                background: '#ffffff',
                backdrop: 'rgba(0, 0, 0, 0.4)'
            });

            if (!result.isConfirmed) {
                return;
            }

            // Cek batasan penggunaan sebelum generate
            const canGenerate = await checkCoverLetterUsage();
            if (!canGenerate) return;

            // Tampilkan loading
            Swal.fire({
                title: 'Generating...',
                html: 'Mohon tunggu sebentar...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            generateBtn.disabled = true;

            const coverLetter = await generateCoverLetter(data);
            if (!coverLetter) {
                generateBtn.disabled = false;
                return;
            }
            
            // Tampilkan hasil dengan format yang benar
            const letterContent = displayLetterContent(coverLetter, data.recipient, data.company);
            previewContent.innerHTML = letterContent;

            // Simpan otomatis ke database
            try {
                await saveCoverLetter(coverLetter, data);
                console.log('Cover letter berhasil disimpan ke database');
                
                // Tampilkan pesan sukses
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Surat lamaran berhasil dibuat dan disimpan',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            } catch (saveError) {
                console.error('Gagal menyimpan ke database:', saveError);
                Swal.fire({
                    title: 'Perhatian',
                    text: 'Surat berhasil di-generate tapi gagal disimpan ke database',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            }

        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Terjadi kesalahan saat generate surat lamaran. Silakan coba lagi.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        } finally {
            generateBtn.disabled = false;
            generateBtn.innerHTML = '<i class="fas fa-magic"></i> Generate dengan AI';
        }
    });

    // Event listener untuk tombol My Cover Letters
    const myLettersBtn = document.querySelector('.my-letters-btn');
    if (myLettersBtn) {
        myLettersBtn.addEventListener('click', showLettersOverlay);
    }

    // Event listener untuk tombol close modal
    const closeModalBtn = document.querySelector('.close-modal');
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', hideLettersOverlay);
    }

    // Event listener untuk menutup modal ketika mengklik overlay
    const overlay = document.querySelector('.letters-overlay');
    if (overlay) {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                hideLettersOverlay();
            }
        });
    }
});