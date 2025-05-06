const btn = document.getElementById("toggleMenu");
const menu = document.getElementById("menu");

btn.addEventListener("click", () => {
if (menu.style.display === "none" || menu.style.display === "") {
    menu.style.display = "block";
} else {
    menu.style.display = "none";
}
});