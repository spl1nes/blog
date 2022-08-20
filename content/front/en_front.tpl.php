<header>
    <div class="floater">
       <img src="tpl/img/logo.png">
        <h1>Jingga</h1>
        <h2>Business solutions done simple.</h2>

        <hr>
    </div>
</header>

<?php
$articles  = $this->data['articles'] ?? [];
$solutions = $this->data['solutions'] ?? [];
?>

<div class="content">
    <div class="floater">
        <h1><?= $this->data['l11n']['Solutions']; ?></h1>

        <?php
        $count = 1;
        foreach ($solutions as $solution) :
            ++$count;
        ?>
            <section class="preview">
                <?php if ($count % 2 === 0) : ?>
                <div class="splash">
                    <img src="/content/solutions/<?= $solution['parent']; ?>/img/<?= $solution['splash'] ?? '' ?>">
                </div>
                <?php endif; ?>
                <div class="product">
                    <h1><?= $solution['headline'] ?? '' ?></h1>
                    <h2><?= $solution['summary'] ?? '' ?></h2>
                    <?php if ($solution['parent'] === 'finished') : ?>
                        <span class="price"><?= $this->data['l11n']['Price']; ?>: <span><?= $solution['price'] ?? '' ?></span></span>
                    <?php endif; ?>
                    <div class="button-list">
                        <?php if ($solution['parent'] === 'finished') : ?>
                            <a class="button" href="#"><?= $this->data['l11n']['Buy']; ?></a>
                        <?php endif; ?>
                        <a class="button" href="/<?= $this->data['lang']; ?>/solution/item/<?= \urlencode($solution['path'] ?? '') ?>/<?= \urlencode(\substr($solution['name'] ?? '', 0, -3)) ?>">Infos</a>
                    </div>
                </div>
                <?php if ($count % 2 !== 0) : ?>
                <div class="splash">
                    <img src="/content/solutions/<?= $solution['parent']; ?>/img/<?= $solution['splash'] ?? '' ?>">
                </div>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>
    </div>
</div>



<div class="content">
    <div class="floater row">
        <h1>Blog</h1>

        <?php foreach ($articles as $article) : ?>
            <section>
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