const FAVORITES_KEY = "wildpet_favorites_v1";

function loadFavorites() {
  try {
    const raw = localStorage.getItem(FAVORITES_KEY);
    return raw ? JSON.parse(raw) : {};
  } catch (e) {
    return {};
  }
}

function saveFavorites(data) {
  localStorage.setItem(FAVORITES_KEY, JSON.stringify(data));
}

function isFavorite(productId) {
  const favorites = loadFavorites();
  return !!favorites[String(productId)];
}

function setFavButtonState(button, active) {
  const icon = button.querySelector("i");
  button.classList.toggle("active", active);
  if (icon) {
    icon.classList.toggle("fa-regular", !active);
    icon.classList.toggle("fa-solid", active);
  }
}

function toggleFavorite(product) {
  const favorites = loadFavorites();
  const key = String(product.id);
  if (favorites[key]) {
    delete favorites[key];
  } else {
    favorites[key] = product;
  }
  saveFavorites(favorites);
  return !!favorites[key];
}

function bindFavoriteButtons() {
  const buttons = document.querySelectorAll(".fav-btn");
  buttons.forEach((button) => {
    const product = {
      id: button.dataset.id,
      nombre: button.dataset.nombre || "",
      descripcion: button.dataset.descripcion || "",
      precio: button.dataset.precio || "0",
      imagen: button.dataset.imagen || "",
      url: button.dataset.url || "#"
    };

    setFavButtonState(button, isFavorite(product.id));

    button.addEventListener("click", function (e) {
      e.preventDefault();
      const active = toggleFavorite(product);
      setFavButtonState(button, active);
    });
  });
}

function renderFavoritesGrid(gridSelector, emptySelector) {
  const grid = document.querySelector(gridSelector);
  const empty = document.querySelector(emptySelector);
  if (!grid) return;

  const favorites = Object.values(loadFavorites());
  if (favorites.length === 0) {
    grid.innerHTML = "";
    if (empty) empty.style.display = "block";
    return;
  }

  if (empty) empty.style.display = "none";
  grid.innerHTML = favorites.map((p) => {
    const price = Number(p.precio || 0).toFixed(2);
    return `
      <article class="card">
        <div class="card-img">
          <img src="${p.imagen}" alt="${p.nombre}">
        </div>
        <div class="card-body">
          <h3>${p.nombre}</h3>
          <p class="desc">${p.descripcion}</p>
          <p class="price">€${price}</p>
          <div class="actions">
            <a class="btn" href="${p.url}">Ver producto</a>
            <button class="fav-btn active" aria-label="Quitar de favoritos"
              data-id="${p.id}"
              data-nombre="${p.nombre}"
              data-descripcion="${p.descripcion}"
              data-precio="${p.precio}"
              data-imagen="${p.imagen}"
              data-url="${p.url}">
              <i class="fa-solid fa-heart"></i>
            </button>
          </div>
        </div>
      </article>
    `;
  }).join("");

  bindFavoriteButtons();
}
