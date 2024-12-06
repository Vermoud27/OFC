<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<header class="navbar">
    <div class="left-section">
        <a href="<?= base_url('/navbar/entreprise#contact') ?>" class="phone-icon"></a>
        <input list="liste-produit" type="text" id="product-names" name="product-name" placeholder="Nom du produit" class="search-bar" autocomplete="off">
        <datalist id="liste-produit">
        </datalist> 
        <i id="search-button" onclick="searchProduct()" class="fa-solid fa-magnifying-glass"></i>
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
    document.getElementById('product-names').addEventListener('input', function () {
    
        const query = this.value; // Récupère la valeur du champ de recherche
        const datalist = document.getElementById('liste-produit'); // Référence au datalist

        // Si la requête contient au moins 2 caractères
        fetch(`/test-recherche?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(
                produits => {
                // Vider le datalist avant de le remplir à nouveau
                datalist.innerHTML = '';

                if (produits.length > 0) {
                    produits.forEach(produit => {
                        const option = document.createElement('option');
                        option.value = produit.nom; // Associer le nom du produit comme valeur
                        datalist.appendChild(option); // Ajouter chaque option au datalist
                    });
                }
                console.log(produits);
            })
            .catch(error => console.error('Erreur:', error));
    });

    // Détecte la touche "Entrée" et simule un clic sur le bouton de recherche
    document.getElementById('product-names').addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Empêche le comportement par défaut
            document.getElementById('search-button').click(); // Simule un clic sur le bouton de recherche
        }
    });

    // Fonction de recherche déclenchée par le bouton
    function searchProduct() {
        const query = document.getElementById('product-names').value;
        console.log(`Recherche lancée pour : ${query}`);
        // Ajoutez ici la logique pour gérer la recherche, redirection ou autre action
        window.location.href = `/produits?search=${encodeURIComponent(query)}`;
    }

    

</script>

