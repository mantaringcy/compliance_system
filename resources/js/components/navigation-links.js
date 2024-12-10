// Active Links
const activePage = window.location.pathname;

const navLinks = document.querySelectorAll('.sidebar .sidebar-nav a').forEach(link => {
    if (link.href.includes(`${activePage}`)) {
        link.classList.add('sidebar-item-active');

        // Find the SVG inside the active link and add a class to it
        const svg = link.querySelector('svg');
        if (svg) {
            svg.classList.add('svg-active');
        }

        // Add class to the sidebar-text inside the active link
        const text = link.querySelector('.sidebar-text');
        if (text) {
            text.classList.add('active');
        }
    }
})

const subLinksCollapse = document.getElementById('profile-list');
const subLinks = document.querySelectorAll('.sidebar .profile a').forEach(link => {
    if (link.href.includes(`${activePage}`)) {
        link.classList.add('profile-list-active');
        subLinksCollapse.classList.remove('collapse');
    }
})