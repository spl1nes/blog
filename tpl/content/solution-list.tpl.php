<?php
$solutions = $this->data['solutions'] ?? [];
?>

<div class="content">
    <div class="floater">
        <h1><?= $this->data['l11n']['Solutions']; ?></h1>
        <?php foreach ($solutions as $solution) : ?>
            <section class="box preview">
                <h1><?php if (\stripos('/' . ($solution['path'] ?? '') . '/', '/ongoing/') !== false) : ?>
                        <span class="tag dev">development</span>
                    <?php else : ?>
                        <span class="tag biz">finished</span>
                    <?php endif; ?><a href="/<?= $this->data['lang']; ?>/blog/post/<?= \urlencode($solution['path'] ?? '') ?>/<?= \urlencode($solution['name'] ?? '') ?>"><?= $solution['headline'] ?? '' ?></a></h1>
                <p><?= \substr($solution['summary'] ?? '', 0, 750) ?> ... (<a href="/<?= $this->data['lang']; ?>/blog/post/<?= \urlencode($solution['path'] ?? '') ?>/<?= \urlencode($solution['name'] ?? '') ?>"><?= $this->data['l11n']['more']; ?></a>)</p>
            </section>
        <?php endforeach; ?>
    </div>
</div>