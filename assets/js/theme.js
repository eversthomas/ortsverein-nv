/**
 * Theme JavaScript – Ortsverein NV
 * Burger-Menü, Kalender-Tag-Klick (Termin hervorheben), Fade-in.
 */
(function() {
  'use strict';

  // ===== BURGER MENU =====
  var burgerBtn = document.querySelector('.burger-btn');
  var mobileNav = document.getElementById('mobile-nav');

  if (burgerBtn && mobileNav) {
    function setMobileNavOpen(open) {
      burgerBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
      burgerBtn.setAttribute('aria-label', open ? 'Menü schließen' : 'Menü öffnen');
      mobileNav.classList.toggle('open', open);
      if (open) {
        mobileNav.removeAttribute('hidden');
        mobileNav.setAttribute('aria-hidden', 'false');
      } else {
        mobileNav.setAttribute('hidden', '');
        mobileNav.setAttribute('aria-hidden', 'true');
      }
      document.body.style.overflow = open ? 'hidden' : '';
    }

    burgerBtn.addEventListener('click', function() {
      var expanded = burgerBtn.getAttribute('aria-expanded') === 'true';
      setMobileNavOpen(!expanded);
    });

    mobileNav.querySelectorAll('a').forEach(function(link) {
      link.addEventListener('click', function() { setMobileNavOpen(false); });
    });

    document.addEventListener('click', function(e) {
      if (mobileNav.classList.contains('open') && !mobileNav.contains(e.target) && !burgerBtn.contains(e.target)) {
        setMobileNavOpen(false);
      }
    });
  }

  // ===== KALENDER: Klick auf Tag mit Termin → zugehörigen Termin hervorheben (AWO Rot) =====
  function highlightTermineForDay(dayEl) {
    if (!dayEl || !dayEl.classList.contains('has-event')) return;
    var date = dayEl.getAttribute('data-date');
    if (!date) return;
    var section = dayEl.closest('#termine') || dayEl.closest('.kalender-layout');
    if (!section) return;
    var lists = section.querySelectorAll('.termine-liste');
    if (!lists.length) return;
    var items = [];
    lists.forEach(function(list) { items = items.concat(Array.prototype.slice.call(list.querySelectorAll('.termin-item'))); });
    var firstMatch = null;
    items.forEach(function(item) {
      if (item.getAttribute('data-date') === date) {
        item.classList.add('termin-highlight');
        if (!firstMatch) firstMatch = item;
      } else {
        item.classList.remove('termin-highlight');
      }
    });
    if (firstMatch) {
      firstMatch.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
  }

  document.addEventListener('click', function(e) {
    var dayEl = e.target.closest('.kal-day.has-event');
    if (dayEl) highlightTermineForDay(dayEl);
  });

  document.addEventListener('keydown', function(e) {
    if (e.key !== 'Enter' && e.key !== ' ') return;
    var dayEl = e.target.closest('.kal-day.has-event');
    if (!dayEl) return;
    e.preventDefault();
    highlightTermineForDay(dayEl);
  });

  // ===== FADE IN (Hero etc.) =====
  if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    var observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    document.querySelectorAll('.fade-in').forEach(function(el, i) {
      el.style.transitionDelay = Math.min(i * 0.06, 0.3) + 's';
      observer.observe(el);
    });
  } else {
    document.querySelectorAll('.fade-in').forEach(function(el) {
      el.classList.add('visible');
    });
  }
})();
