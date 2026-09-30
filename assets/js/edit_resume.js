document.addEventListener("DOMContentLoaded", function () {
    const editForm = document.querySelector("form");
    if (editForm) {
        editForm.addEventListener("submit", function (e) {
            const mobileInput = editForm.querySelector("input[name='mobile']");
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