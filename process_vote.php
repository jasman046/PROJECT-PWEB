<?php

header('Content-Type: application/json');

// Check jika request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method tidak diizinkan'
    ]);
    exit;
}


$nama_voter = isset($_POST['nama_voter']) ? trim($_POST['nama_voter']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$pilihan_band = isset($_POST['pilihan_band']) ? trim($_POST['pilihan_band']) : '';
$alasan = isset($_POST['alasan']) ? trim($_POST['alasan']) : '';


// SERVER-SIDE VALIDASI
$errors = [];

if (empty($nama_voter)) {
    $errors[] = 'Nama tidak boleh kosong';
} elseif (strlen($nama_voter) < 3) {
    $errors[] = 'Nama minimal harus 3 karakter';
}

if (empty($email)) {
    $errors[] = 'Email tidak boleh kosong';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Format email tidak valid';
}

if (empty($pilihan_band)) {
    $errors[] = 'Silakan pilih salah satu band';
} elseif (!in_array($pilihan_band, ['1', '2', '3'])) {
    $errors[] = 'Pilihan band tidak valid';
}

if (empty($alasan)) {
    $errors[] = 'Alasan tidak boleh kosong';
} elseif (strlen($alasan) < 15) {
    $errors[] = 'Alasan minimal harus 15 karakter';
}

// RETURN ERROR JIKA ADA VALIDASI GAGAL
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => implode('; ', $errors)
    ]);
    exit;
}

// SAVE VOTE KE FILE
$voting_file = 'voting_band.txt';

$timestamp = date('Y-m-d H:i:s');
$vote_data = "====================================\n";
$vote_data .= "WAKTU: " . $timestamp . "\n";
$vote_data .= "NAMA: " . htmlspecialchars($nama_voter) . "\n";
$vote_data .= "EMAIL: " . htmlspecialchars($email) . "\n";
$vote_data .= "BAND ID: " . htmlspecialchars($pilihan_band) . "\n";
$vote_data .= "ALASAN:\n" . htmlspecialchars($alasan) . "\n";
$vote_data .= "====================================\n\n";

// gunakan append mode
$result = file_put_contents($voting_file, $vote_data, FILE_APPEND | LOCK_EX);

if ($result === false) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan suara. Cek permission folder.'
    ]);
    exit;
}


// Simpan juga dalam format JSON untuk kemudahan parsing (opsional)
$voting_json_file = 'voting_band.json';

// Baca existing JSON atau buat array baru
if (file_exists($voting_json_file)) {
    $json_content = file_get_contents($voting_json_file);
    $votes = json_decode($json_content, true);
    if (!is_array($votes)) {
        $votes = [];
    }
} else {
    $votes = [];
}

// Tambah vote baru
$new_vote = [
    'timestamp' => $timestamp,
    'nama' => $nama_voter,
    'email' => $email,
    'band_id' => (int)$pilihan_band,
    'alasan' => $alasan
];

$votes[] = $new_vote;

// Simpan kembali ke JSON
file_put_contents($voting_json_file, json_encode($votes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);

// RETURN SUCCESS JIKA SEMUA BERHASIL

echo json_encode([
    'success' => true,
    'message' => 'Suara berhasil disimpan',
    'data' => [
        'nama' => $nama_voter,
        'band_id' => $pilihan_band,
        'timestamp' => $timestamp
    ]
]);

exit;
?>