// JS global mínimo (se ampliará en Ventas)
document.addEventListener('DOMContentLoaded', () => {
  const navItems = document.querySelectorAll('body.dashv2 .nav-item');
  if (navItems.length) {
    navItems.forEach((navItem) => {
      navItem.addEventListener('click', () => {
        navItems.forEach((item) => {
          item.className = 'nav-item';
        });
        navItem.className = 'nav-item active';
      });
    });
  }
});

