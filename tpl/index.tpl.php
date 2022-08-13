<!DOCTYPE HTML>
<html lang="<?= $this->data['lang'] ?? 'en'; ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#262626">
        <meta name="msapplication-navbutton-color" content="#262626">
        <meta name="apple-mobile-web-app-status-bar-style" content="#262626">

        <base href="<?= $this->data['base'] ?? ''; ?>/">

        <style><?= \preg_replace('!\s+!', ' ', \file_get_contents(__DIR__ . '/css/small.css')); ?></style>

        <link rel="manifest" href="manifest.json">
        <link rel="shortcut icon" href="tpl/img/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="tpl/css/styles.css">
        <link rel="stylesheet" type="text/css" href="resources/fonts/Roboto/roboto.css">
    </head>
    <body>
        <?php include __DIR__ . '/header.tpl.php'; ?>
        <?php include __DIR__ . '/main.tpl.php'; ?>
        <?php include __DIR__ . '/footer.tpl.php'; ?>
    </body>
</html>
