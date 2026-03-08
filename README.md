# Theme ortsverein-nv

Klassisches WordPress-Theme für den AWO Ortsverein Neukirchen-Vluyn. Redakteure bearbeiten Inhalte im **Classic Editor**; Design und Layout liegen vollständig im Theme. Kein Gutenberg, kein Pagebuilder, kein ACF. Vanilla-CSS first.

---

## So funktioniert das Theme

### Dateistruktur

```
ortsverein-nv/
├── style.css              # Theme-Header (WordPress erkennt Theme)
├── functions.php          # Lädt nur inc/* (Reihenfolge dokumentiert)
├── index.php              # Fallback-Template
├── front-page.php         # Startseite
├── page.php               # Standard-Seiten (mit reduziertem Hero)
├── page-kalender.php      # Kalenderseite (Slug: kalender)
├── page-mitgliedschaft.php # Mitgliedschaft (Slug: mitgliedschaft)
├── header.php             # Skip-Link, Header, Navigation, Mobile-Nav
├── footer.php             # Site-Footer
├── inc/
│   ├── theme-setup.php    # after_setup_theme (Sprache, title-tag, Menüs, custom-logo)
│   ├── enqueue.php        # Kritisches CSS inline + theme.css, theme.js
│   ├── template-functions.php # Hilfsfunktionen (Home-URL, etc.)
│   ├── ics.php            # ICS-Abruf, -Parser, Caching, Termin-Helfer
│   ├── customizer.php     # Hero, Kalender-ICS-URL
│   ├── cleanup.php        # Gutenberg aus, Emojis, Embeds, Frontend-Assets, Global Styles
│   ├── head-cleanup.php   # RSD, WLW, Generator, Shortlink, REST-Link
│   ├── accessibility.php  # aria-current für Menü, Body-Class-Hook
│   └── seo.php            # Title-Filter, Meta-Description-Hook
├── template-parts/
│   ├── hero.php           # Hero (nur Startseite)
│   ├── hero-subpage.php   # Reduzierter Hero (Breadcrumb + H1) für Unterseiten
│   ├── angebot-kacheln.php # Kacheln „Was wir für dich tun können“
│   ├── begegnung-teaser.php # Teaser Begegnungsstätte (Startseite)
│   ├── mitgliedschaft-teaser.php # Teaser Mitgliedschaft (Startseite)
│   ├── termine-teaser.php # Kalender aktueller Monat + Terminliste (Startseite)
│   ├── kalender-grid.php  # Monats-Grid (Kalender + Startseite)
│   ├── termin-item.php    # Einzelner Termin (ICS)
│   ├── section-header.php # Label + H2 + optionaler Text
│   ├── breadcrumb.php     # Breadcrumb (Unterseiten)
│   └── back-to-top.php    # Link „Zurück nach oben“
├── assets/
│   ├── css/theme.css      # Vanilla-CSS (Design-System, Layout, Komponenten)
│   ├── js/theme.js        # Burger, Kalender-Tag-Hervorhebung, Fade-in
│   └── images/            # Logo (logo.svg)
└── html-vorlage/          # Statische Referenz (nicht ausgeliefert)
```

### Template Hierarchy

- **Startseite:** `front-page.php` → Hero, Angebotskacheln, Begegnungs-Teaser, Mitgliedschafts-Teaser, Termine-Teaser (aktueller Monat + Link zum Kalender).
- **Seiten:** `page.php` → reduzierter Hero (Breadcrumb + H1), dann `the_content()`.  
  Ausnahme: Seite mit Slug **kalender** → `page-kalender.php` (Monatsansicht + Terminliste aus ICS).  
  Ausnahme: Seite mit Slug **mitgliedschaft** → `page-mitgliedschaft.php` (Editor-Inhalt + iFrame AWO-Online-Mitgliedschaftsformular).
- **Fallback:** `index.php`.

### Menüs

