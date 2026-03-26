/* ============================================
   TORAT HABAYIT — Shared Header & Footer
   ============================================ */

function renderHeader() {
  return `
  <header class="site-header">
    <div class="header-inner">
      <a href="index.html" class="logo">
        <span class="logo-main">Torat Habayit</span>
        <span class="logo-sub">Institute</span>
      </a>
      <nav class="nav-menu">
        <a href="index.html" data-i18n="nav_home">Home</a>
        <a href="about.html" data-i18n="nav_about">About Us</a>
        <a href="books.html" data-i18n="nav_books">Books</a>
        <a href="courses.html" data-i18n="nav_courses">Courses</a>
        <a href="resources.html" data-i18n="nav_resources">Resources</a>
        <a href="blog.html" data-i18n="nav_blog">Blog</a>
        <a href="gallery.html" data-i18n="nav_gallery">Gallery</a>
        <a href="contact.html" data-i18n="nav_contact">Contact Us</a>
        <a href="donations.html" class="nav-donate" data-i18n="nav_donate">Donate</a>
        <button onclick="toggleLanguage()" class="lang-toggle-btn" style="background:transparent;border:1px solid rgba(255,255,255,0.4);color:#fff;border-radius:20px;padding:4px 12px;cursor:pointer;margin-left:8px;font-family:inherit;font-weight:600;font-size:0.8rem;"><span class="lang-toggle-text">עב</span></button>
      </nav>
      <button class="hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>
  <div class="mobile-menu">
    <button class="mobile-menu-close">✕</button>
    <a href="index.html" data-i18n="nav_home">Home</a>
    <a href="about.html" data-i18n="nav_about">About Us</a>
    <a href="books.html" data-i18n="nav_books">Books</a>
    <a href="courses.html" data-i18n="nav_courses">Courses</a>
    <a href="resources.html" data-i18n="nav_resources">Resources</a>
    <a href="blog.html" data-i18n="nav_blog">Blog</a>
    <a href="gallery.html" data-i18n="nav_gallery">Gallery</a>
    <a href="donations.html" data-i18n="nav_donate">Donate Now</a>
    <a href="contact.html" data-i18n="nav_contact">Contact Us</a>
    <button onclick="toggleLanguage()" class="lang-toggle-btn" style="background:transparent;border:1px solid var(--navy);color:var(--navy);border-radius:20px;padding:8px 16px;cursor:pointer;margin-top:1rem;font-family:inherit;font-weight:600;"><span class="lang-toggle-text">עב (Hebrew)</span></button>
  </div>`;
}

function renderFooter() {
  return `
  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
        <div>
          <div class="footer-logo">
            <a href="index.html" class="logo">
              <span class="logo-main">Torat Habayit</span>
              <span class="logo-sub">Institute</span>
            </a>
          </div>
          <p class="footer-tagline" data-i18n="footer_tagline">Guiding the intersection of Torah law and modern medicine for over three decades.</p>
        </div>
        <div class="footer-col">
          <h4 data-i18n="footer_links">Quick Links</h4>
          <ul>
            <li><a href="about.html" data-i18n="nav_about">About Us</a></li>
            <li><a href="books.html" data-i18n="nav_books">Publications</a></li>
            <li><a href="courses.html" data-i18n="nav_courses">Courses</a></li>
            <li><a href="resources.html" data-i18n="nav_resources">Resources</a></li>
            <li><a href="blog.html" data-i18n="nav_blog">Blog</a></li>
            <li><a href="donations.html" data-i18n="nav_donate">Donate</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4 data-i18n="footer_contact">Contact Us</h4>
          <div class="footer-contact-item">
            <span>📍</span>
            <span>Rechov Haran 3/3, Jerusalem 9246603, Israel</span>
          </div>
          <div class="footer-contact-item">
            <span>📞</span>
            <span><a href="tel:026504650">02-650-4-650</a> (Israel)<br><a href="tel:+17187040985">+1-718-704-0985</a> (USA)</span>
          </div>
          <div class="footer-contact-item">
            <span>✉️</span>
            <span><a href="mailto:info@torathabayit.com">info@torathabayit.com</a></span>
          </div>
        </div>
        <div class="footer-col">
          <h4 data-i18n="footer_stay">Stay Connected</h4>
          <p style="color:rgba(255,255,255,0.55);font-size:0.85rem;margin-bottom:0.75rem;" data-i18n="footer_stay_desc">Receive Halachic insights and updates from the Institute.</p>
          <form class="footer-newsletter" onsubmit="return false;">
            <input type="email" placeholder="Your email address" data-i18n="footer_placeholder">
            <button type="submit">→</button>
          </form>
          <p class="footer-tax-note" data-i18n="footer_tax">🇺🇸 USA Tax-Deductible: 501(c)(3) recognised<br>🇮🇱 Israel Institution Code: 45622</p>
        </div>
      </div>
      <div class="footer-bottom">
        <span>© ${new Date().getFullYear()} <span data-i18n="footer_rights">Torat Habayit Institute. All rights reserved.</span></span>
        <div style="display:flex;gap:1rem;">
          <a href="contact.html" data-i18n="nav_contact">Contact</a>
          <a href="donations.html" data-i18n="nav_donate">Donate</a>
          <a href="admin/index.html" data-i18n="footer_admin">Admin</a>
        </div>
      </div>
    </div>
  </footer>`;
}

// Auto-inject header and footer
document.addEventListener('DOMContentLoaded', () => {
  const headerPlaceholder = document.getElementById('header-placeholder');
  const footerPlaceholder = document.getElementById('footer-placeholder');
  if (headerPlaceholder) headerPlaceholder.outerHTML = renderHeader();
  if (footerPlaceholder) footerPlaceholder.outerHTML = renderFooter();
});
