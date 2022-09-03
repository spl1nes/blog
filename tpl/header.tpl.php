<nav>
   <ul>
      <li><a href="/<?= $this->data['lang']; ?>">Main</a>
      <li>|
      <li><a href="/<?= $this->data['lang']; ?>/solution/list"><?= $this->data['l11n']['Solutions']; ?></a>
      <li>|
      <li><a href="/<?= $this->data['lang']; ?>/blog/list">Blog</a>
      <li>|
      <li><a href="/<?= $this->data['lang']; ?>/about"><?= $this->data['l11n']['About']; ?></a>
      <li>|
      <li><a href="/<?= $this->data['lang'] === 'en' ? 'de' : 'en'; ?><?= $this->data['url']['path']; ?>"><?php if ($this->data['lang'] === 'en') : ?><img height="13px" alt="German" src="/tpl/img/flag_de.png"><?php else : ?><img height="13px" alt="German" src="/tpl/img/flag_en.png"><?php endif; ?></a>
   </ul>
</nav>