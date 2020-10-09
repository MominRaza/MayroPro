if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker
            .register('sw.js')
            .then(reg => console.log('Service Worker: Register'))
            .catch(err => console.log(`Service Worker: Error: ${err}`));
    })
}