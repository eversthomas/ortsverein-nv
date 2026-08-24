/**
 * Customizer Live-Vorschau – Ortsverein NV
 */
(function() {
  'use strict';

  function sanitizeSize(value) {
    var size = parseInt(value, 10);
    if (isNaN(size)) {
      size = 44;
    }
    if (size < 32) {
      size = 32;
    }
    if (size > 140) {
      size = 140;
    }
    return size;
  }

  function paddingFromSize(size) {
    var pad = Math.round((size - 44) / 2 + 14);
    return pad < 14 ? 14 : pad;
  }

  function applyHeaderSize(raw) {
    var size = sanitizeSize(raw);
    var pad = paddingFromSize(size);
    var css = ':root{--header-logo-size:' + size + 'px;}' +
      '#site-header .brand-icon{height:' + size + 'px;width:auto;max-width:none;max-height:' + size + 'px;}' +
      '#site-header .header-inner{padding-top:' + pad + 'px;padding-bottom:' + pad + 'px;}';

    var styleEl = document.getElementById('ortsverein-nv-header-size');
    if (!styleEl) {
      styleEl = document.createElement('style');
      styleEl.id = 'ortsverein-nv-header-size';
      document.head.appendChild(styleEl);
    }
    styleEl.textContent = css;
    document.documentElement.style.setProperty('--header-logo-size', size + 'px');
  }

  function bindSetting() {
    if (typeof wp === 'undefined' || !wp.customize) {
      return;
    }
    wp.customize('ortsverein_nv_header_height', function(value) {
      applyHeaderSize(value.get());
      value.bind(applyHeaderSize);
    });
  }

  if (typeof wp !== 'undefined' && wp.customize) {
    wp.customize.bind('preview-ready', bindSetting);
    bindSetting();
  }
})();
