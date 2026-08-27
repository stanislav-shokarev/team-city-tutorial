/**
 * Mobile menu toggle plus keyboard access to second-level menus.
 *
 * The markup comes from wp_nav_menu(), so this only ever adds behaviour to
 * classes WordPress already emits. With JavaScript disabled the menu is still
 * reachable — CSS shows it from the 'md' breakpoint up.
 */
export function initNavigation() {
  const toggle = document.querySelector('[data-nav-toggle]');
  const nav = document.querySelector('[data-nav]');

  if (!toggle || !nav) {
    return;
  }

  const setExpanded = (expanded) => {
    toggle.setAttribute('aria-expanded', String(expanded));
    nav.classList.toggle('nav--open', expanded);
  };

  toggle.addEventListener('click', () => {
    setExpanded(toggle.getAttribute('aria-expanded') !== 'true');
  });

  // Close on Escape and return focus to the button that opened it.
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
      setExpanded(false);
      toggle.focus();
    }
  });

  // Reset the toggle state when the viewport grows past the breakpoint,
  // otherwise the desktop menu inherits a stale aria-expanded="true".
  const desktop = window.matchMedia('(min-width: 768px)');

  desktop.addEventListener('change', (event) => {
    if (event.matches) {
      setExpanded(false);
    }
  });

  initSubMenus(nav);
}

/**
 * Adds a real button to each submenu parent so the menu is operable by
 * keyboard and touch, not only by hover.
 *
 * @param {HTMLElement} nav The nav container.
 */
function initSubMenus(nav) {
  const parents = nav.querySelectorAll('.menu-item-has-children');

  parents.forEach((parent) => {
    const submenu = parent.querySelector('.sub-menu');

    if (!submenu) {
      return;
    }

    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'submenu-toggle';
    button.setAttribute('aria-expanded', 'false');
    button.innerHTML = '<span class="screen-reader-text">Show submenu</span><span aria-hidden="true">▾</span>';

    button.addEventListener('click', () => {
      const open = button.getAttribute('aria-expanded') === 'true';
      button.setAttribute('aria-expanded', String(!open));
      parent.classList.toggle('is-open', !open);
    });

    parent.insertBefore(button, submenu);
  });

  // Collapse any open submenu when focus leaves the nav entirely.
  document.addEventListener('focusin', (event) => {
    if (nav.contains(event.target)) {
      return;
    }

    parents.forEach((parent) => {
      parent.classList.remove('is-open');
      parent.querySelector('.submenu-toggle')?.setAttribute('aria-expanded', 'false');
    });
  });
}
