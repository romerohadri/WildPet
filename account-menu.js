document.addEventListener("DOMContentLoaded", function () {
  const menus = document.querySelectorAll(".account-menu");
  menus.forEach((menu) => {
    const toggle = menu.querySelector(".account-toggle");
    if (!toggle) return;

    toggle.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      menu.classList.toggle("open");
    });
  });

  document.addEventListener("click", function () {
    menus.forEach((menu) => menu.classList.remove("open"));
  });
});
