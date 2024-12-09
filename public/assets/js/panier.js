function updateQuantity(idProduit, delta) {
	// Envoie une requête GET à la méthode modifierPanier
	window.location.href = `/panier/modifier/${idProduit}/${delta}`;
}

function updateQuantityGamme(idGamme, delta) {
	// Envoie une requête GET à la méthode modifierPanier
	window.location.href = `/panier/modifierGamme/${idGamme}/${delta}`;
}


function retirerProduit(idProduit) {
	// Envoie une requête GET à la méthode modifierPanier
	window.location.href = `/panier/retirer/${idProduit}`;
}