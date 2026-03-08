# Analyse der HTML-Vorlage – ortsverein-nv

## 1. Überblick

Die Datei `html-vorlage/index.html` ist eine **vollständige statische One-Page-Website** mit eingebettetem CSS (im `<style>`-Block) und eingebettetem JavaScript (am Ende des `<body>`). Sie dient als Design- und Strukturreferenz für das WordPress-Theme.

**Referenzierte Assets:**
- `logo.svg` – vorhanden im Ordner `html-vorlage/`
- `mj-haus-schmal.jpg` – **nicht vorhanden**, im Hero als Hintergrundbild referenziert (Platzhalter)

**Technische Merkmale:**
- Semantisches HTML5 (`header`, `nav`, `main`, `section`, `footer`)
- Skip-Link, ARIA-Attribute, Fokuszustände, `prefers-reduced-motion`
- Responsive (Breakpoints ca. 900px, 600px)
- Keine externen CSS-/JS-Dateien; alles inline

---

## 2. Identifizierte strukturelle Bereiche

### 2.1 Skip-Link (Zeile 534)
- **Element:** `<a class="skip-link" href="#main">Zum Inhalt springen</a>`
- **Zweck:** Barrierefreiheit – Tastatur nutzer springen zum Hauptinhalt
- **Zuordnung:** Wiederverwendbare Komponente / Teil des globalen Layouts (z. B. in `header.php` oder direkt nach `<body>`)

---

### 2.2 Header (Zeilen 537–553)
- **ID:** `#site-header`, `role="banner"`
- **Inhalt:**
  - **Brand:** Logo + Text „AWO Team“ / „Neukirchen-Vluyn“, Link zur Startseite
  - **Hauptnavigation (Desktop):** Begegnung, Mitgliedschaft, Beratung, Termine, Intern
  - **Nav-Divider** (dekorativ)
  - **Service-Links:** Kontakt, Downloads, Kalender
  - **Burger-Button** (Mobile), `aria-expanded`, `aria-controls="mobile-nav"`
- **Zuordnung:** Globale Template-Datei → **header.php**

---

### 2.3 Mobile Navigation (Zeilen 556–581)
- **Klasse:** `.mobile-nav`, **ID:** `mobile-nav`, `aria-label="Mobile Navigation"`
- **Inhalt:** Gleiche Menüpunkte wie Desktop + Schnellzugriff (Kontakt, Downloads, Kalender), mit Icons
- **Zuordnung:** Kann in `header.php` integriert bleiben (gleiche Menüquelle wie Desktop) oder als eigenes Template-Part; **Menüquelle = WordPress Menü**

---

### 2.4 Main (Zeile 584)
- **ID:** `#main` – Ziel des Skip-Links
- **Inhalt:** Alle Sektionen von Hero bis Kontakt (vor dem Footer)
- **Zuordnung:** In WordPress: `front-page.php` (Startseite) bzw. `page.php` + Unterseiten; `<main id="main">` in allen Templates verwenden

---

### 2.5 Hero (Zeilen 587–651)
- **ID:** `#hero`, `aria-labelledby="hero-h1"`
- **Inhalt:**
  - Hintergrundbild (Platzhalter `mj-haus-schmal.jpg`), dekorative Shapes
  - **Hero-Inner:** Zweispaltig (Content + Visual)
  - **Hero-Content:** Eyebrow „Neukirchen-Vluyn“, H1, Lead-Text, zwei CTAs (Termine, Mitglied werden)
  - **Hero-Visual:** Kartenstapel mit drei Stat-Karten (Mitglieder, Angebote, „Seit 1952“)
- **Zuordnung:** **A) Startseitenbereich** → Template-Part **template-parts/hero.php** (nur auf der Startseite)

---

### 2.6 Angebotskacheln (Zeilen 654–721)
- **ID:** `#angebote`, Klasse `section-padding`, `aria-labelledby="angebote-h2"`
- **Inhalt:**
  - Section-Header (Label „Unsere Angebote“, H2, Beschreibungstext)
  - **Card-Grid** mit 5 Kacheln (Links zu Ankern): Begegnung, Mitgliedschaft, Beratung, Termine, Intern
  - Jede Karte: Icon, Titel, Kurzbeschreibung, CTA-Text
- **Zuordnung:** **A) Startseitenbereich** → Template-Part **template-parts/angebot-kacheln.php** (nur Startseite). Section-Header ist wiederverwendbar → **template-parts/section-header.php**

---

