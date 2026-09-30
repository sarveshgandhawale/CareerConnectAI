
/* =========================================
   HOW IT WORKS
========================================= */

document.addEventListener("DOMContentLoaded", function () {

    const heroButton = document.querySelector(".hero-btn");

    if (heroButton) {

        heroButton.addEventListener("click", function (event) {

            const target = document.querySelector("#journey");

            if (target) {

                event.preventDefault();

                target.scrollIntoView({
                    behavior: "smooth"
                });

            }

        });

    }


    /* =====================================
       CARD REVEAL ANIMATION
    ===================================== */

    const cards = document.querySelectorAll(".journey-card");

    const observer = new IntersectionObserver(
        function (entries) {

            entries.forEach(function (entry) {

                if (entry.isIntersecting) {

                    entry.target.style.opacity = "1";
                    entry.target.style.transform = "translateY(0)";

                    observer.unobserve(entry.target);

                }

            });

        },
        {
            threshold: 0.15
        }
    );


    cards.forEach(function (card) {

        card.style.opacity = "0";
        card.style.transform = "translateY(30px)";
        card.style.transition = "opacity .6s ease, transform .6s ease";

        observer.observe(card);

    });

});

