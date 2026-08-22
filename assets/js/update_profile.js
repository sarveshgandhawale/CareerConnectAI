// Page Loaded
console.log("Update Profile Loaded");

// Mobile Validation
function validateForm(){

let mobile=document.getElementById("mobile").value;

if(mobile.length!=10){

alert("Please Enter Valid Mobile Number");

return false;

}

return true;

}