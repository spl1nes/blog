<?php
$articles = $this->data['articles'] ?? [];
?>

<div class="content">
    <div class="floater">
        <h1>Blog</h1>
        <?php foreach ($articles as $article) : ?>
            <section class="box preview">
                <h1><?php if (\stripos('/' . ($article['path'] ?? '') . '/', '/dev/') !== false) : ?>
                        <span class="tag dev">dev</span>
                    <?php else : ?>
                        <span class="tag biz">biz</span>
                    <?php endif; ?><a href="/<?= $this->data['lang']; ?>/blog/post/<?= \urlencode($article['path'] ?? '') ?>/<?= \urlencode($article['name'] ?? '') ?>"><?= $article['headline'] ?? '' ?></a></h1>
                <p><?= \substr($article['summary'] ?? '', 0, 750) ?> ... (<a href="/<?= $this->data['lang']; ?>/blog/post/<?= \urlencode($article['path'] ?? '') ?>/<?= \urlencode($article['name'] ?? '') ?>"><?= $this->data['l11n']['more']; ?></a>)</p>
            </section>
        <?php endforeach; ?>
    </div>
</div>