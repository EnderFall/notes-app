const THEME_KEY = "theme"

function setLightTheme() {
  setTheme("light", true);
}

/**
 * Set theme for mazer
 * @param {"dark"|"light"} theme
 * @param {boolean} persist 
 */
function setTheme(theme, persist = false) {
  document.body.classList.add(theme)
  document.documentElement.setAttribute('data-bs-theme', theme)
  
  if (persist) {
    localStorage.setItem(THEME_KEY, theme)
  }
}

/**
 * Init theme from setTheme()
 */
function initTheme() {
  const toggler = document.getElementById("toggle-dark");
  const theme = localStorage.getItem(THEME_KEY);

  if (toggler) {
    toggler.checked = theme === "dark";
    
    toggler.addEventListener("input", (e) => {
      setTheme(e.target.checked ? "dark" : "light", true);
    });
  }

  // Only set the theme to light if there is no stored theme
  if (!theme) {
    setLightTheme();
  }
}

window.addEventListener('DOMContentLoaded', initTheme);