### 2.7 Begegnung (Zeilen 728–786)
- **ID:** `#begegnung`, Klasse `bg-rot-hell`
- **Inhalt:** Breadcrumb, Section-Label, H2 „Unsere Begegnungsstätte“, Fließtext, Info-Box Adresse, Tabelle „Wöchentliche Standardtermine“, Back-to-top-Link
- **Zuordnung:** **B) Eigene Unterseite** → WordPress-Seite „Begegnung“ mit **page-begegnung.php** (oder generisches `page.php` + Seiten-Slug). In der Vorlage als Anker-Section; in WP sinnvoll als eigene Seite mit festem Layout.

---

### 2.8 Mitgliedschaft (Zeilen 789–848)
- **ID:** `#mitgliedschaft`
- **Inhalt:** Breadcrumb, Label, H2 „Mitglied werden“, Text, Benefits-Liste, Buttons (Mitgliedsantrag, Kontakt), Beitrags-Box (Platzhalter Beträge)
- **Zuordnung:** **B) Eigene Unterseite** → z. B. **page-mitgliedschaft.php** oder Seite mit Slug `mitgliedschaft`

---

### 2.9 Beratung (Zeilen 851–911)
- **ID:** `#beratung`, Klasse `bg-grau`
- **Inhalt:** Breadcrumb, Label, H2 „Beratung & OV-Büro“, Text, Info-Boxen (Beratungstermine, Vertraulichkeit), Themen-Liste, CTA „Termin anfragen“
- **Zuordnung:** **B) Eigene Unterseite** → **page-beratung.php** oder Seite „Beratung“

---

### 2.10 Termine (in der HTML nur als Ziel für Links; eigener Block fehlt im Ausschnitt)
- In der Vorlage wird `#termine` in der Nav und in den Kacheln verlinkt. Ein eigener Abschnitt „Termine“ mit **Terminliste + Filter** (Filter-Buttons „Alle“ / „Woche“, `.termine-liste`, `.termin-item`) ist im CSS definiert (Zeilen 322–371) und im JS (Zeilen 771–811) mit Daten und `renderTermine` umgesetzt.
- **Hinweis:** Im vorliegenden HTML steht **kein** sichtbarer Block mit `id="termine"` und der Terminliste. Die Termin-Logik (Daten, Filter, Rendering) ist im Script vorhanden; das Markup für den Termin-Bereich müsste z. B. zwischen „Angebote“ und „Begegnung“ oder an anderer Stelle ergänzt werden. Für WordPress: **A) Startseitenbereich** (Termin-Teaser/Liste) ODER **B) eigene Unterseite** „Termine“ mit Kalender + Liste. Empfehlung: eigener Bereich auf der Startseite (Termin-Teaser) + optional eigene Seite „Termine“ mit voller Liste/Kalender.

---

### 2.11 Kalender (Zeilen 914–958)
- **ID:** `#kalender`, Klasse `bg-grau`
- **Inhalt:** Breadcrumb, Label, H2 „Kalender“, Monatsnavigation (Vor/Zurück), Kalender-Grid (JS-generiert), Legende, Terminliste für den Monat (JS)
- **Zuordnung:** **B) Eigene Unterseite** „Kalender“ ODER **A)** auf Startseite. Wegen Umfang und JS-Logik sinnvoll als **eigene Unterseite** mit **page-kalender.php** oder Slug `kalender`, Kalender-JS in Theme übernehmen.

---

### 2.12 Kontakt (Zeilen 961–1041)
- **ID:** `#kontakt`, Klasse `bg-rot-hell`
- **Inhalt:** Breadcrumb, Label, H2 „Kontakt aufnehmen“, Kontaktformular (Name, E-Mail, Betreff, Nachricht), Erfolgsmeldung, Sidebar: Adresse, Telefon, E-Mail, Öffnungszeiten
- **Zuordnung:** **B) Eigene Unterseite** → **page-kontakt.php** oder Seite „Kontakt“. Formular: später mit WordPress oder Plugin an Backend anbinden; Markup und Styling aus Vorlage übernehmen.

---

### 2.13 Downloads
- In der Vorlage wird `#downloads` in Nav und Kacheln verlinkt; ein **eigener Section-Block „Downloads“** mit `.download-grid`, `.download-item` ist nur im **CSS** (Zeilen 419–458) definiert, nicht im sichtbaren HTML-Markup.
- **Zuordnung:** **B) Eigene Unterseite** „Downloads“ mit Liste von Downloads (WP-Medien oder Custom Post Type / normale Seiten). Template-Part für **eine Download-Karte** als **C) wiederverwendbare Komponente** möglich.

---