Drei Menü-Positionen (Theme-Setup): **Hauptmenü**, **Schnellzugriff** (Kontakt, Downloads, Kalender), **Footer**. Desktop und Mobile nutzen dieselbe Menüquelle; Mobile Nav wird per Burger geöffnet/geschlossen (ARIA, `hidden`).

### Assets

- **CSS:** Kritisches Above-the-fold-CSS inline im Head; Hauptdatei `assets/css/theme.css`. Hero-Hintergrundbild aus dem Customizer per Inline-Style.
- **JS:** `assets/js/theme.js` (Burger mit aria-hidden/hidden, Kalender-Tag-Klick → Termin hervorheben, Fade-in). Kein Inline-JS.
- **Bilder:** Logo in `assets/images/logo.svg`; Customizer optional Hero-Bild.

### Präfix & Sicherheit

- Alle eigenen Funktionen/Konstanten: Präfix **ortsverein_nv_**.
- Ausgaben: `esc_html()`, `esc_attr()`, `esc_url()`.
- Eingaben: `sanitize_text_field()` etc.; bei Kalender-Parameter `sanitize_text_field( wp_unslash( $_GET['kal_month'] ) )` + Regex-Prüfung.

### Kalender / ICS

- **Customizer:** Feld „Kalender (ICS)“ → ICS-URL (Standard: AWO KV Wesel).
- **Abruf:** `inc/ics.php` – `wp_remote_get`, Transient-Cache (30 Min), Parser für iCal (VEVENT, DTSTART, SUMMARY, LOCATION …).
- **Kalenderseite:** Monatsnavigation per URL `?kal_month=YYYY-MM`, Grid + Terminliste im bestehenden Design; Klick auf Tag mit Termin hebt passende Termine in der Liste hervor (AWO-Rot).
- **Startseite:** Nur aktueller Monat (Grid + Terminliste), Link „Zum Kalender“; erste 5 Termine neben dem Kalender, Rest darunter in zwei Spalten (Desktop).

---

## Bereits umgesetzt

### Basis & Setup

- Analyse HTML-Vorlage, Theme-Architektur, Basis-Templates (Header, Footer, front-page, page, index).
- CSS/JS aus Vorlage in `assets/`, kein Inline-Code im Markup (außer kritisches CSS und Hero-Bild).
- **Gutenberg deaktiviert**, Classic Editor; Kommentare deaktiviert; Embeds/oEmbed deaktiviert.
- **REST-API** nur für eingeloggte Nutzer mit `manage_options`, sonst 401.
- **Global Styles** an der Quelle entfernt (`remove_action` für `wp_enqueue_global_styles`); **Frontend:** wp-block-library, global-styles, classic-theme-styles, dashicons, wp-img-auto-sizes-contain abgemeldet.
- **Head-Cleanup:** RSD, WLW, Generator, Shortlink, REST-Link entfernt.

### Customizer

- **Hero (Startseite):** Eyebrow, Titel, Lead, zwei Buttons (Text + Zielseite), Hintergrundbild, drei Stat-Kacheln (Zahl + Beschriftung).
- **Kalender:** ICS-URL (Default AWO-Link); Speichern leert ICS-Transient.

### Startseite

- Hero, Angebotskacheln (Begegnung, Mitgliedschaft, Beratung, Termine – ohne „Intern“), Begegnungs-Teaser (Text + Tabelle Standardtermine + Link „Mehr zur Begegnungsstätte“), Mitgliedschafts-Teaser (Text + Benefits + Beitragsbox + Buttons + Link zur Seite), Termine-Teaser (aktueller Monat, Grid + Terminliste, Link zum Kalender; überzählige Termine unter dem Kalender in zwei Spalten).

### Unterseiten

- **Reduzierter Hero** für alle normalen Seiten (Template Part `hero-subpage`: Breadcrumb + H1).
- **Kalender:** `page-kalender.php` – Monatsansicht + Terminliste aus ICS, Klick auf Tag hebt Termin hervor.
- **Mitgliedschaft:** `page-mitgliedschaft.php` – Editor-Inhalt + iFrame (AWO Online-Mitgliedschaftsantrag), Anker `#formular` für direkten Link zum Formular.

