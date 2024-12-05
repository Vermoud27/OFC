<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<header class="navbar">
    <div class="left-section">
        <a href="<?= base_url('/') ?>" class="phone-icon"></a>
        <input type="text" placeholder="Rechercher..." class="search-bar">
        <p> 🔍 </p>
    </div>
    <div class="center-section">
        <a href="/"><img src="/assets/img/logo/logo_dore.png" alt="Logo OFC Naturel" class="logo"></a>
    </div>


    <div class="right-section">
        <p> 🇫🇷 </p>
        <?php if (session()->get('isLoggedIn')): ?>
            <a href="<?= base_url('/admin/produits') ?>" class="admin-icon">
                <i class="fa fa-cog" aria-hidden="true"></i>
            </a>
        <?php endif; ?>

        <div class="cart-container">
            <a href="/PanierController">
                <i class="fas fa-shopping-cart"></i>
                <span
                    class="cart-count"><?= isset($_COOKIE['panier']) ? array_sum(json_decode($_COOKIE['panier'], true)) : 0 ?></span>
            </a>
        </div>
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
        <li><a href="<?= base_url('produits?categorie=gammes') ?>" class="link-white">Gammes</a></li>
        <li><a href="<?= base_url('produits?categorie=Huiles') ?>" class="link-white">Huiles</a></li>
        <li><a href="<?= base_url('produits?categorie=Soins-peau') ?>" class="link-white">Soins peau au détail</a></li>
        <li><a href="<?= base_url('produits?categorie=Soins-capillaires') ?>" class="link-white">Soins capillaires</a>
        </li>
        <li><a href="<?= base_url('produits?categorie=Autres') ?>" class="link-white">Autres</a></li>
        <li><a href="/navbar/entreprise">L’entreprise</a></li>
    </ul>
</nav>