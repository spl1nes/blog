<?php

use resources\markdown\Markdown;

?>

<div class="content">
    <div class="floater">
        <article><?= Markdown::parse(\file_get_contents($this->content)); ?></article>
    </div>
</div>