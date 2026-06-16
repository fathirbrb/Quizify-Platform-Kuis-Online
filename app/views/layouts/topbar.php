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
        <a href="index.php?page=profile" class="avatar-wrap" title="Profile Settings" style="text-decoration:none;">
            <div class="avatar"><?= $initials ?></div>
            <span class="avatar-name"><?= htmlspecialchars($adminNama) ?></span>
        </a>
        <a href="#modal-logout" class="btn-logout" title="Keluar">&#x2192; Logout</a>
    </div>
</header>