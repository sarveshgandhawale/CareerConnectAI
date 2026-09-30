function validateForm() {
    const mobileElem = document.getElementById("mobile");
    if (!mobileElem) return true;

    const mobile = mobileElem.value.trim();
    if (mobile.length !== 10 || !/^\d{10}$/.test(mobile)) {
        alert("Please enter a valid 10-digit mobile number.");
        mobileElem.focus();
        return false;
    }
    return true;
}