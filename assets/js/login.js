function showPassword() {
    const pass = document.getElementById("password");
    if (pass) {
        pass.type = pass.type === "password" ? "text" : "password";
    }
}