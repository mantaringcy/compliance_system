// Sidebar Toggle
const toggler = document.querySelector(".main-btn");
toggler.addEventListener("click", function(){
    document.querySelector("#sidebar").classList.toggle("collapsed");
    document.querySelector("#main").classList.toggle("collapsed");
});