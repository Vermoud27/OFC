<header class="navbar">
    <div class="left-section">
        <a href="<?= base_url('navbar/entreprise#contact') ?>" title="Nous contacter">
            <p> 📞 </p>
        </a>
        <input type="text" placeholder="Rechercher..." class="search-bar">
        <p> 🔍 </p>
    </div>
    <div class="center-section">
        <a href="/ControllerOFC"><img src="/assets/img/logo/logo_dore.png" alt="Logo OFC Naturel" class="logo"></a>
    </div>


    <div class="right-section">
    <p> 🇫🇷 </p>
    <a href="<?= base_url('PanierController') ?>">
        <p> 🛒 </p>
    </a>

    <?php if (session()->get('isLoggedIn')): ?>
        <a href="<?= base_url('/profile') ?>" class="profile-icon-logged-in"></a>
    <?php else: ?>
        <a href="<?= base_url('/signin') ?>" class="profile-icon"></a>
    <?php endif; ?>
</div>


</header>
<nav class="menu">
    <ul>
        <li><a href="<?= base_url('produits') ?>" class="link-white">Produits</a></li>
        <li><a href="#">Gammes</a></li>
        <li><a href="#">Huiles</a></li>
        <li><a href="#">Soins peau au détail</a></li>
        <li><a href="#">Soins capillaires</a></li>
        <li><a href="#">Autres</a></li>
        <li><a href="/navbar/entreprise">L’entreprise</a></li>
    </ul>
</nav>