x = window.matchMedia('(max-width: 1023px)');
function toggleMenu() {
    var menu = document.getElementById('menu-toggle');
    if (x.matches) { // If media query matches
        menu.innerText = menu.innerText == 'menu' ? 'close' : 'menu';
    }
    document.body.classList.toggle('menu-active');
}
function toggleSearch() {
    var search = document.getElementById('search-toggle');
    search.classList.toggle('active');
    if (x.matches) { // If media query matches
        search.innerText = search.innerText == 'search' ? 'arrow_back' : 'search';
    } else {
        search.innerText = search.innerText == 'search' ? 'close' : 'search';
    }
    var searchBox = document.getElementById('qa-search');
    searchBox.classList.toggle('active');
}
function toggleUser() {
    var userBox = document.getElementById('qa-nav-user');
    userBox.classList.toggle('active');
}

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker
            .register('sw.js')
            .then(reg => console.log('Service Worker: Register'))
            .catch(err => console.log(`Service Worker: Error: ${err}`));
    })
}