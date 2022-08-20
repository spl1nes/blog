<?php
$articles = $this->data['articles'] ?? [];
?>

<div class="content">
    <div class="floater">
        <h1>Blog</h1>

        <?php if ($this->data['lang'] === 'de') : ?>
            <article><blockquote>Blog Artikel sind hauptsächlich in englischer Sprache!</blockquote></article>
        <?php endif; ?>

        <?php foreach ($articles as $article) : ?>
            <section class="summary">
                <h1><?php if ($article['parent'] === 'dev') : ?>
                        <span class="tag dev">dev</span>
                    <?php else : ?>
                        <span class="tag biz">biz</span>
                    <?php endif; ?><a href="/<?= $this->data['lang']; ?>/blog/post/<?= \urlencode($article['path'] ?? '') ?>/<?= \urlencode($article['name'] ?? '') ?>"><?= $article['headline'] ?? '' ?></a></h1>
                <p><?= \substr($article['summary'] ?? '', 0, 750) ?> ... (<a href="/<?= $this->data['lang']; ?>/blog/post/<?= \urlencode($article['path'] ?? '') ?>/<?= \urlencode($article['name'] ?? '') ?>"><?= $this->data['l11n']['more']; ?></a>)</p>
            </section>
        <?php endforeach; ?>
    </div>
</div>