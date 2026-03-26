/* ============================================
   TORAT HABAYIT — i18n Translation Engine
   ============================================ */

function updateLanguage(lang) {
  // 1. Set global document attributes
  document.documentElement.lang = lang;
  document.documentElement.dir = lang === 'he' ? 'rtl' : 'ltr';

  // 2. Iterate over every element tagged with a data-i18n key
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    
    // Check if the translation dictionary has this key in the chosen language
    if (translations[lang] && translations[lang][key]) {
      // Inputs and Textareas use placeholders, standard tags use innerHTML
      if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
        el.placeholder = translations[lang][key];
      } else {
        el.innerHTML = translations[lang][key];
      }
    }
  });

  // 3. Update the toggle button's text to reflect the current state
  const toggleText = document.querySelector('.lang-toggle-text');
  if (toggleText) {
    toggleText.textContent = lang === 'en' ? 'עב' : 'EN';
  }
}

function toggleLanguage() {
  // Read current setting, toggle it, save it, and apply it
  const currentLang = localStorage.getItem('siteLang') || 'en';
  const newLang = currentLang === 'en' ? 'he' : 'en';
  localStorage.setItem('siteLang', newLang);
  updateLanguage(newLang);
}

// 4. Initialization on Document Load
document.addEventListener('DOMContentLoaded', () => {
  const savedLang = localStorage.getItem('siteLang') || 'en';
  // Small delay ensures dynamic components (like headers/footers in JS) are fully rendered first
  setTimeout(() => updateLanguage(savedLang), 100);
});
