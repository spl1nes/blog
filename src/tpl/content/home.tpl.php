<?php
$articles = $this->getData('articles');
?>

<?php foreach ($articles as $article) : ?>
<section class="box preview">
    <h1><a href="/article/<?= \urlencode($article['path'] ?? '') ?>/<?= \urlencode($article['name'] ?? '') ?>"><?= $article['headline'] ?? '' ?></a></h1>
    <p><?= \substr($article['summary'] ?? '', 0, 250) ?> ... (<a href="/article/<?= \urlencode($article['path'] ?? '') ?>/<?= \urlencode($article['name'] ?? '') ?>">more</a>)</p>
</section>
<?php endforeach; ?>
