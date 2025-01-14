// Inisialisasi Quill editor
let quill;

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Quill dengan opsi kustomisasi
    quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['clean']
            ]
        },
        placeholder: 'Mulai menulis surat lamaran Anda...'
    });

    // Event listener untuk auto-save
    let autoSaveTimeout;
    quill.on('text-change', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            saveToLocalStorage();
        }, 1000);
    });

    // Load data dari localStorage jika ada
    loadFromLocalStorage();

    // Event listener untuk tombol refresh preview
    document.getElementById('refreshPreview').addEventListener('click', updatePreview);

    // Event listener untuk tombol save
    document.getElementById('saveButton').addEventListener('click', function() {
        saveToLocalStorage();
        showSaveSuccess();
    });

    // Event listener untuk tombol reset
    document.getElementById('resetButton').addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin mereset semua perubahan?')) {
            resetEditor();
        }
    });

    // Event listener untuk tombol download PDF
    document.getElementById('downloadPDF').addEventListener('click', downloadAsPDF);

    // Event listener untuk perubahan input form
    const formInputs = document.querySelectorAll('input');
    formInputs.forEach(input => {
        input.addEventListener('change', function() {
            saveToLocalStorage();
            updatePreview();
        });
    });

    // Update preview saat pertama kali load
    updatePreview();
});

// Fungsi untuk update preview
function updatePreview() {
    const previewContent = document.getElementById('previewContent');
    const companyName = document.getElementById('companyName').value || '[Nama Perusahaan]';
    const position = document.getElementById('position').value || '[Posisi]';
    const recipientName = document.getElementById('recipientName').value || '[Nama HR]';
    const letterDate = document.getElementById('letterDate').value;
    const content = quill.root.innerHTML;

    // Format tanggal
    const formattedDate = letterDate ? new Date(letterDate).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }) : new Date().toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    // Update preview
    previewContent.innerHTML = `
        <div class="preview-letter">
            <div class="letter-header">
                <p class="letter-date">Jakarta, ${formattedDate}</p>
                <p class="letter-recipient">
                    Kepada Yth.<br>
                    ${recipientName}<br>
                    ${companyName}
                </p>
            </div>
            <div class="letter-body">
                ${content}
            </div>
        </div>
    `;
}

// Fungsi untuk menyimpan ke localStorage
function saveToLocalStorage() {
    const data = {
        companyName: document.getElementById('companyName').value,
        position: document.getElementById('position').value,
        recipientName: document.getElementById('recipientName').value,
        letterDate: document.getElementById('letterDate').value,
        content: quill.root.innerHTML
    };
    localStorage.setItem('coverLetterData', JSON.stringify(data));
}

// Fungsi untuk memuat dari localStorage
function loadFromLocalStorage() {
    const savedData = localStorage.getItem('coverLetterData');
    if (savedData) {
        const data = JSON.parse(savedData);
        document.getElementById('companyName').value = data.companyName || '';
        document.getElementById('position').value = data.position || '';
        document.getElementById('recipientName').value = data.recipientName || '';
        document.getElementById('letterDate').value = data.letterDate || '';
        quill.root.innerHTML = data.content || '';
    }
}

// Fungsi untuk reset editor
function resetEditor() {
    document.getElementById('companyName').value = '';
    document.getElementById('position').value = '';
    document.getElementById('recipientName').value = '';
    document.getElementById('letterDate').value = '';
    quill.root.innerHTML = `
        <p>Yang terhormat,</p>
        <p>Bapak/Ibu [Nama HR],</p>
        <p>[Nama Perusahaan]</p>
        <br>
        <p>Dengan surat ini, saya [Nama Anda] mengajukan lamaran untuk posisi [Posisi] di [Nama Perusahaan].</p>
        <br>
        <p>[Isi cover letter Anda di sini...]</p>
        <br>
        <p>Hormat saya,</p>
        <p>[Nama Anda]</p>
    `;
    localStorage.removeItem('coverLetterData');
    updatePreview();
}

// Fungsi untuk menampilkan notifikasi sukses
function showSaveSuccess() {
    const saveButton = document.getElementById('saveButton');
    const originalText = saveButton.innerHTML;
    
    saveButton.disabled = true;
    saveButton.innerHTML = '<i class="fas fa-check"></i> Tersimpan!';
    
    setTimeout(() => {
        saveButton.disabled = false;
        saveButton.innerHTML = originalText;
    }, 2000);
}

// Fungsi untuk download PDF
function downloadAsPDF() {
    const previewContent = document.getElementById('previewContent');
    const opt = {
        margin: 20,
        filename: 'cover-letter.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { 
            scale: 2,
            letterRendering: true,
            useCORS: true
        },
        jsPDF: { 
            unit: 'mm', 
            format: 'a4', 
            orientation: 'portrait' 
        }
    };

    // Tampilkan loading state
    const downloadBtn = document.getElementById('downloadPDF');
    const originalText = downloadBtn.innerHTML;
    downloadBtn.disabled = true;
    downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengunduh...';

    // Generate PDF
    html2pdf().set(opt).from(previewContent).save()
        .then(() => {
            downloadBtn.innerHTML = originalText;
            downloadBtn.disabled = false;
        })
        .catch(err => {
            console.error('Error generating PDF:', err);
            alert('Terjadi kesalahan saat mengunduh PDF. Silakan coba lagi.');
            downloadBtn.innerHTML = originalText;
            downloadBtn.disabled = false;
        });
} 