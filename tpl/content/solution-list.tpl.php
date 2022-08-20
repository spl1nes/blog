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
            <section class="preview" itemtype="https://schema.org/Product" itemscope>
                <?php if ($count % 2 === 0) : ?>
                <div class="splash" itemprop="image">
                    <img alt="Splash" src="/content/solutions/<?= $solution['parent']; ?>/img/<?= $solution['splash'] ?? '' ?>">
                </div>
                <?php endif; ?>
                <div class="product">
                    <h1 itemprop="name"><?= $solution['headline'] ?? '' ?></h1>
                    <h2 itemprop="description"><?= $solution['summary'] ?? '' ?></h2>
                    <?php if ($solution['parent'] === 'finished') : ?>
                        <span class="price" itemprop="offers" itemtype="https://schema.org/Offer" itemscope><?= $this->data['l11n']['Price']; ?>: <span itemprop="price"><?= $solution['price'] ?? '' ?></span></span>
                    <?php else: ?>
                        <span class="price" class="price" itemprop="offers" itemtype="https://schema.org/Offer" itemscope><span itemprop="price"><?= $this->data['l11n']['InDevelopment']; ?></span></span>
                    <?php endif; ?>
                    <div class="button-list">
                        <?php if ($solution['parent'] === 'finished') : ?>
                            <a class="button" href="#"><?= $this->data['l11n']['Buy']; ?></a>
                        <?php endif; ?>
                        <a class="button" href="/<?= $this->data['lang']; ?>/solution/item/<?= \urlencode($solution['path'] ?? '') ?>/<?= \urlencode(\substr($solution['name'] ?? '', 0, -3)) ?>">Infos</a>
                    </div>
                </div>
                <?php if ($count % 2 !== 0) : ?>
                <div class="splash" itemprop="image">
                    <img alt="Splash" src="/content/solutions/<?= $solution['parent']; ?>/img/<?= $solution['splash'] ?? '' ?>">
                </div>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>
    </div>
</div>
