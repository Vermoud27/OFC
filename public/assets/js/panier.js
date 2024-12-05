function updateQuantity(idProduit, delta) {
	// Envoie une requête GET à la méthode modifierPanier
	window.location.href = `/panier/modifier/${idProduit}/${delta}`;
}