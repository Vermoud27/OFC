<footer>
    <div
        style="display: flex; justify-content: space-between; flex-wrap: wrap; max-width: 1400px; align-items: center; gap: 20px;">
        <!-- Section Gauche -->
        <div style="flex: 1; min-width: 50px; text-align: left;">
            <ul style="list-style-type: none; padding: 0; margin: 0;">
                <li><a href="<?= base_url('ControllerOFC') ?>" class="link-white">Accueil</a></li>
                <li><a href="<?= base_url('navbar/entreprise#info') ?>" class="link-white">À propos</a></li>
                <li><a href="#" >Blog</a></li>
                <li><a href="/faq" >FAQ</a></li>
                <li><a href="<?= base_url('PanierController') ?>" class="link-white">Panier</a></li>
                <li><a href="<?= base_url('navbar/entreprise#contact') ?>" class="link-white">Contact</a></li>
            </ul>
        </div>

        <!-- Section Milieu -->
        <div style="flex: 1; min-width: 200px; text-align: center;">
            <form>
                <p>S'inscrire à la newsletter</p>
                <input type="email" placeholder="E-mail"
                    style="padding: 5px; width: 70%; border: none; border-radius: 10px;">
                <button class="signup" type="submit"> S'inscrire </button>
            </form>
        </div>

         <!-- Section Logo -->
        <div class="social-logo" style="flex: 2; justify-content: center; align-items: center;">
            <a href="https://es.pinterest.com/ofcnaturel/" target="_blank" title="Pinterest OFC Naturel" class="pinterest"></a>
            <a href="https://www.tiktok.com/@ofc.naturel" target="_blank" title="TikTok OFC Naturel" class="tiktok"></a>
            <a href="https://www.instagram.com/ofcnaturel/" target="_blank" title="Instagram OFC Naturel" class="instagram"></a>
            <a href="https://www.facebook.com/ofcnaturel/" target="_blank" title="Facebook OFC Naturel" class="facebook"></a>
        </div>

        <!-- Section Droite -->
        <div style="flex: 1; min-width: 200px; text-align: right;">
            <ul style="list-style-type: none; padding: 0; margin: 0;">
                <li><a href="<?= base_url('/mentionslegales') ?>" >Mentions légales</a></li>
                <li><a href="<?= base_url('/polconf') ?>">Politique de confidentialité</a></li>
                <li><a href="<?= base_url('/polremb') ?>">Politique de retour et remboursement</a></li>
                <li><a href="<?= base_url('/polcook') ?>">Politique de cookies</a></li>
                <li><a href="<?= base_url('/rgpd') ?>">Protection des données et RGPD</a></li>
                <li><a href="<?= base_url('/condutil') ?>">Conditions générales d'utilisation</a></li>
                <li><a href="<?= base_url('/condvente') ?>">Conditions générales de vente</a></li>
            </ul>
        </div>
    </div>

    <!-- Copyright -->
    <div
        style="display: flex; justify-content: flex-end; max-width: 2500px; margin: auto; font-size: 8px; margin-top: 10px;">
        © 2024 OFC Naturel. Tous droits réservés.</div>
</footer>