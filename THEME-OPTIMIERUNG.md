# Theme-Optimierung ortsverein-nv

Kurzdokumentation der technischen Bereinigung und Optimierung (Performance, Accessibility, SEO-Basis, Architektur).

---

## 1. Kurzanalyse der umgesetzten Punkte

- **Frontend-Ballast:** Block-Bibliothek, Global Styles, Classic-Theme-Styles und Dashicons wurden im Frontend abgemeldet; Emojis und oEmbed waren bereits deaktiviert. RSD, WLW, Generator, Shortlink und REST-Link wurden aus dem Head entfernt.
- **Above-the-fold:** Kritisches CSS (Skip-Link, Header, Container, Hero-Basis) wird inline im Head ausgegeben; Logo mit `width="120"` (ohne fetchpriority, da nicht zwingend LCP-relevant); Hero-Hintergrund weiterhin per Inline-Style (Customizer).
- **Accessibility:** Skip-Link unverändert; Mobile Nav mit `aria-hidden` und `hidden` wenn geschlossen, JS setzt beides beim Öffnen/Schließen; `aria-current="page"` für aktuelle Menüpunkte; dekorative SVGs mit `aria-hidden="true"`; Logo ohne ungültiges `height="auto"`.
- **SEO-Basis:** Title-Struktur über Filter vorbereitet; Meta-Description nur über Filter `ortsverein_nv_meta_description` (kein Dummy-Inhalt); Trennzeichen für Title angepasst.
- **Architektur:** Klare Trennung in `inc/`: theme-setup, enqueue, template-functions, ics, customizer, cleanup, head-cleanup, accessibility, seo; functions.php lädt alle Includes in sinnvoller Reihenfolge.
- **Markup:** Ungültiges `height="auto"` am Logo entfernt; Logo-Alttext „AWO Ortsverein Neukirchen-Vluyn“; Breadcrumbs in Startseiten-Teaser (Begegnung, Mitgliedschaft) entfernt – konzeptionell korrekt, da man auf der Startseite ist, nicht auf der Zielseite; `.entry-content`-Basis und Fallbacks für Classic-Editor bzw. mögliche Core-Klassen (alignleft, wp-caption, Tabellen) in theme.css ergänzt.
- **Gutenberg/Blockstyles:** Im Frontend werden wp-block-library, wp-block-library-theme, global-styles, classic-theme-styles und wc-blocks-style abgemeldet; Theme-CSS deckt Inhalte ab.

---

## 2. Was wurde entfernt

| Bereich | Entfernt / Deaktiviert |
|--------|-------------------------|
| Head | RSD-Link, WLW-Manifest, Generator-Meta, Shortlink, REST-API-Link |
| Frontend-CSS | wp-block-library, wp-block-library-theme, global-styles, classic-theme-styles, wc-blocks-style, dashicons, wp-img-auto-sizes-contain (nur Frontend) |
| Global Styles | remove_action für wp_enqueue_global_styles (wp_enqueue_scripts + wp_footer), verhindert global-styles-inline-css |
| Bereits vorher | Emojis, oEmbed/Embeds (cleanup.php) |
| Theme-Support | responsive-embeds (nicht genutzt) |

---

## 3. Was wurde optimiert

| Bereich | Maßnahme |
|--------|----------|
| Above-the-fold | Kritisches CSS inline (Skip, Header, Container, Hero, Brand); Logo feste `width`, aussagekräftiger Alttext |
| Accessibility | Mobile Nav: `aria-hidden` + `hidden` wenn geschlossen; JS synchronisiert beim Toggle; `aria-current="page"` im Menü; SVGs dekorativ mit `aria-hidden` |
| SEO | Filter `document_title_parts`, `document_title_separator`; Hook `ortsverein_nv_meta_description` für spätere Meta-Description |
| Architektur | head-cleanup.php, accessibility.php, seo.php; functions.php mit Kommentaren und klarer Reihenfolge |
| Classic-Content | .entry-content + alignleft/right/center, wp-caption, Tabellen in theme.css |

---

## 4. Potenzielle Nebenwirkungen – lokal testen

- **Plugins mit Block-/Widget-Nutzung:** Wenn ein Plugin Block-Styles oder Dashicons im Frontend erwartet, können Darstellung oder Icons fehlen. Gegebenenfalls in cleanup.php die entsprechenden `wp_dequeue_style`-Zeilen auskommentieren oder per Plugin-Check anpassen.
- **REST-API:** Bereits auf Nutzer mit `manage_options` beschränkt; ohne REST-Link im Head funktioniert die Admin-Oberfläche und der Editor normal.
- **Mobile Nav ohne JS:** Nav bleibt mit `hidden` und `aria-hidden="true"` unsichtbar und für Screenreader ausgeblendet; bei deaktiviertem JS erscheint sie nicht. Sinnvoll, da die Navigation ohne JS nicht bedienbar wäre; Hauptnavigation bleibt im Desktop-Header sichtbar.
- **Logo-Höhe:** Nur `width="120"` gesetzt; Höhe skaliert proportional (SVG). Bei einem Pixel-Logo ggf. feste `height` setzen, um Layout-Shift zu vermeiden.
- **Hero-Hintergrundbild:** Wird weiterhin per Inline-Style aus dem Customizer eingebunden. Optional später: `preload` für das Bild, wenn es für den First Paint wichtig ist.

---

## 5. Dateien geändert / neu

- **Neu:** `inc/head-cleanup.php`, `inc/accessibility.php`, `inc/seo.php`
- **Geändert:** `functions.php`, `inc/cleanup.php`, `inc/enqueue.php`, `inc/theme-setup.php`, `header.php`, `template-parts/hero.php`, `assets/css/theme.css`, `assets/js/theme.js`
- **Dieses Dokument:** `THEME-OPTIMIERUNG.md`
