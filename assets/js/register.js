function showPassword() {
    const pass = document.getElementById("password");
    if (pass) {
        pass.type = pass.type === "password" ? "text" : "password";
    }
}

function validateForm() {
    const passElem = document.getElementById("password");
    const confirmElem = document.getElementById("confirm_password");
    const mobileElem = document.getElementById("mobile");

    if (!passElem || !confirmElem || !mobileElem) return true;

    const pass = passElem.value;
    const confirm = confirmElem.value;
    const mobile = mobileElem.value.trim();

    if (mobile.length !== 10 || !/^\d{10}$/.test(mobile)) {
        alert("Please enter a valid 10-digit mobile number.");
        mobileElem.focus();
        return false;
    }

    if (pass !== confirm) {
        alert("Passwords do not match.");
        confirmElem.focus();
        return false;
    }

    if (pass.length < 6) {
        alert("Password must be at least 6 characters long.");
        passElem.focus();
        return false;
    }

    return true;
}