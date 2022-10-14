function toggleMenu() {
  document.body.classList.toggle('menu-active');
}

const x = window.matchMedia('(max-width: 1023px)');

function toggleSearch() {
  let search = document.getElementById('search-toggle');
  search.classList.toggle('active');

  if (x.matches) {
    search.innerText = search.innerText === 'search' ? 'arrow_back' : 'search';
  } else {
    search.innerText = search.innerText === 'search' ? 'close' : 'search';
  }

  let searchBox = document.getElementById('qa-search');
  searchBox.classList.toggle('active');
}

function toggleUser() {
  let userBox = document.getElementById('qa-nav-user');
  userBox.classList.toggle('active');
}

function toggleTheme(themeToggle) {
  if (document.body.classList.contains('light-theme')) {
    document.body.classList.replace('light-theme', 'dark-theme');
    themeToggle.innerText = 'dark_mode';
    themeToggle.title = 'Dark';
    document.cookie = 'theme=dark; max-age=2592000; path=/;';
  } else if (document.body.classList.contains('dark-theme')) {
    document.body.classList.remove('dark-theme');
    themeToggle.innerText = 'brightness_auto';
    themeToggle.title = 'System default';
    document.cookie = 'theme=; max-age=0; path=/;';
  } else {
    document.body.classList.add('light-theme');
    themeToggle.innerText = 'light_mode';
    themeToggle.title = 'Light';
    document.cookie = 'theme=light; max-age=2592000; path=/;';
  }
  toggleThemeJS();
}

function toggleExtra(e) {
  e.classList.toggle('active');
}

function toggleThemeJS() {
  if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
    if (document.body.classList.contains('light-theme')) {
      document
        .querySelector('meta[name="theme-color"]')
        .setAttribute('content', '#002de3');
    } else {
      document
        .querySelector('meta[name="theme-color"]')
        .setAttribute('content', '#35363a');
    }
  } else if (document.body.classList.contains('dark-theme')) {
    document
      .querySelector('meta[name="theme-color"]')
      .setAttribute('content', '#35363a');
  } else {
    document
      .querySelector('meta[name="theme-color"]')
      .setAttribute('content', '#002de3');
  }
}

toggleThemeJS();

window.matchMedia('(prefers-color-scheme: dark)').onchange = () => {
  toggleThemeJS();
};

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register(qa_root + 'sw_offline.js');
  });
}