### 2.14 Intern (nur Link in Nav/Kachel)
- `#intern` verweist auf einen „Intern“-Bereich (nur für Ehrenamtliche). In der Vorlage keine eigene Section mit Inhalt.
- **Zuordnung:** **B) Eigene Unterseite** „Intern“ (evtl. mit Login-Pflicht per WordPress/Plugin), oder nur Menüpunkt zu externem Tool/Login.

---

### 2.15 Footer (Zeilen 1045–1063)
- **ID:** `#site-footer`, `role="contentinfo"`
- **Inhalt:** Logo/Brand, Links (Impressum, Datenschutz, Barrierefreiheit, Kontakt), „Prototyp“-Hinweis
- **Zuordnung:** Globale Template-Datei → **footer.php**

---

### 2.16 Wiederverwendbare Komponenten (C)

| Komponente              | Verwendung in Vorlage                    | Theme-Umsetzung                    |
|-------------------------|------------------------------------------|------------------------------------|
| **Section-Header**      | Angebote, Begegnung, Mitgliedschaft, …   | **template-parts/section-header.php** (Label + H2 + optionaler Text) |
| **Card (Kachel)**       | 5× Angebotskacheln                       | Template-Part oder PHP-Schleife mit Parametern (Titel, Text, URL, Icon/Farbe) |
| **Breadcrumb**          | Alle Unterseiten-Sections                | **template-parts/breadcrumb.php** (für Unterseiten) |
| **Info-Box**            | Begegnung (Adresse), Beratung (Termine)  | CSS-Klassen `.info-box`, `.info-box.blau` etc.; Inhalt aus Editor/Meta |
| **Back-to-top**         | Am Ende jeder Section                    | **template-parts/back-to-top.php** (Link zur Startseite oder #main) |
| **Buttons**             | `.btn`, `.btn-primary`, `.btn-secondary` | Nur CSS + semantisches `<a>` / `<button>` in Templates |
| **Termin-Item**         | Terminliste (JS-rendered)                 | Template-Part **template-parts/termin-item.php** oder PHP-Schleife über Termine |
| **Kontaktformular**     | Nur Kontakt-Section                      | Eigenes Template-Part oder in **page-kontakt.php**; später WP/Plugin anbinden |
| **Download-Item**       | Nur im CSS definiert                     | **template-parts/download-item.php** für eine Zeile der Download-Liste |

---

## 3. Zuordnung: Startseite vs. Unterseiten vs. Komponenten

### A) Nur Startseite (front-page.php)

- Skip-Link (global, aber inhaltlich „Zum Inhalt“)
- **Header** (global)
- **Hero** → template-parts/hero.php
- **Angebotskacheln** → template-parts/angebot-kacheln.php
- **Termine (Teaser)** → optional: kurze Terminliste oder „Nächste Termine“ auf der Startseite (template-parts/termine-teaser.php)
- **Footer** (global)

### B) Eigene Unterseiten (eigene Templates oder page.php + Slug)

| Seite (WP)   | Template-Vorschlag   | Inhalt grob |
|--------------|----------------------|-------------|
| Begegnung    | page-begegnung.php   | Breadcrumb, H2, Text, Adresse, Tabelle Öffnungszeiten, Back-to-top |
| Mitgliedschaft | page-mitgliedschaft.php | Breadcrumb, H2, Text, Benefits, Beitragsbox, Buttons |
| Beratung     | page-beratung.php    | Breadcrumb, H2, Text, Info-Boxen, Themenliste, CTA |
| Termine      | page-termine.php     | Terminliste + Filter (evtl. Kalender-Teaser) |
| Kalender     | page-kalender.php    | Kalender-Navigation, Kalender-Grid, Monats-Termine (JS wie in Vorlage) |
| Kontakt      | page-kontakt.php     | Formular + Kontaktdaten-Sidebar |
| Downloads    | page-downloads.php   | Download-Liste (Template-Parts für Einträge) |
| Intern       | page-intern.php oder Redirect | Login/Info für Ehrenamtliche |
| Impressum    | page-impressum.php / Standard page.php | Rechtstext |
| Datenschutz  | page-datenschutz.php / Standard | Rechtstext |
| Barrierefreiheit | page-barrierefreiheit.php / Standard | Erklärung |

### C) Wiederverwendbare Template-Parts

- **section-header.php** – Label + H2 + optionaler Beschreibungstext
- **hero.php** – nur für Startseite
- **angebot-kacheln.php** – nur für Startseite (nutzt ggf. eine generische **card**-Komponente)
- **breadcrumb.php**
- **back-to-top.php**
- **termin-item.php** (einzelner Termin-Eintrag)
- **download-item.php** (einzelner Download-Eintrag)

---

## 4. Geplante WordPress-Seitenstruktur (Redaktion)

- **Startseite** (festes Layout): Hero, Angebotskacheln, optional Termin-Teaser.
- **Statische Seiten** (mit festen Templates wo nötig):  
  Begegnung, Mitgliedschaft, Beratung, Termine, Kalender, Kontakt, Downloads, Intern, Impressum, Datenschutz, Barrierefreiheit.
- **Navigation:** Ein WordPress-Menü (Hauptmenü) mit diesen Seiten; Service-Links (Kontakt, Downloads, Kalender) können dasselbe Menü oder ein zweites Menü sein. Intern-Link nur für eingeloggte Nutzer oder immer sichtbar mit Hinweis „nur für Ehrenamtliche“.

---

## 5. CSS- und JS-Struktur in der Vorlage

### CSS (auslagern nach assets/css/)

- **Design-System** (:root Variablen) → Basis
- **Reset & Base** → Basis
- **Layout** (.container, .section-padding)
- **Skip-Link**
- **Header** (inkl. Burger, Mobile-Nav)
- **Hero**
- **Buttons**
- **Cards/Kacheln**
- **Termine** (Liste, Filter, .termin-item)
- **Section-Styles** (.section-label, .section-header, Zebra .bg-grau, .bg-rot-hell)
- **Intern Notice**
- **Formular** (.kontakt-form, .form-group, .form-success)
- **Downloads** (.download-grid, .download-item)
- **Kalender** (.kalender-nav, .kalender-grid, .kal-day …)
- **Footer**
- **Subsections** (Breadcrumb, .info-box, .benefits-list, .two-col, .back-to-top)
- **Responsive** (Media Queries)
- **Animation** (.fade-in)

Empfehlung: Eine Hauptdatei z. B. `theme.css` (oder aufgeteilt in `base.css`, `components.css`, `layout.css`) in `assets/css/`, aus Vorlage übernommen und ohne Inline-Styles.

### JavaScript (auslagern nach assets/js/)

- Termin-Daten + renderTerminItem + renderTermine + Filter-Buttons
- Kalender: renderKalender, Prev/Next, Monats-Termine
- Sticky Header (scroll)
- Burger-Menü (open/close, aria-expanded, body overflow)
- Kontaktformular (Validierung, Success-Anzeige)
- Fade-in (IntersectionObserver)
- Smooth Scroll für Anker-Links (mit prefers-reduced-motion)
- Responsive Kalender-Layout (resize)

Eine gebündelte Datei z. B. `theme.js` in `assets/js/`, keine Inline-Scripts im Theme.

---

## 6. Barrierefreiheit & SEO (bereits in Vorlage)

- Skip-Link, Landmarken (banner, main, contentinfo), aria-labelledby bei Sections
- Fokuszustände (outline), Tastaturbedienung, Burger mit aria-expanded/aria-controls
- Kalender: role="grid", gridcell, aria-label
- Formular: Labels, aria-required, role="alert" für Fehler, Success mit role="alert"
- prefers-reduced-motion für Animationen und scroll-behavior
- Eine H1 pro Seite (Hero), klare H2-Hierarchie
- Semantisches HTML; in WordPress: sauberer `<title>`, Meta-Description, gleiche Struktur beibehalten

---

## 7. Nächste Schritte (für Theme-Implementierung)

1. **Theme-Architektur** wie in der Aufgabenstellung anlegen (inc/, template-parts/, assets/).
2. **CSS/JS** aus der Vorlage in `assets/css/` und `assets/js/` übernehmen, ohne Inline-Code.
3. **header.php** und **footer.php** aus Header- und Footer-HTML ableiten; Navigation mit `wp_nav_menu()`.
4. **front-page.php**: Hero, Angebotskacheln, ggf. Termin-Teaser; Anker-Links durch echte Seiten-URLs ersetzen.
5. **page.php** als Fallback; **page-{slug}.php** für Begegnung, Mitgliedschaft, Beratung, Termine, Kalender, Kontakt, Downloads, Intern wo nötig.
6. **Template-Parts** für section-header, breadcrumb, back-to-top, ggf. card, termin-item, download-item erstellen und einbinden.
7. **Cleanup** (Gutenberg, Kommentare, etc.) in **inc/cleanup.php**.
8. **SEO & Barrierefreiheit**: Titel, Meta, H1-Regel, Skip-Link, Fokus, keine neuen Inline-Styles/Scripts.

Diese Analyse bildet die Grundlage für die Theme-Architektur und die Überführung der HTML-Vorlage in das WordPress-Theme **ortsverein-nv**.
