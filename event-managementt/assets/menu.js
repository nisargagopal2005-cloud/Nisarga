document.addEventListener("DOMContentLoaded", () => {
  const nav = document.getElementById("mainNav");
  const links = nav.querySelectorAll("a");

  links.forEach(link => {
    link.addEventListener("click", (e) => {
      e.preventDefault(); // stop navigation (remove if you want navigation)
      links.forEach(other => {
        if (other !== link) {
          other.style.display = "none"; // hide others
        }
      });
    });
  });
});
