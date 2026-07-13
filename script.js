// VALIDASI FORM VOTING BAND

const votingForm = document.getElementById('voting-form');
const namaVoterInput = document.getElementById('nama-voter');
const emailInput = document.getElementById('email');
const pilihanBandInput = document.getElementById('pilihan-band');
const alasanInput = document.getElementById('alasan');
const errorContainer = document.getElementById('error-container');
const successMessage = document.getElementById('success-message');
const charCountDisplay = document.getElementById('char-count');

// Error message elements
const errorNama = document.getElementById('error-nama');
const errorEmail = document.getElementById('error-email');
const errorBand = document.getElementById('error-band');
const errorAlasan = document.getElementById('error-alasan');

// ===========================
// REGEX VALIDATION PATTERNS
// ===========================
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const minAlasan = 15;

// ===========================
// REAL-TIME VALIDATION
// ===========================

// Validasi Nama - Real-time
namaVoterInput.addEventListener('input', function() {
    clearErrorMessage(errorNama);
    namaVoterInput.classList.remove('error');
});

// Validasi Email - Real-time
emailInput.addEventListener('input', function() {
    clearErrorMessage(errorEmail);
    emailInput.classList.remove('error');
});

// Validasi Pilihan Band - Real-time
pilihanBandInput.addEventListener('change', function() {
    clearErrorMessage(errorBand);
    pilihanBandInput.classList.remove('error');
});

// Validasi Alasan - Real-time dengan character counter
alasanInput.addEventListener('input', function() {
    clearErrorMessage(errorAlasan);
    alasanInput.classList.remove('error');
    updateCharCounter();
});

// ===========================
// CHARACTER COUNTER
// ===========================
function updateCharCounter() {
    const currentLength = alasanInput.value.length;
    charCountDisplay.textContent = currentLength + '/' + minAlasan + ' karakter';
    
    if (currentLength < minAlasan) {
        charCountDisplay.classList.remove('valid');
        charCountDisplay.classList.add('warning');
    } else {
        charCountDisplay.classList.remove('warning');
        charCountDisplay.classList.add('valid');
    }
}

// ===========================
// VALIDATION FUNCTIONS
// ===========================

function validateNama(nama) {
    // Trim whitespace
    nama = nama.trim();
    
    // Check if empty
    if (nama === '') {
        return {
            valid: false,
            message: '⚠️ Nama tidak boleh kosong'
        };
    }
    
    // Check minimum length
    if (nama.length < 3) {
        return {
            valid: false,
            message: '⚠️ Nama minimal harus 3 karakter'
        };
    }
    
    return {
        valid: true,
        message: ''
    };
}

function validateEmail(email) {
    email = email.trim();
    
    // Check if empty
    if (email === '') {
        return {
            valid: false,
            message: '⚠️ Email tidak boleh kosong'
        };
    }
    
    // Check email format
    if (!emailRegex.test(email)) {
        return {
            valid: false,
            message: '⚠️ Format email tidak valid (contoh: nama@email.com)'
        };
    }
    
    return {
        valid: true,
        message: ''
    };
}

function validateBand(bandId) {
    if (bandId === '') {
        return {
            valid: false,
            message: '⚠️ Silakan pilih salah satu band'
        };
    }
    
    return {
        valid: true,
        message: ''
    };
}

function validateAlasan(alasan) {
    alasan = alasan.trim();
    
    // Check if empty
    if (alasan === '') {
        return {
            valid: false,
            message: '⚠️ Alasan tidak boleh kosong'
        };
    }
    
    // Check minimum character
    if (alasan.length < minAlasan) {
        return {
            valid: false,
            message: '⚠️ Alasan minimal harus ' + minAlasan + ' karakter (saat ini: ' + alasan.length + ' karakter)'
        };
    }
    
    return {
        valid: true,
        message: ''
    };
}

// ===========================
// DOM ERROR DISPLAY FUNCTIONS
// ===========================

function showErrorMessage(inputElement, errorElement, message) {
    inputElement.classList.add('error');
    errorElement.textContent = message;
    errorElement.classList.add('show');
}

function clearErrorMessage(errorElement) {
    errorElement.textContent = '';
    errorElement.classList.remove('show');
}

function showErrorContainer(errors) {
    errorContainer.innerHTML = '';
    const errorList = document.createElement('ul');
    
    errors.forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        errorList.appendChild(li);
    });
    
    errorContainer.appendChild(errorList);
    errorContainer.classList.add('show');
    errorContainer.style.display = 'block';
    
    // Scroll to error container
    errorContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function hideErrorContainer() {
    errorContainer.classList.remove('show');
    errorContainer.style.display = 'none';
    errorContainer.innerHTML = '';
}

// ===========================
// FORM SUBMISSION
// ===========================

votingForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Reset previous errors
    hideErrorContainer();
    clearErrorMessage(errorNama);
    clearErrorMessage(errorEmail);
    clearErrorMessage(errorBand);
    clearErrorMessage(errorAlasan);
    namaVoterInput.classList.remove('error');
    emailInput.classList.remove('error');
    pilihanBandInput.classList.remove('error');
    alasanInput.classList.remove('error');
    
    // Validate all fields
    const namaValidation = validateNama(namaVoterInput.value);
    const emailValidation = validateEmail(emailInput.value);
    const bandValidation = validateBand(pilihanBandInput.value);
    const alasanValidation = validateAlasan(alasanInput.value);
    
    let errors = [];
    let isValid = true;
    
    // Display individual field errors
    if (!namaValidation.valid) {
        showErrorMessage(namaVoterInput, errorNama, namaValidation.message);
        errors.push(namaValidation.message);
        isValid = false;
    }
    
    if (!emailValidation.valid) {
        showErrorMessage(emailInput, errorEmail, emailValidation.message);
        errors.push(emailValidation.message);
        isValid = false;
    }
    
    if (!bandValidation.valid) {
        showErrorMessage(pilihanBandInput, errorBand, bandValidation.message);
        errors.push(bandValidation.message);
        isValid = false;
    }
    
    if (!alasanValidation.valid) {
        showErrorMessage(alasanInput, errorAlasan, alasanValidation.message);
        errors.push(alasanValidation.message);
        isValid = false;
    }
    
    if (!isValid) {
        showErrorContainer(errors);
        return;
    }

    submitVote();
});

// ===========================
// SUBMIT VOTE TO SERVER
// ===========================

function submitVote() {
    const formData = new FormData();
    formData.append('nama_voter', namaVoterInput.value.trim());
    formData.append('email', emailInput.value.trim());
    formData.append('pilihan_band', pilihanBandInput.value);
    formData.append('alasan', alasanInput.value.trim());
    
    // Send to process_vote.php
    fetch('process_vote.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide form dan show success message
            votingForm.style.display = 'none';
            successMessage.style.display = 'block';
            
            successMessage.scrollIntoView({ behavior: 'smooth' });
            
            // Reset form untuk kemungkinan vote lagi (opsional)
            setTimeout(() => {
                votingForm.reset();
                votingForm.style.display = 'flex';
                successMessage.style.display = 'none';
                updateCharCounter();
            }, 3000);
        } else {
            showErrorContainer([data.message || 'Terjadi kesalahan saat menyimpan suara']);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorContainer(['Terjadi kesalahan jaringan. Silakan coba lagi.']);
    });
}

// ===========================
// INITIALIZE ON PAGE LOAD
// ===========================

document.addEventListener('DOMContentLoaded', function() {

    updateCharCounter();
});
