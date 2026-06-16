<?php
$durasiMenit = max(1, (int) ($kuis['durasi'] ?? 30));
$mulaiPengerjaan = $pengerjaan['mulai'] ?? date('Y-m-d H:i:s');
$mulaiTimestamp = strtotime($mulaiPengerjaan) ?: time();
$deadlineTimestamp = $mulaiTimestamp + ($durasiMenit * 60);
$sisaDetik = isset($sisaDetik) ? max(0, (int) $sisaDetik) : max(0, $deadlineTimestamp - time());
?>

<section class="page-content">
    <div class="section-header">
        <div>
            <h2><?= htmlspecialchars($kuis['nama_kuis'] ?? 'Kuis') ?></h2>
            <p class="text-muted">
                <?= htmlspecialchars($kuis['nama_matkul'] ?? '') ?> ·
                <?= htmlspecialchars($kuis['nama_kelas'] ?? '') ?> ·
                <?= count($soalList) ?> soal
            </p>
        </div>
        <div class="quiz-actions">
            <span
                class="timer-box"
                id="quiz-timer"
                data-deadline="<?= htmlspecialchars((string) ((time() + $sisaDetik) * 1000)) ?>"
                data-remaining="<?= htmlspecialchars((string) $sisaDetik) ?>"
            >
                <?= sprintf('%02d:%02d', intdiv($sisaDetik, 60), $sisaDetik % 60) ?>
            </span>
            <button type="submit" name="mode" value="simpan" class="btn btn-secondary btn-sm" form="form-kerjakan">Simpan & Keluar</button>
        </div>
    </div>

<?php if (empty($soalList)): ?>
    <div class="card">
        <p class="text-muted">Soal belum tersedia untuk kuis ini.</p>
        <a href="index.php?page=kuis-tersedia" class="btn btn-secondary" style="margin-top:1rem">Kembali</a>
    </div>
<?php else: ?>

    <form id="form-kerjakan" method="POST" action="index.php?page=kerjakan&id=<?= urlencode($kuis['id'] ?? 1) ?>">
        <input type="hidden" name="kuis_id" value="<?= htmlspecialchars($kuis['id'] ?? 1) ?>">

        <div class="quiz-layout">

            <div class="question-card card">
                <?php foreach ($soalList as $index => $soal): ?>
                    <div class="question-body" style="<?= $index > 0 ? 'border-top:1px solid var(--gray-200);padding-top:1rem;margin-top:1rem;' : '' ?>">
                        <div class="question-meta">
                            <span class="question-number">Soal <?= $index + 1 ?> dari <?= count($soalList) ?></span>
                        </div>
                        <p class="question-text">
                            <?= htmlspecialchars($soal['pertanyaan'] ?? '') ?>
                        </p>
                        <div class="options-list">
                            <?php
                            $opsi = [
                                'A' => $soal['opsi_a'] ?? $soal['a'] ?? '',
                                'B' => $soal['opsi_b'] ?? $soal['b'] ?? '',
                                'C' => $soal['opsi_c'] ?? $soal['c'] ?? '',
                                'D' => $soal['opsi_d'] ?? $soal['d'] ?? '',
                            ];
                            foreach ($opsi as $huruf => $teks):
                                if (!$teks) {
                                    continue;
                                }
                            ?>
                                <label class="option-item">
                                    <input type="radio" name="jawaban[<?= htmlspecialchars($soal['id']) ?>]" value="<?= $huruf ?>" <?= (($jawabanTersimpan[$soal['id']] ?? '') === $huruf) ? 'checked' : '' ?>>
                                    <div class="option-letter"><?= $huruf ?></div>
                                    <span class="option-text"><?= htmlspecialchars($teks) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="quiz-sidebar">
                <div class="quiz-panel card">
                    <div class="quiz-panel-title">Navigasi Soal</div>
                    <div class="question-grid">
                        <?php foreach ($soalList as $i => $s): ?>
                            <div class="q-dot <?= $i === 0 ? 'current' : '' ?>">
                                <?= $i + 1 ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="quiz-panel card submit-section">
                    <div class="progress-summary">
                        <span>Total Soal</span>
                        <strong><?= count($soalList) ?></strong>
                    </div>
                    <button type="submit" name="mode" value="kumpulkan" class="btn btn-primary btn-full" style="margin-top:1rem">Kumpulkan Jawaban</button>
                    <p class="text-muted" style="margin-top:0.75rem;">Nilai akan langsung masuk ke riwayat nilai dan laporan dosen.</p>
                </div>
            </div>

        </div>
    </form>
<?php endif; ?>
</section>

<?php if (!empty($soalList)): ?>
<script>
(() => {
    const timer = document.getElementById('quiz-timer');
    const form = document.getElementById('form-kerjakan');
    if (!timer || !form) {
        return;
    }

    const remainingFromServer = Number(timer.dataset.remaining || 0);
    const deadline = Date.now() + (remainingFromServer * 1000);
    let submitted = false;

    const ensureMode = () => {
        let mode = form.querySelector('input[name="mode"][type="hidden"]');
        if (!mode) {
            mode = document.createElement('input');
            mode.type = 'hidden';
            mode.name = 'mode';
            form.appendChild(mode);
        }
        mode.value = 'kumpulkan';
    };

    const pad = (value) => String(value).padStart(2, '0');

    let autosaveTimer = null;
    const autosave = () => {
        window.clearTimeout(autosaveTimer);
        autosaveTimer = window.setTimeout(async () => {
            try {
                const data = new FormData(form);
                await fetch('index.php?page=mahasiswa-autosave-kuis', {
                    method: 'POST',
                    body: data,
                    headers: { 'Accept': 'application/json' },
                    cache: 'no-store'
                });
            } catch (error) {
                // Autosave boleh gagal sesaat; submit akhir tetap menyimpan jawaban.
            }
        }, 250);
    };

    const submitWhenExpired = () => {
        if (submitted) {
            return;
        }

        submitted = true;
        ensureMode();
        timer.textContent = '00:00';
        timer.classList.add('timer-expired');
        form.submit();
    };

    const tick = () => {
        const remaining = Math.max(0, Math.floor((deadline - Date.now()) / 1000));
        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;

        timer.textContent = `${pad(minutes)}:${pad(seconds)}`;
        timer.classList.toggle('timer-warning', remaining > 0 && remaining <= 60);

        if (remaining <= 0) {
            submitWhenExpired();
        }
    };

    window.setInterval(tick, 1000);
    form.querySelectorAll('input[type="radio"]').forEach((input) => {
        input.addEventListener('change', autosave);
    });
    tick();
})();
</script>
<?php endif; ?>
