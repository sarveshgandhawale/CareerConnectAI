document.addEventListener("DOMContentLoaded", function () {
    const careerForm = document.getElementById("careerForm") || document.querySelector("form");
    if (careerForm) {
        careerForm.addEventListener("submit", function () {
            const submitBtn = careerForm.querySelector("button[type='submit']");
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Analyzing with Gemini AI...';
            }
        });
    }
});