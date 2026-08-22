console.log("Resume Builder Loaded");

document.querySelector("form").addEventListener("submit", function(e){

let mobile=document.querySelector("input[name='mobile']").value;

if(mobile.length!=10){

alert("Enter a valid 10-digit mobile number.");

e.preventDefault();

}

});