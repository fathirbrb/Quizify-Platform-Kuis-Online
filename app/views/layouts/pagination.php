<?php
/**
 * Pagination helper - reusable
 * Params: $page (current), $totalPages, $baseUrl (url tanpa &p=)
 */
if ($totalPages <= 1) return;
?>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="<?= $baseUrl ?>&p=<?= $page - 1 ?>" class="page-btn">&#8592; Prev</a>
    <?php else: ?>
        <span class="page-btn disabled">&#8592; Prev</span>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <?php if ($i === (int)$page): ?>
            <span class="page-btn active"><?= $i ?></span>
        <?php else: ?>
            <a href="<?= $baseUrl ?>&p=<?= $i ?>" class="page-btn"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="<?= $baseUrl ?>&p=<?= $page + 1 ?>" class="page-btn">Next &#8594;</a>
    <?php else: ?>
        <span class="page-btn disabled">Next &#8594;</span>
    <?php endif; ?>
</div>
