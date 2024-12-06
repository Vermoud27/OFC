<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<header class="navbar">
    <div class="left-section">
        <a href="<?= base_url('/navbar/entreprise#contact') ?>" class="phone-icon"></a>
        <input type="text" id="product-names" name="product-name" placeholder="Nom du produit" class="search-bar" autocomplete="off">
        <datalist id="product-names">
            <?php if (!empty($produits) && is_array($produits)): ?>
                <?php foreach ($produits as $produit): ?>
                    <option value="<?= esc($produit['nom']); ?>"></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </datalist> <!-- Liste dynamique des produits -->
        <p> 🔍 </p>
    </div>
    <div class="center-section">
        <a href="/"><img src="/assets/img/logo/logo_dore.png" alt="Logo OFC Naturel" class="logo"></a>
    </div>


    <div class="right-section">
        <p> 🇫🇷 </p>
        <?php if (session()->get('role') == 'Admin'): ?>
            <a href="<?= base_url('/admin/produits') ?>" class="admin-icon"></a>
        
        <?php endif; ?>

        <?php if (session()->get('isLoggedIn')): ?>
            <div class="cart-container">
                <a href="/PanierController">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">
                        <?= isset($_COOKIE['panier']) ? array_sum(json_decode($_COOKIE['panier'], true)) : 0 ?>
                    </span>
                </a>
            </div>
        <?php else: ?>
            <div class="cart-container">
                <a href="/PanierController">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">0</span>
                </a>
            </div>
        <?php endif; ?>


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
        <li><a href="<?= base_url('gammes') ?>" class="link-white">Gammes</a></li>
        <li><a href="<?= base_url('produits?categorie=Huiles') ?>" class="link-white">Huiles</a></li>
        <li><a href="<?= base_url('produits?categorie=Soins-peau') ?>" class="link-white">Soins peau au détail</a></li>
        <li><a href="<?= base_url('produits?categorie=Soins-capillaires') ?>" class="link-white">Soins capillaires</a></li>
        <li><a href="<?= base_url('produits?categorie=Autres') ?>" class="link-white">Autres</a></li>
        <li><a href="/navbar/entreprise">L’entreprise</a></li>
    </ul>
</nav>

<script>
document.getElementById('product-name').addEventListener('input', function () {
    const query = this.value;
    const datalist = document.getElementById('product-names');

    // Vérifie si la requête est suffisamment longue pour lancer la recherche
    if (query.length >= 2) {
        fetch('/rechercher-produits', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ query })
        })
        .then(response => response.json())
        .then(produits => {
            datalist.innerHTML = ''; // Vider les options précédentes

            if (produits.length > 0) {
                produits.forEach(produit => {
                    const option = document.createElement('option');
                    option.value = produit.nom; // Utilisez le nom du produit comme valeur
                    datalist.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des produits:', error);
        });
    } else {
        datalist.innerHTML = ''; // Vider le datalist si la saisie est trop courte
    }
});
</script>

