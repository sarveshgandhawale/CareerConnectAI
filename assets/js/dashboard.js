document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".dashboard-card");
    cards.forEach(card => {
        card.addEventListener("mouseenter", function () {
            this.style.transform = "translateY(-6px)";
        });
        card.addEventListener("mouseleave", function () {
            this.style.transform = "translateY(0)";
        });
    });
});