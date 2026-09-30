document.addEventListener("DOMContentLoaded", function () {
    const resumeForm = document.querySelector("form");
    if (resumeForm) {
        resumeForm.addEventListener("submit", function (e) {
            const mobileInput = resumeForm.querySelector("input[name='mobile']");
            if (mobileInput) {
                const mobileVal = mobileInput.value.trim();
                if (mobileVal.length !== 10 || !/^\d{10}$/.test(mobileVal)) {
                    alert("Please enter a valid 10-digit mobile number.");
                    mobileInput.focus();
                    e.preventDefault();
                    return false;
                }
            }
        });
    }
});