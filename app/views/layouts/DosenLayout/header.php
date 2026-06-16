
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= isset($pageTitle) ? $pageTitle . ' — Quizify Dosen' : 'Quizify Dosen' ?></title>
    <link rel="stylesheet" href="public/css/style.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="public/css/admin.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="public/css/dosen.css?v=<?= time() ?>" />
    <script>
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    </script>
</head>

<body>
    <div class="layout">
