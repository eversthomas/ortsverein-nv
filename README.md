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
│   ├── theme-options.php  # Zentrale Theme-Options (Organisation, Seiten, SEO-Basis)
│   ├── install.php        # Initial-Setup (Seiten, Menüs, Optionen) bei Theme-Aktivierung
│   ├── cleanup.php        # Gutenberg aus, Emojis, Embeds, Frontend-Assets, Global Styles
│   ├── head-cleanup.php   # RSD, WLW, Generator, Shortlink, REST-Link
│   ├── accessibility.php  # aria-current für Menü, Body-Class-Hook
│   ├── schema.php         # Strukturierte Daten (Schema.org/JSON-LD)
│   ├── sitemap.php        # XML-Sitemap /sitemap.xml
│   └── llms.php           # llms.txt – KI-/LLM-Hinweise
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

## Phasenplan (Optimierung & Entwicklung)

Dieser Plan beschreibt die Weiterentwicklung des Themes in kleinen, testbaren Schritten.  
Details zu Ist-Zustand, Zielarchitektur und Begründungen stehen ergänzend in `theme-audit-and-roadmap.md` im Projekt-Root.

### Phase A – Vor Livegang wichtig (High Impact)

- [x] **A1: Audit & Dokumentation**
  - Ziel: Gemeinsames Bild von Ist-Zustand, Risiken und Zielarchitektur.
  - Ergebnis: `theme-audit-and-roadmap.md` mit Analyse, Zielarchitektur und Roadmap.

- [x] **A2: REST-API-Hardening entschärfen**
  - Ziel: Gutenberg bleibt aus, REST-API wird nicht unnötig global blockiert.
  - Umsetzung: Entfernen der pauschalen `rest_authentication_errors`-Sperre aus `inc/cleanup.php`.
  - Test-Idee: REST-Endpunkte (`/wp-json/wp/v2/posts`) als Gast/Admin aufrufen, Classic Editor bleibt aktiv.

- [x] **A3: Theme-Options-Seite (Basis)**
  - Ziel: Zentrale, verständliche Verwaltungsoberfläche für Vereinsdaten und wichtige Theme-Einstellungen.
  - Umsetzung: Eigene Theme-Options-Seite „Ortsverein NV“ unter „Design“ mit Bereichen:
    - Verein & Organisation (Name, Kurzbeschreibung, Adresse)
    - Kontakt & Online-Auftritt (E-Mail, Telefon, Website, Link zum Kreisverband)
    - Zentrale Seiten (Kalender, Mitgliedschaft, Kontakt, Begegnungsstätte, Downloads, Intern)
    - SEO-Basis (Fallback-Meta-Description)
  - Test-Idee: Unter „Design → Ortsverein NV“ Werte setzen/ändern und später in strukturierten Daten / Links nutzen.

- [x] **A4: Initial-Content / Install-Logik**
  - Ziel: Nach Aktivierung direkt eine sinnvolle Grundstruktur ohne Dubletten erhalten.
  - Umsetzung: `inc/install.php` mit Initial-Setup auf `after_switch_theme`:
    - Legt fehlende Kernseiten an (Startseite, Kalender, Mitgliedschaft, Kontakt, Impressum, Datenschutz).
    - Legt ggf. Menüs an (Hauptmenü, Schnellzugriff, Footer) und befüllt sie grundlegend.
    - Setzt eine statische Startseite, falls noch keine gesetzt ist.
    - Befüllt zentrale Theme-Options (z. B. Kalender-/Mitgliedschaftsseite), wenn dort noch nichts eingetragen ist.
  - Test-Idee: Frische Testinstallation, Theme aktivieren, Seiten-/Menüstruktur und Startseite prüfen; erneute Aktivierung erzeugt keine Doppelstrukturen.

- [x] **A5: SEO-Basis (Canonical + robots)**
  - Ziel: Saubere SEO-Basis, ohne mit SEO-Plugins zu kollidieren.
  - Umsetzung:
    - `incs/seo.php` ergänzt um `ortsverein_nv_output_canonical()` und `ortsverein_nv_output_robots_meta()`:
      - Gibt Canonical-Link und robots-Meta nur aus, wenn kein gängiges SEO-Plugin (Yoast/RankMath) aktiv ist.
      - Canonical-URL ist filterbar über `ortsverein_nv_canonical_url`.
      - robots-Content ist filterbar über `ortsverein_nv_robots`.
  - Test-Idee: Seitenquelltext auf verschiedenen Seitentypen prüfen (mit/ohne SEO-Plugin), `<link rel="canonical">` und `<meta name="robots">` kontrollieren.

### Phase B – Strukturelle Verbesserungen

