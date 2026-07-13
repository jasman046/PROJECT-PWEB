<?php

$counter_file = 'counter_music.txt';

// Inisialisasi file counter jika belum ada
if (!file_exists($counter_file)) {
    file_put_contents($counter_file, '0');
}

// Baca counter saat ini
$current_hits = (int) file_get_contents($counter_file);

$current_hits++;

// Simpan kembali ke file
file_put_contents($counter_file, $current_hits);

$bands = [
    [
        'id' => 1,
        'nama' => '.Feast',
        'genre' => 'Indie Rock / Alternative',
        'kota' => 'Jakarta',
        'lagu_andalan' => 'Peradaban',
        'foto' => 'images/feast.jpg', 
        'deskripsi' => 'Band rock alternatif asal Jakarta yang dikenal dengan lirik kritis mengenai isu sosial dan politik, dibalut aransemen musik yang energik.'
    ],
    [
        'id' => 2,
        'nama' => 'Dewa 19',
        'genre' => 'Pop Rock',
        'kota' => 'Surabaya',
        'lagu_andalan' => 'Kangen',
        'foto' => 'images/dewa19.jpg', 
        'deskripsi' => 'Legenda musik rock Indonesia yang telah melahirkan banyak karya hits abadi dan mendominasi industri musik Tanah Air sejak era 90-an.'
    ],
    [
        'id' => 3,
        'nama' => 'Jamrud',
        'genre' => 'Hard Rock / Heavy Metal',
        'kota' => 'Cimahi',
        'lagu_andalan' => 'Pelangi di Matamu',
        'foto' => 'images/jamrud.jpg', 
        'deskripsi' => 'Band pelopor musik keras di Indonesia yang berhasil membawa musik heavy metal dan hard rock ke puncak popularitas arus utama.'
    ]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IndieSound - Katalog & Voting Band Lokal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="logo">IndieSound</h1>
            <p class="tagline">Apresiasi Musisi Lokal Indonesia</p>
        </div>
    </header>

    <main class="container">
        <!-- Hit Counter -->
        <section class="counter-section">
            <p class="counter-text">Jumlah Kunjungan: <span class="counter-value"><?php echo $current_hits; ?></span></p>
        </section>

        <!-- Band Roster -->
        <section class="band-roster">
            <h2>Roster Band Indie Terbaik</h2>
            <p class="section-description">Daftarkan band favorit kamu dan vote untuk musisi lokal terbaik minggu ini!</p>
            
            <div class="bands-grid">
                <?php foreach($bands as $band): ?>
                <article class="band-card" id="band-<?php echo $band['id']; ?>">
                    <div class="band-image">
                        <img src="<?php echo $band['foto']; ?>" alt="<?php echo htmlspecialchars($band['nama']); ?>">
                    </div>
                    <div class="band-info">
                        <h3 class="band-name"><?php echo htmlspecialchars($band['nama']); ?></h3>
                        <p class="band-genre">
                            <span class="label">Genre:</span>
                            <span class="value"><?php echo htmlspecialchars($band['genre']); ?></span>
                        </p>
                        <p class="band-city">
                            <span class="label">Asal Kota:</span>
                            <span class="value"><?php echo htmlspecialchars($band['kota']); ?></span>
                        </p>
                        <p class="band-song">
                            <span class="label">Lagu Andalan:</span>
                            <span class="value"><?php echo htmlspecialchars($band['lagu_andalan']); ?></span>
                        </p>
                        <p class="band-description"><?php echo htmlspecialchars($band['deskripsi']); ?></p>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Voting Form -->
        <section class="voting-section">
            <h2>Berikan Suara untuk Band Favoritmu</h2>
            <p class="section-description">Voting dilakukan setiap minggu. Pilih band favorit dan berikan alasan mengapa kamu menyukai mereka!</p>
            
            <form id="voting-form" class="voting-form">
                <div id="error-container" class="error-container" style="display: none;"></div>

                <!-- Nama Voter -->
                <div class="form-group">
                    <label for="nama-voter">Nama Voter <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="nama-voter" 
                        name="nama_voter" 
                        placeholder="Masukkan nama lengkap kamu"
                        required
                    >
                    <span class="error-message" id="error-nama"></span>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="contoh@email.com"
                        required
                    >
                    <span class="error-message" id="error-email"></span>
                </div>

                <!-- Pilihan Band -->
                <div class="form-group">
                    <label for="pilihan-band">Pilihan Band Favorit <span class="required">*</span></label>
                    <select id="pilihan-band" name="pilihan_band" required>
                        <option value="">-- Pilih Band --</option>
                        <?php foreach($bands as $band): ?>
                        <option value="<?php echo $band['id']; ?>">
                            <?php echo htmlspecialchars($band['nama'] . ' - ' . $band['genre']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="error-message" id="error-band"></span>
                </div>

                <!-- Alasan Memilih -->
                <div class="form-group">
                    <label for="alasan">Alasan Memilih <span class="required">*</span> (Minimal 15 karakter)</label>
                    <textarea 
                        id="alasan" 
                        name="alasan" 
                        placeholder="Tulis alasan mengapa kamu memilih band ini... (min. 15 karakter)"
                        rows="5"
                        required
                    ></textarea>
                    <span class="char-count" id="char-count">0/15 karakter</span>
                    <span class="error-message" id="error-alasan"></span>
                </div>

                <button type="submit" class="btn-submit">Kirim Vote</button>
            </form>

            <div id="success-message" class="success-message" style="display: none;">
                <p>Suara kamu telah berhasil disimpan! Terima kasih telah mendukung musisi lokal!</p>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 IndieSound - Komunitas Musik Indie Indonesia. Dukung Musisi Lokal!</p>
            <p class="footer-note">Laporan Voting disimpan dalam sistem file terpusat</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>