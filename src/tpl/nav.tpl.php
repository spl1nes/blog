<nav>
    <div class="floater">
        <input type="checkbox" id="navToggle"> 
        <ul>
            <li><a href="/"<?= $this->request->getUri()->getPathElement(0) === '' ? ' class="active"' : '' ?>>Home</a></li>
            <li><a href="/business"<?= $this->request->getUri()->getPathElement(0) === 'business' ? ' class="active"' : '' ?>>Business</a></li>
            <li><a href="/dev"<?= $this->request->getUri()->getPathElement(0) === 'dev' ? ' class="active"' : '' ?>>Development</a></li>
            <li class="right"><a href="/cv"<?= $this->request->getUri()->getPathElement(0) === 'cv' ? ' class="active"' : '' ?>>CV</a></li>
            <li class="right icon"><label for="navToggle"><i class="fa fa-bars"></i></label></li>
        </ul>
    </div>
</nav>