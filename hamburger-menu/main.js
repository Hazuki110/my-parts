const hamburger = document.querySelector(".hamburger");
const nav = document.querySelector(".header__nav");
const navLinks = nav.querySelectorAll("a");

function setMenu(isOpen) {
  hamburger.classList.toggle("is-open", isOpen);
  nav.classList.toggle("is-open", isOpen);
  document.body.classList.toggle("is-lock", isOpen);
  hamburger.setAttribute("aria-expanded", String(isOpen));
  hamburger.setAttribute("aria-label", isOpen ? "メニューを閉じる" : "メニューを開く");
}

hamburger.addEventListener("click", () => {
  setMenu(!hamburger.classList.contains("is-open"));
});

navLinks.forEach((link) => {
  link.addEventListener("click", () => setMenu(false));
});