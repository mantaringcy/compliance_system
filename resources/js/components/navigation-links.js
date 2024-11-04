// Active Links
const activePage = window.location.pathname;

const navLinks = document.querySelectorAll('.sidebar .sidebar-nav a').forEach(link => {
    if (link.href.includes(`${activePage}`)) {
        link.classList.add('sidebar-item-active');
    }
})

const subLinksCollapse = document.getElementById('profile-list');
const subLinks = document.querySelectorAll('.sidebar .profile a').forEach(link => {
    if (link.href.includes(`${activePage}`)) {
        link.classList.add('profile-list-active');
        subLinksCollapse.classList.remove('collapse');
    }
})