- [x] **B1: Schema-/LLM-Schicht trennen**
  - Ziel: Klare Trennung von klassischem SEO, Schema.org und zukünftigen KI-/LLM-Erweiterungen.
  - Umsetzung:
    - JSON-LD-/Schema-Logik aus `inc/seo.php` nach `inc/schema.php` ausgelagert.
    - `functions.php` lädt nun explizit `schema.php` (für strukturierte Daten) und `seo.php` (für Title/Meta/Social).
  - Test-Idee: Rich-Results-Test und Quelltext prüfen, sicherstellen, dass nur ein JSON-LD-Block vom Theme kommt und SEO-Meta-Tags weiter korrekt sind.

- [x] **B2: Slug-/Strukturrobustheit**
  - Ziel: Slug- und Strukturänderungen im Backend sollen die Theme-Funktionen nicht brechen.
  - Umsetzung:
    - Zentrale Seiten (Kalender, Mitgliedschaft, Kontakt, Begegnung) werden in Hero-Buttons, Teasern und Angebotskacheln bevorzugt über Theme-Options (`page_*`) aufgelöst; Slugs dienen nur noch als defensiver Fallback.
    - Schema-Events für die Kalenderseite nutzen die konfigurierte Kalenderseite (`page_calendar`) anstelle eines festen Slugs.
  - Test-Idee: Slugs von Kernseiten ändern, Zuordnungen über „Design → Ortsverein NV → Zentrale Seiten“ anpassen und prüfen, ob alle Links (Hero, Teaser, Kacheln, Schema) weiterhin korrekt funktionieren.

- [x] **B3: Sitemap**
  - Ziel: Einfache, wartbare Sitemap ohne Abhängigkeit von Plugins, aber kompatibel mit ihnen.
  - Umsetzung:
    - XML-Sitemap unter `/sitemap.xml` über `inc/sitemap.php` (Rewrite + `template_redirect`).
    - Enthält Startseite, alle veröffentlichten Seiten und (optional) Beiträge.
    - Gibt nichts aus, wenn ein gängiges SEO-Plugin erkannt wird (Yoast/RankMath).
  - Test-Idee: Permalinks einmal speichern (Rewrite flush), dann `/sitemap.xml` aufrufen und prüfen, ob alle relevanten Seiten/Beiträge gelistet sind.

### Phase C – Feinschliff / Optional

- [x] **C1: Performance-Feintuning**
  - Ziel: Möglichst hohe Lighthouse-/Core-Web-Vitals-Werte ohne Overengineering.
  - Umsetzung:
    - Header-Logo mit fester `height` und `decoding="async"` zur Reduktion von Layout-Shift und schnelleren Dekodierung.
    - Theme-JS (`ortsverein-nv-theme`) wird mit `defer` geladen, um den initialen Renderpfad nicht zu blockieren.
  - Test-Idee: Lighthouse/Audit auf Startseite und Unterseiten ausführen und insbesondere LCP/TBT prüfen; sicherstellen, dass Interaktivität (Burger, Kalender-Interaktion) weiterhin funktioniert.

- [x] **C2: Accessibility-Feinschliff**
  - Ziel: Barrierefreiheit von „gut“ auf „sehr gut“ bringen.
  - Umsetzung:
    - Kalender-Grid von unnötigen ARIA-Grid-Rollen befreit (keine fehlerhafte `grid`/`gridcell`-Hierarchie mehr, dafür klare Labels und Tastaturbedienung über `tabindex` + Enter/Space).
    - Mobile Navigation verbessert: Beim Öffnen wandert der Fokus auf den ersten Link im Menü, beim Schließen zurück auf den Burger; Escape schließt das Menü aus der Tastatur-Nutzung heraus.
  - Test-Idee: Lighthouse-/AXE-Check auf Start- und Kalenderseite (keine ARIA-Hierarchie-Warnung mehr), Tastatur-Only-Nutzung der Mobile-Navigation (Tab und Escape) und Kalender-Interaktion (Tab auf Tage mit Termin, Enter/Space zum Hervorheben).

- [x] **C3: KI-/LLM-Optimierungen**
  - Ziel: Theme-Inhalte für KI-/LLM-Modelle noch besser nutzbar machen – klar getrennt von klassischem SEO.
  - Umsetzung:
    - `inc/llms.php`: llms.txt unter `/llms.txt` bereitgestellt mit kompakten, maschinenlesbaren Angaben zu Site-URL, Vereinsname, Ort, Kurzbeschreibung, zentralen Inhaltsseiten und ICS-Feed.
    - llms.txt ist optional und wird über eigene Rewrite-/Query-Var-Logik ausgeliefert; ein Hook `ortsverein_nv_llms_txt` erlaubt Erweiterungen durch Child-Theme/Plugins.
  - Test-Idee: `/llms.txt` im Browser aufrufen, prüfen, ob URL, Name, zentrale Seiten (sofern zugewiesen) und ICS-URL korrekt gelistet sind.

---

## Weitere Inhalte / noch umzusetzen

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
