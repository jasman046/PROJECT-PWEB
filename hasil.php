<?php
$voting_json_file = 'voting_band.json';
$votes = [];

// Baca data dari file JSON jika file ada
if (file_exists($voting_json_file)) {
    $json_content = file_get_contents($voting_json_file);
    if (!empty($json_content)) {
        $decoded = json_decode($json_content, true);
        if (is_array($decoded)) {
            $votes = $decoded;
        }
    }
}

// Mapping ID Band ke Nama Aslinya
$band_names = [
    1 => '.Feast',
    2 => 'Dewa 19',
    3 => 'Jamrud'
];

// Hitung Total Perolehan Suara per Band
$rekap_suara = [1 => 0, 2 => 0, 3 => 0];
foreach ($votes as $v) {
    $b_id = isset($v['band_id']) ? (int)$v['band_id'] : 0;
    if (array_key_exists($b_id, $rekap_suara)) {
        $rekap_suara[$b_id]++;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IndieSound - Hasil Voting</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Style tambahan khusus halaman hasil agar rapi */
        .summary-box {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .summary-card {
            flex: 1;
            min-width: 200px;
            background: #fff;
            padding: 20px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            text-align: center;
        }
        .summary-card h4 { margin-bottom: 5px; color: var(--text-dark); }
        .summary-card p { font-size: 24px; font-weight: bold; color: var(--primary-color); }
        
        .table-container {
            background: #fff;
            padding: 20px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
        }
        th, td {
            border: 1px solid var(--border-color);
            padding: 12px;
            text-align: left;
        }
        th { background-color: #f8f9fa; font-weight: 600; }
        tr:hover { background-color: #fcfcfc; }
        .no-data { text-align: center; color: var(--text-muted); padding: 20px; }
        .btn-back { display: inline-block; margin-bottom: 20px; color: var(--primary-color); text-decoration: none; font-weight: bold; }
        .btn-back:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="logo">IndieSound</h1>
            <p class="tagline">Hasil Perolehan Suara Live</p>
        </div>
    </header>

    <main class="container" style="margin-top: 40px;">
        <a href="index.php" class="btn-back">&larr; Kembali ke Form Voting</a>

        <h2>Ringkasan Hasil Perolehan Suara</h2>
        <div class="summary-box">
            <?php foreach ($band_names as $id => $nama): ?>
                <div class="summary-card">
                    <h4><?php echo $nama; ?></h4>
                    <p><?php echo $rekap_suara[$id]; ?> Suara</p>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Daftar Detail Pemilih</h2>
        <div class="table-container">
            <?php if (empty($votes)): ?>
                <p class="no-data">Belum ada suara yang masuk saat ini.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Waktu</th>
                            <th>Nama Pemilih</th>
                            <th>Pilihan Band</th>
                            <th>Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        // Diurutkan dari yang paling baru masuk (reverse array)
                        foreach (array_reverse($votes) as $vote): 
                            $band_id = isset($vote['band_id']) ? (int)$vote['band_id'] : 0;
                            $nama_band = isset($band_names[$band_id]) ? $band_names[$band_id] : 'Tidak Diketahui';
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($vote['timestamp'] ?? '-'); ?></td>
                                <td><strong><?php echo htmlspecialchars($vote['nama'] ?? '-'); ?></strong></td>
                                <td><span style="color: var(--primary-color); font-weight:600;"><?php echo htmlspecialchars($nama_band); ?></span></td>
                                <td><?php echo nl2br(htmlspecialchars($vote['alasan'] ?? '-')); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 IndieSound - Laporan Real-time Sistem File</p>
        </div>
    </footer>
</body>
</html>