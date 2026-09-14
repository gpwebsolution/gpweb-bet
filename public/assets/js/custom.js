document.addEventListener("DOMContentLoaded", function () {
  // === NAVBAR SCROLL SHADOW ===
  const topNav = document.querySelector(".page__content__navbar");
  window.addEventListener("scroll", function () {
    if (topNav) {
      topNav.classList.toggle("navbar-scrolled", window.scrollY > 10);
    }
  });
});
