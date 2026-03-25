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
        <a href="index.html">Home</a>
        <a href="about.html">About Us</a>
        <a href="books.html">Books</a>
        <a href="courses.html">Courses</a>
        <a href="resources.html">Resources</a>
        <a href="blog.html">Blog</a>
        <a href="gallery.html">Gallery</a>
        <a href="contact.html">Contact Us</a>
        <a href="donations.html" class="nav-donate">Donate</a>
      </nav>
      <button class="hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>
  <div class="mobile-menu">
    <button class="mobile-menu-close">✕</button>
    <a href="index.html">Home</a>
    <a href="about.html">About Us</a>
    <a href="books.html">Books</a>
    <a href="courses.html">Courses</a>
    <a href="resources.html">Resources</a>
    <a href="blog.html">Blog</a>
    <a href="gallery.html">Gallery</a>
    <a href="donations.html">Donate Now</a>
    <a href="contact.html">Contact Us</a>
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
          <p class="footer-tagline">Guiding the intersection of Torah law and modern medicine for over three decades.</p>
        </div>
        <div class="footer-col">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="about.html">About Us</a></li>
            <li><a href="books.html">Publications</a></li>
            <li><a href="courses.html">Courses</a></li>
            <li><a href="resources.html">Resources</a></li>
            <li><a href="blog.html">Blog</a></li>
            <li><a href="donations.html">Donate</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Contact Us</h4>
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
          <h4>Stay Connected</h4>
          <p style="color:rgba(255,255,255,0.55);font-size:0.85rem;margin-bottom:0.75rem;">Receive Halachic insights and updates from the Institute.</p>
          <form class="footer-newsletter" onsubmit="return false;">
            <input type="email" placeholder="Your email address">
            <button type="submit">→</button>
          </form>
          <p class="footer-tax-note">🇺🇸 USA Tax-Deductible: 501(c)(3) recognised<br>🇮🇱 Israel Institution Code: 45622</p>
        </div>
      </div>
      <div class="footer-bottom">
        <span>© ${new Date().getFullYear()} Torat Habayit Institute. All rights reserved.</span>
        <div style="display:flex;gap:1rem;">
          <a href="contact.html">Contact</a>
          <a href="donations.html">Donate</a>
          <a href="admin/index.html">Admin</a>
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
