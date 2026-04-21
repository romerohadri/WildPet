function toggleFavorito(id) {
    fetch("add_favorites_to_cart.php", {method: "POST", body: "id=" + id});
    alert("Cambiado en favoritos");
}