### Barrierefreiheit & Performance

- Skip-Link, Landmarken, eine H1 pro Seite; Mobile Nav mit `aria-hidden`/`hidden` und JS-Sync; `aria-current="page"` im Menü; dekorative SVGs `aria-hidden="true"`; Logo-Alttext „AWO Ortsverein Neukirchen-Vluyn“.
- Kritisches CSS inline; `prefers-reduced-motion` im CSS; keine Breadcrumbs in Startseiten-Teaser (konzeptionell korrekt).

### SEO-Basis

- `add_theme_support( 'title-tag' )`; Filter `document_title_parts`, `document_title_separator` (»); Hook `ortsverein_nv_meta_description` für Meta-Description (kein Dummy-Inhalt im Theme).

---

## Noch umzusetzen

| Nr. | Aufgabe | Anmerkung |
|-----|--------|-----------|
| 1 | **Kontaktseite** | Ohne Formular: Kontaktdaten, Link Vereinssatzung (URL z. B. Customizer/Seite), Ansprechpartner. Optional `page-kontakt.php` oder Standard page.php. |
| 2 | **Begegnungsstätte-Template** | Eigenes Template (z. B. `page-begegnung.php`): Bild–Text-Wechsel, Öffnungszeiten; Startseiten-Teaser verlinkt bereits darauf. |
| 3 | **Beratung-Template** | Eigenes Template oder Aufbau mit Bild–Text und Beratungsinfos; optional Teaser auf Startseite. |
| 4 | **Impressum / Datenschutz** | Einfache Seiten mit reduziertem Hero und Editor-Inhalt (page.php reicht). |
| 5 | **Title / Meta** | Titel und Meta-Description pro Seite später dynamisch setzen (Plugin oder Theme-Erweiterung); Theme ist vorbereitet (Filter/Hooks). |
| 6 | **Gutenberg-Seiten** | Bestehende Seiten, die noch Block-Markup haben, einmalig im Classic Editor öffnen und speichern, damit nur Classic-Inhalt genutzt wird. |
| 7 | **Downloads** | Optional: Unterseite „Downloads“ mit Liste (Medien oder CPT); Template-Part für Download-Einträge. |
| 8 | **Intern** | Optional: Unterseite oder Redirect für Ehrenamtliche (Login/Info). |

---

## Technische Details

### Inc-Reihenfolge (functions.php)

Setup → Enqueue → Template-Functions → ICS → Customizer → Cleanup → Head-Cleanup → Accessibility → SEO.

### Frontend-Cleanup (cleanup.php)

- `ortsverein_nv_remove_global_styles()` (init): `remove_action` für `wp_enqueue_global_styles` (wp_enqueue_scripts + wp_footer).
- `ortsverein_nv_dequeue_frontend_assets()` (wp_enqueue_scripts, 100): dequeue von wp-block-library, wp-block-library-theme, global-styles, classic-theme-styles, wc-blocks-style, dashicons, wp-img-auto-sizes-contain (nur Frontend).

### Barrierefreiheit

- Skip-Link → `#main`; Navigationen mit `aria-label`; Burger `aria-expanded`, `aria-controls="mobile-nav"`; Mobile Nav beim Schließen `hidden` und `aria-hidden="true"`, beim Öffnen per JS entfernt/auf false.

### SEO

- Meta-Description nur, wenn Filter `ortsverein_nv_meta_description` einen nicht leeren String liefert. Canonical nicht im Theme (Vermeidung von Doppelung mit SEO-Plugins).

### Referenz

- **html-vorlage/index.html:** Statische One-Page-Referenz mit gleichem Design (CSS/JS waren Grundlage für assets/). Nicht ausgeliefert; bei Strukturfragen zur Zuordnung Startseite/Unterseiten/Komponenten heranziehen.

---

*Stand: Dokumentation zusammengeführt aus ARCHITEKTUR, ENTWICKLUNGSPLAN, ANALYSE-HTML-VORLAGE und THEME-OPTIMIERUNG.*
