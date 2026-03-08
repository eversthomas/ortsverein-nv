# Entwicklungsplan – Theme ortsverein-nv

Stand: Fortschreibung nach Absprache. Dieses Dokument bündelt die verbindlichen Vorgaben und offenen Aufgaben.

---

## 1. Bereits umgesetzt (Referenz)

- Analyse HTML-Vorlage, Theme-Architektur, Basis-Templates (Header, Footer, front-page, page, index)
- CSS/JS aus Vorlage in `assets/`, kein Inline-Code
- **Gutenberg deaktiviert**, Classic Editor
- **Emojis deaktiviert** (`inc/cleanup.php`)
- **REST-API** nur für eingeloggte Administratoren (`manage_options`), sonst 401
- Kommentare deaktiviert, Embeds deaktiviert, weniger Dashboard-Widgets
- Customizer: Hero (Texte, Kacheln, Hintergrundbild, Button-Texte und Button-Zielseiten)
- SEO/Barrierefreiheit: Title-Tag, eine H1, Landmarken, Skip-Link, Fokus, prefers-reduced-motion

---

## 2. Gutenberg-Vorlagen / Seiteninhalte

- Im Theme ist der **Block-Editor deaktiviert**; neue Bearbeitung erfolgt im Classic Editor.
- **Bestehende Seiten**, die noch mit Gutenberg (Blöcken) erstellt wurden, können „Reste“ von Gutenberg enthalten (Block-Markup in der Datenbank).  
  **Empfehlung:** Diese Seiten einmalig im Classic Editor öffnen und speichern bzw. Inhalte in den Classic Editor übernehmen, damit nur noch Fließtext/Classic-Inhalt genutzt wird. Theme-seitig werden keine Block-Vorlagen geladen.

---

## 3. Kontaktseite (ohne Formular)

- **Kein Kontaktformular.** Kontaktseite enthält:
  - Kontaktdaten (Adresse, Telefon, E-Mail, Öffnungszeiten)
  - Link zur **Vereinssatzung** (URL z. B. im Customizer oder auf der Seite)
  - Weitere Kontaktmöglichkeiten (z. B. Hinweise zur Anreise, Ansprechpartner)
- Eigenes Template optional (`page-kontakt.php`), oder Standard-`page.php` mit reduziertem Hero (siehe unten).

---

## 4. Hero auf Unterseiten

- **Alle Unterseiten** (inkl. Impressum, Datenschutz, Kontakt, Begegnungsstätte, Beratung, etc.): **deutlich kleinerer Hero** (geringere Höhe) als auf der Startseite.
- Einheitliches Unterseiten-Hero-Layout im Theme (z. B. schlanker Bereich mit Seitentitel/Breadcrumb), dann Hauptinhalt.

---

## 5. Kalenderseite & ICS

- **Kalenderseite:** Termine aus einem **ICS-Feed** anzeigen.
  - **Option A:** ICS-URL im **Customizer** eintragbar (z. B. ein Feld „Kalender-ICS-URL“).
  - **Option B:** URL **statisch im Theme** einbauen, z. B.  
    `https://www.awo-kv-wesel.de/?rex-api-call=forcal_ical&category=23&filename=category-23`
- Aus dem ICS werden die Termine auf der **Kalenderseite** gerendert (Monatsansicht/Liste wie in der HTML-Vorlage).
- **Startseite:** Nur **aktuelle / nächste Termine** (Teaser) aus demselben ICS anzeigen.

Technik: ICS per HTTP abrufen (PHP oder JS), parsen (iCal-Format), im Theme ausgeben. Caching (Transient) empfohlen, damit der externe Feed nicht bei jedem Aufruf neu geladen wird.

---

## 6. Impressum & Datenschutz

- **Einfache Seiten:** Reduzierter Hero (wie alle Unterseiten), darunter **nur Fließtext** aus dem Classic Editor.
- Kein eigenes Layout nötig; reicht **page.php** mit dem reduzierten Unterseiten-Hero und `the_content()`.

---

## 7. Barrierefreiheit & SEO (durchgängig)

- **Barrierefreiheit** und **SEO** sind im Code und bei den Inhalten der Unterseiten mitzudenken:
  - Korrekte Überschriftenhierarchie, Landmarken, Fokus, Tastatur, ARIA wo nötig, Alt-Texte.
  - Sauberer Titel, sinnvolle Meta-Beschreibung, semantisches HTML, interne Verlinkung.
- **KI-SEO-Optimierung:** Bei der Konzeption der Unterseiten und Texte soll eine **KI-gestützte SEO-Optimierung** mitgedacht werden (z. B. Struktur und Formulierung so, dass sie von KI-Tools oder Redakteuren mit KI-Unterstützung gut genutzt werden können; keine Duplikate, klare H1/H2-Struktur, lesbare Absätze). Kein eigener „KI-Block“ im Theme – inhaltliche Ausrichtung.

---

## 8. Spezialseite „Begegnungsstätte“

- **Eigenes Template:** `page-begegnungsstaette.php` (oder Slug `begegnungsstaette` / `begegnung` je nach URL-Wunsch).
- **Inhalt:** Wechsel von **Bildern und Text** (Bild–Text–Bild–Text …), plus **Öffnungszeiten** (z. B. Tabelle oder Liste).
- **Startseite:** Teile davon können auf der Startseite vorkommen (z. B. Kurzinfo oder Link zur Begegnungsstätte mit Öffnungszeiten-Teaser).

---

## 9. Unterseite „Beratung“

- Eigenes Template oder klar definierter Aufbau mit **wechselnden Bild- und Text-Blöcken** sowie Kontakt-/Beratungshinweisen.
- Optional: Auszüge oder Verlinkung zur Beratungsseite auf der Startseite (analog Begegnungsstätte).

---

## 10. Offene Umsetzungsschritte (Priorisierung)

| Nr. | Aufgabe | Anmerkung |
|-----|--------|-----------|
| 1 | **Reduzierter Hero** für alle Unterseiten | Gemeinsames Layout (z. B. Template Part „hero-subpage“), geringere Höhe, Breadcrumb + Seitentitel |
| 2 | **page.php** anpassen | Unterseiten-Hero einbinden, dann `the_content()` |
| 3 | **Kontaktseite** | Template oder Inhalt: Kontaktdaten, Link Vereinssatzung, keine Formular-Logik |
| 4 | **Kalenderseite** | ICS-URL (Customizer oder statisch), PHP/JS zum Parsen, Darstellung Termine + Monatsansicht; Caching |
| 5 | **Startseite: nächste Termine** | Teaser aus ICS, nur aktuelle/künftige Termine |
| 6 | **Impressum / Datenschutz** | Mit reduziertem Hero, nur Editor-Inhalt |
| 7 | **Begegnungsstätte-Template** | Eigenes Template, Bild–Text-Wechsel, Öffnungszeiten |
| 8 | **Beratung-Template** | Eigenes Template oder Aufbau mit Bild–Text und Beratungsinfos |
| 9 | **Meta-Description** pro Seite | Optional im Theme oder per Plugin, SEO + ggf. KI-SEO-tauglich halten |

---

## 11. Technische Referenzen

- **ICS-Feed (Beispiel):**  
  https://www.awo-kv-wesel.de/?rex-api-call=forcal_ical&category=23&filename=category-23  
  (iCal für „Ortsverein Neukirchen-Vluyn“, forCal/REDAXO.)
- **Cleanup:** Gutenberg aus, Emojis aus, REST-API nur Admin, Kommentare aus, Embeds aus – siehe `inc/cleanup.php`.

Dieser Plan wird bei Bedarf fortgeschrieben.
