console.log("Edit Resume Loaded");

document.querySelector("form").addEventListener("submit", function(e){

let mobile=document.querySelector("input[name='mobile']").value;

if(mobile.length!=10){

alert("Enter Valid Mobile Number");

e.preventDefault();

}

});