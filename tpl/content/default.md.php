<?php

use resources\markdown\Markdown;

$parser = new Markdown();
?>

<div class="content">
    <div class="floater">
        <article><?= $parser->parse(\file_get_contents($this->content)); ?></article>
    </div>
</div>