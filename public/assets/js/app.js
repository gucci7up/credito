document.addEventListener('DOMContentLoaded', () => {
  const navItems = document.querySelectorAll('.app-navitem');
  if (navItems.length) {
    const params = new URLSearchParams(window.location.search);
    const r = (params.get('r') || 'dashboard/index').toLowerCase();
    const section = r.split('/')[0] || 'dashboard';
    navItems.forEach((el) => {
      const key = el.getAttribute('data-route');
      if (key === section) {
        el.classList.add('is-active');
      } else {
        el.classList.remove('is-active');
      }
    });
  }
});

