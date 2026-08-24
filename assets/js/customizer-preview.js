/**
 * Customizer Live-Vorschau – Ortsverein NV
 */
(function() {
  'use strict';

  if (typeof wp === 'undefined' || !wp.customize) {
    return;
  }

  wp.customize('ortsverein_nv_header_height', function(value) {
    value.bind(function(newval) {
      var size = parseInt(newval, 10);
      if (isNaN(size)) {
        return;
      }
      if (size < 32) {
        size = 32;
      }
      if (size > 140) {
        size = 140;
      }
      document.documentElement.style.setProperty('--header-logo-size', size + 'px');
    });
  });
})();
