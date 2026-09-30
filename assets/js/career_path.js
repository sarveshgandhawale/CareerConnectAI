document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("careerSearch");
    const filterButtons = document.querySelectorAll(".filter-btn");
    const careerCards = document.querySelectorAll(".career-card");

    if (!searchInput && filterButtons.length === 0 && careerCards.length === 0) {
        return;
    }

    let selectedCategory = "all";

    function filterCareers() {
        const searchValue = searchInput ? searchInput.value.toLowerCase().trim() : "";

        careerCards.forEach(card => {
            const category = card.dataset.category || "";
            const name = (card.dataset.name || card.innerText || "").toLowerCase();

            const categoryMatch = selectedCategory === "all" || category === selectedCategory;
            const searchMatch = searchValue === "" || name.includes(searchValue);

            if (categoryMatch && searchMatch) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    }

    filterButtons.forEach(button => {
        button.addEventListener("click", () => {
            filterButtons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");
            selectedCategory = button.dataset.category || "all";
            filterCareers();
        });
    });

    if (searchInput) {
        searchInput.addEventListener("input", filterCareers);
    }
});