<header class="quiz-header">
    <div class="quiz-header-left">
        <h3>
            <?= htmlspecialchars($kuis['nama_kuis'] ?? 'Kuis') ?>
        </h3>
        <p>
            <?= htmlspecialchars($kuis['nama_matkul'] ?? '') ?> ·
            <?= (int) ($kuis['jumlah_soal'] ?? 0) ?> soal
        </p>
    </div>
    <div class="quiz-header-right">
        <div class="timer-box">
            <span class="timer-icon">⏱</span>
            <?= (int) ($kuis['durasi'] ?? 30) ?>:00
        </div>
        <a href="index.php?page=kuis-tersedia" class="btn btn-secondary btn-sm">Simpan & Keluar</a>
    </div>
</header>

<?php if (empty($soalList)): ?>
    <div style="padding:2rem;">
        <p class="text-muted">Soal belum tersedia untuk kuis ini.</p>
        <a href="index.php?page=kuis-tersedia" class="btn btn-secondary" style="margin-top:1rem">Kembali</a>
    </div>
<?php else: ?>

    <div class="quiz-layout">

        <div class="question-card fade-in">
            <?php $soal = $soalList[0]; ?>
            <div class="question-meta">
                <span class="question-number">Soal 1 dari
                    <?= count($soalList) ?>
                </span>
                <button class="btn-flag">Tandai</button>
            </div>
            <div class="question-body">
                <p class="question-text">
                    <?= htmlspecialchars($soal['pertanyaan'] ?? '') ?>
                </p>
                <div class="options-list">
                    <?php
                    $huruf = ['A', 'B', 'C', 'D'];
                    $opsi = [
                        $soal['opsi_a'] ?? '',
                        $soal['opsi_b'] ?? '',
                        $soal['opsi_c'] ?? '',
                        $soal['opsi_d'] ?? '',
                    ];
                    foreach ($opsi as $i => $teks):
                        if (!$teks)
                            continue;
                        ?>
                        <div class="option-item">
                            <div class="option-letter">
                                <?= $huruf[$i] ?>
                            </div>
                            <span class="option-text">
                                <?= htmlspecialchars($teks) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="question-footer">
                <div class="question-nav">
                    <button class="btn btn-secondary btn-sm">← Sebelumnya</button>
                    <button class="btn btn-secondary btn-sm">Selanjutnya →</button>
                </div>
            </div>
        </div>

        <div class="quiz-sidebar">
            <div class="quiz-panel">
                <div class="quiz-panel-title">Navigasi Soal</div>
                <div class="question-grid">
                    <?php foreach ($soalList as $i => $s): ?>
                        <div class="q-dot <?= $i === 0 ? 'current' : '' ?>">
                            <?= $i + 1 ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="grid-legend">
                    <div class="legend-item">
                        <div class="legend-dot ld-current"></div> Sedang dikerjakan
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot ld-answered"></div> Sudah dijawab
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot ld-flagged"></div> Ditandai
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot ld-unanswered"></div> Belum dijawab
                    </div>
                </div>
            </div>

            <div class="quiz-panel submit-section">
                <div class="progress-summary">
                    <span>Terjawab</span>
                    <strong>0 /
                        <?= count($soalList) ?>
                    </strong>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 0%"></div>
                </div>
                <button class="btn btn-primary btn-full" style="margin-top:1rem">Kumpulkan Jawaban</button>
                <p>Pastikan semua soal sudah dijawab sebelum mengumpulkan.</p>
            </div>
        </div>

    </div>
<?php endif; ?>