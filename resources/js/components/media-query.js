// Media Query
function handleScreenChange(x) {
    if (mediaQuery.matches) { // If media query matches
        document.querySelector("#sidebar").classList.toggle("collapsed");
        document.querySelector("#main").classList.toggle("collapsed");
    } else {
        document.querySelector("#sidebar").classList.remove("collapsed");
        document.querySelector("#main").classList.remove("collapsed");
    }
}
  
// Create a MediaQueryList object
const mediaQuery = window.matchMedia("(max-width: 1000px)")
  
// Call listener function at run time
    handleScreenChange(mediaQuery);
  
// Attach listener function on state changes
mediaQuery.addEventListener("change", function() {
    handleScreenChange(mediaQuery);
});