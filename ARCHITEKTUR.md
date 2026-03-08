# Theme-Architektur ortsverein-nv

## Kurzbeschreibung

Klassisches WordPress-Theme für den AWO Ortsverein Neukirchen-Vluyn. Die Struktur folgt der WordPress Template Hierarchy; Design und Layout liegen vollständig im Theme. Redakteure bearbeiten nur Inhalte im Classic Editor.

## Dateistruktur

```
ortsverein-nv/
├── style.css              # Theme-Header, WordPress erkennt Theme
├── functions.php          # Nur Loader für inc/*
├── index.php              # Fallback-Template
├── front-page.php         # Startseite (Hero + Angebotskacheln)
├── page.php               # Standard-Seiten
├── header.php             # Skip-Link, Header, Navigation
├── footer.php             # Site-Footer
├── inc/
│   ├── theme-setup.php    # after_setup_theme (Sprache, Title-Tag, Menüs, etc.)
│   ├── enqueue.php        # Styles & Scripts (assets)
│   ├── cleanup.php        # Gutenberg aus, Classic Editor, Kommentare, etc.
│   └── template-functions.php  # Hilfsfunktionen (Breadcrumb, Escaping)
├── template-parts/
│   ├── hero.php           # Hero-Bereich (nur Startseite)
│   ├── angebot-kacheln.php # Kacheln „Was wir für dich tun können“
│   ├── section-header.php # Wiederverwendbar: Label + H2 + Text
│   ├── breadcrumb.php     # Breadcrumb-Navigation
│   └── back-to-top.php    # Link „Zurück nach oben“
├── assets/
│   ├── css/theme.css      # Ausgelagertes CSS der HTML-Vorlage
│   ├── js/theme.js        # Ausgelagertes JS (Burger, Kalender, Formular, etc.)
│   └── images/            # Logo, Hero-Hintergrund (optional)
└── html-vorlage/          # Referenz (nicht ausgeliefert)
```

## Template Hierarchy

- **Startseite:** `front-page.php` → Hero + Angebotskacheln (festes Layout).
- **Seiten:** `page.php` → Header, Main mit `the_content()`, Footer. Später optional `page-{slug}.php` für Begegnung, Kontakt, etc.
- **Fallback:** `index.php` → minimaler Fallback.

## Menü

Ein WordPress-Menü (z. B. „Hauptmenü“) wird in `header.php` mit `wp_nav_menu()` ausgegeben – für Desktop und Mobile dieselbe Menüquelle. Keine Anker-Links; echte Seiten-URLs.

## Assets

- **CSS:** Eine Datei `assets/css/theme.css` (Design-System, Layout, Komponenten, Responsive). Kein Inline-CSS.
- **JS:** Eine Datei `assets/js/theme.js` (Burger, Sticky-Header, Kalender, Kontaktformular, Fade-in, Smooth-Scroll für Anker). Kein Inline-JS.
- **Bilder:** Logo und optional Hero-Hintergrund in `assets/images/`. Pfade in CSS relativ: `../images/`.

## Präfix & Sicherheit

- Alle eigenen Funktionen/Konstanten: Präfix `ortsverein_nv_`.
- Ausgaben: `esc_html()`, `esc_attr()`, `esc_url()`.
- Eingaben: `sanitize_text_field()` etc., Nonce bei Formularen.

## SEO & Barrierefreiheit

- Title über `add_theme_support( 'title-tag' )`; Meta-Description ggf. über Template oder Plugin.
- Eine H1 pro Seite; semantische Landmarken (header, main, nav, footer).
- Skip-Link in `header.php`; Fokuszustände und Tastaturbedienbarkeit über vorhandenes CSS/JS.

## Weiterer Plan

Verbindliche Vorgaben und offene Aufgaben (Unterseiten-Hero, Kalender/ICS, Kontakt ohne Formular, Begegnungsstätte, Beratung, Impressum/Datenschutz, KI-SEO) sind in **ENTWICKLUNGSPLAN.md** beschrieben.
