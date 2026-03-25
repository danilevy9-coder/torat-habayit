/* ============================================
   TORAT HABAYIT — Main JavaScript
   ============================================ */

document.addEventListener('DOMContentLoaded', () => {

  // ─── Active Nav Link ───
  const currentPath = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-menu a, .mobile-menu a').forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPath || (currentPath === '' && href === 'index.html')) {
      link.classList.add('active');
    }
  });

  // ─── Mobile Menu ───
  const hamburger = document.querySelector('.hamburger');
  const mobileMenu = document.querySelector('.mobile-menu');
  const mobileClose = document.querySelector('.mobile-menu-close');
  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', () => mobileMenu.classList.add('open'));
    mobileClose?.addEventListener('click', () => mobileMenu.classList.remove('open'));
  }

  // ─── Scroll Fade-In ───
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, { threshold: 0.12 });
  document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

  // ─── Header scroll shadow ───
  const header = document.querySelector('.site-header');
  if (header) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 40) {
        header.style.boxShadow = '0 4px 30px rgba(0,0,0,0.25)';
      } else {
        header.style.boxShadow = '0 2px 20px rgba(0,0,0,0.2)';
      }
    });
  }

  // ─── Newsletter form ───
  const newsletterForm = document.querySelector('.footer-newsletter');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const input = newsletterForm.querySelector('input');
      if (input.value) {
        showToast('Thank you for subscribing!');
        input.value = '';
      }
    });
  }

});

// ─── Toast notification ───
function showToast(msg, type = 'success') {
  const toast = document.createElement('div');
  toast.style.cssText = `
    position:fixed; bottom:2rem; right:2rem; z-index:10000;
    background:${type === 'success' ? '#0A192F' : '#DC2626'};
    color:#fff; padding:0.9rem 1.4rem; border-radius:10px;
    font-size:0.9rem; font-weight:500; box-shadow:0 8px 30px rgba(0,0,0,0.2);
    display:flex; align-items:center; gap:0.6rem;
    animation: slideIn 0.3s ease;
  `;
  const icon = type === 'success' ? '✓' : '✕';
  toast.innerHTML = `<span style="color:#C5A059;font-size:1.1rem">${icon}</span> ${msg}`;
  document.body.appendChild(toast);
  setTimeout(() => {
    toast.style.animation = 'slideOut 0.3s ease forwards';
    setTimeout(() => toast.remove(), 300);
  }, 3500);
}

// Add animation keyframes
const style = document.createElement('style');
style.textContent = `
  @keyframes slideIn { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
  @keyframes slideOut { from { opacity:1; transform:translateY(0); } to { opacity:0; transform:translateY(20px); } }
`;
document.head.appendChild(style);
