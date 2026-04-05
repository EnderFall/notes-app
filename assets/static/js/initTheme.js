const body = document.body;
const theme = localStorage.getItem('theme');

// Hanya atur tema jika ada preferensi yang disimpan
if (theme) {
  document.documentElement.setAttribute('data-bs-theme', theme);
} else {
  // Jika tidak ada preferensi yang disimpan, atur tema default ke light
  document.documentElement.setAttribute('data-bs-theme', 'light');
}
