console.log("Dashboard Loaded");

const cards=document.querySelectorAll(".dashboard-card");

cards.forEach(card=>{

card.addEventListener("mouseover",function(){

this.style.boxShadow="0 10px 25px rgba(0,0,0,.25)";

});

card.addEventListener("mouseout",function(){

this.style.boxShadow="0 5px 15px rgba(0,0,0,.15)";

});

});