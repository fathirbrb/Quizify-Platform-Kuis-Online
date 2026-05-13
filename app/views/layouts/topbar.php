<?php

$pageTitle = isset($pageTitle) ? $pageTitle : 'Dashboard Admin';
$pageSubtitle = isset($pageSubtitle) ? $pageSubtitle : '';

$adminNama = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Administrator';

$words = explode(' ', $adminNama);
$initials = strtoupper(substr($words[0], 0, 1));
if (isset($words[1])) {
    $initials .= strtoupper(substr($words[1], 0, 1));
}
?>

<header class="topbar">
    <div class="topbar-left">
        <div class="topbar-title"><?= htmlspecialchars($pageTitle) ?></div>
        <?php if ($pageSubtitle): ?>
            <p class="text-muted"><?= htmlspecialchars($pageSubtitle) ?></p>
        <?php endif; ?>
    </div>

    <div class="topbar-right">
        <span class="role-chip">&#9670; Admin</span>
        <div class="avatar-wrap">
            <div class="avatar"><?= $initials ?></div>
            <span class="avatar-name"><?= htmlspecialchars($adminNama) ?></span>
        </div>
    </div>
</header>