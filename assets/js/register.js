function showPassword() {

    let pass = document.getElementById("password");

    if (pass.type == "password") {

        pass.type = "text";

    } else {

        pass.type = "password";

    }

}


function validateForm() {

    let pass =
        document.getElementById("password").value;

    let confirm =
        document.getElementById("confirm_password").value;

    let mobile =
        document.getElementById("mobile").value;


    // Check mobile number

    if (mobile.length != 10) {

        alert("Enter 10 digit mobile number");

        return false;

    }


    // Check password

    if (pass != confirm) {

        alert("Passwords do not match");

        return false;

    }


    return true;

}