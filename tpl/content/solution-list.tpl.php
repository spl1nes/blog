<?php
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
                    <img alt="Splash" src="/content/solutions/<?= $solution['parent']; ?>/img/<?= $solution['splash'] ?? '' ?>">
                </div>
                <?php endif; ?>
                <div class="product">
                    <h1><?= $solution['headline'] ?? '' ?></h1>
                    <h2><?= $solution['summary'] ?? '' ?></h2>
                    <?php if ($solution['parent'] === 'finished') : ?>
                        <span class="price"><?= $this->data['l11n']['Price']; ?>: <?= $solution['price'] ?? '' ?></span>
                    <?php else: ?>
                        <span class="price"><?= $this->data['l11n']['InDevelopment']; ?></span>
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
                    <img alt="Splash" src="/content/solutions/<?= $solution['parent']; ?>/img/<?= $solution['splash'] ?? '' ?>">
                </div>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>
    </div>
</div>
