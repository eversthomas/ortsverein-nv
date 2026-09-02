<?php
/**
 * Registriert Theme-Doku für das Plugin BS Redaktions-Doku.
 *
 * Texte: Cursor/doku-theme-ortsverein-nv.md (Leitfaden, Leichte Sprache).
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hängt Theme-Doku-Einträge an den Plugin-Hook.
 *
 * @return void
 */
function ortsverein_nv_register_redaktions_doku() {
	if ( ! function_exists( 'bsrd_register_theme_doc' ) ) {
		return;
	}

	bsrd_register_theme_doc(
		'ortsverein-nv',
		array(
			'key'      => 'design-ortsverein-nv',
			'title'    => 'Design: Ortsverein NV (Vereinsdaten)',
			'category' => 'Theme-Besonderheiten',
			'screens'  => array( 'appearance_page_ortsverein_nv_options' ),
			'content'  => '<p>Unter Design -> Ortsverein NV findet sich eine eigene Einstellungsseite für alle Daten, die speziell den Ortsverein betreffen. Hier werden zentrale Informationen gepflegt - sie wirken sich auf mehrere Stellen der Website aus, z. B. auf die Startseite, Kontaktangaben und die Suchmaschinenoptimierung.</p>
<p><strong>Wie du diese Seite öffnest</strong></p>
<ol>
<li>Klick links auf Design</li>
<li>Klick im aufgeklappten Untermenü auf Ortsverein NV</li>
<li>Du siehst nun vier Bereiche mit Eingabefeldern</li>
<li>Füll die gewünschten Felder aus</li>
<li>Klick unten auf Änderungen speichern</li>
</ol>
<p><strong>Die vier Bereiche im Überblick</strong></p>
<p><em>Verein &amp; Organisation</em> - Grunddaten des Ortsvereins. Erscheinen u. a. in strukturierten Daten für Suchmaschinen und können im Footer oder auf der Kontaktseite angezeigt werden.</p>
<ul>
<li>Name des Ortsvereins - z. B. AWO Ortsverein Muster e. V.</li>
<li>Kurzbeschreibung - ein kurzer Satz über den Verein (erscheint z. B. in Suchergebnissen)</li>
<li>Adresse - vollständige Vereinsadresse</li>
</ul>
<p><em>Kontakt &amp; Online-Auftritt</em> - Kontaktdaten, die Besuchern helfen, den Verein schnell zu erreichen.</p>
<ul>
<li>E-Mail-Adresse</li>
<li>Telefonnummer</li>
<li>Website des Ortsvereins (falls abweichend)</li>
<li>Link zum AWO-Kreisverband oder Verbund</li>
</ul>
<p><em>Zentrale Seiten</em> - Verknüpfung der wichtigsten Seiten mit dem Theme. Das Theme nutzt diese Zuordnung intern, z. B. für Links auf der Startseite oder im Kalender.</p>
<ul>
<li>Kalenderseite - die Seite mit dem Veranstaltungskalender</li>
<li>Mitgliedschaftsseite</li>
<li>Kontaktseite</li>
<li>Seite Begegnungsstätte</li>
<li>Downloadseite (optional) - z. B. für Satzung, Formulare</li>
<li>Interner Bereich (optional) - für Ehrenamtliche</li>
</ul>
<p><strong>Was bedeutet das?</strong> Ausgewählt wird aus einer Liste vorhandener Seiten. Wurde z. B. eine neue Kalenderseite erstellt, wird sie hier ausgewählt, damit das Theme sie korrekt verlinken kann.</p>
<p><em>SEO-Basis</em> - Einfache Grundeinstellungen für Suchmaschinen.</p>
<ul>
<li>Standard-Meta-Description - ein kurzer Text, der in Google-Suchergebnissen erscheint, wenn eine Seite keine eigene Beschreibung hat</li>
</ul>
<p><strong>Tipp:</strong> Immer speichern nicht vergessen! Am Ende der Seite auf Änderungen speichern klicken - sonst gehen Eingaben beim Verlassen der Seite verloren.</p>',
		)
	);

	bsrd_register_theme_doc(
		'ortsverein-nv',
		array(
			'key'      => 'customizer',
			'title'    => 'Design: Customizer (theme-spezifische Optionen)',
			'category' => 'Theme-Besonderheiten',
			'screens'  => array( 'customize' ),
			'content'  => '<p>Der Customizer erlaubt es, bestimmte visuelle Einstellungen der Website zu verändern und eine Vorschau in Echtzeit zu sehen. Erreichbar über Design -> Customizer. Welche Optionen hier konkret erscheinen, hängt vom Theme ab - für <code>ortsverein-nv</code> sind das:</p>
<ul>
<li>Website-Titel und Untertitel (Darstellung im Browser-Tab)</li>
<li>Logo hochladen oder austauschen</li>
<li>Favicons (das kleine Symbol im Browser-Tab)</li>
<li>Startseite und Blogseite festlegen</li>
</ul>
<p><strong>So gehst du vor</strong></p>
<ol>
<li>Links auf Design, dann auf Customizer klicken</li>
<li>Die Website öffnet sich rechts als Vorschau</li>
<li>Links die Einstellungsbereiche sehen - klick auf einen davon</li>
<li>Änderungen vornehmen - die Vorschau aktualisiert sich sofort</li>
<li>Oben auf Veröffentlichen klicken, damit die Änderungen live gehen</li>
</ol>
<p><strong>Bitte diese Bereiche im Customizer NICHT anfassen:</strong> Vorlagen / Template-Editor (Layouts können kaputt gehen), Theme-Editor unter Design (enthält den Programmcode - Änderungen können die Website komplett unbrauchbar machen). Bei Fragen zu Layout oder Design die zuständige Person kontaktieren.</p>',
		)
	);

	bsrd_register_theme_doc(
		'ortsverein-nv',
		array(
			'key'      => 'menuebereiche',
			'title'    => 'Die drei Menübereiche des Themes',
			'category' => 'Theme-Besonderheiten',
			'screens'  => array( 'nav-menus' ),
			'content'  => '<p>Das Theme <code>ortsverein-nv</code> hat drei benannte Menübereiche - diese Aufteilung ist theme-spezifisch, andere Themes können andere Menübereiche haben.</p>
<table>
<thead>
<tr><th>Menübereich</th><th>Wo sichtbar?</th></tr>
</thead>
<tbody>
<tr><td>Hauptmenü</td><td>Hauptnavigation der Website (oben oder im Header)</td></tr>
<tr><td>Schnellzugriff</td><td>Zusätzliche Links oben rechts (Kontakt, Downloads, Kalender)</td></tr>
<tr><td>Footer</td><td>Unten auf jeder Seite (Impressum, Datenschutz, Barrierefreiheit)</td></tr>
</tbody>
</table>
<p>Die grundsätzliche Bedienung (Seite zum Menü hinzufügen, Reihenfolge ändern) ist in der allgemeinen WordPress-Basis-Doku unter "Menüs pflegen" beschrieben - hier nur die Zuordnung, welcher Bereich wofür gedacht ist.</p>',
		)
	);

	bsrd_register_theme_doc(
		'ortsverein-nv',
		array(
			'key'      => 'widget-options',
			'title'    => 'Widgets für einzelne Seiten steuern - mit Widget Options',
			'category' => 'Theme-Besonderheiten',
			'screens'  => array( 'widgets' ),
			'content'  => '<p>Normalerweise erscheinen alle Widgets in der Sidebar auf jeder Seite gleichzeitig. Das (nicht zum WordPress-Kern gehörende, zusätzlich installierte) Plugin <strong>Widget Options</strong> gibt die Möglichkeit, pro Widget gezielt einzustellen, auf welchen Seiten es sichtbar sein soll - und auf welchen nicht.</p>
<p>Praktisches Beispiel: ein Widget mit Kontaktdaten für die Seite Über uns, und ein anderes mit dem Hinweis auf die Mitgliedschaft für die Angebotsseite - ohne dass etwas doppelt angelegt werden muss.</p>
<p><strong>Widget Options nutzen - so geht es</strong></p>
<ol>
<li>Geh zu Design -> Widgets</li>
<li>Klick auf ein Widget, um es aufzuklappen</li>
<li>Unten im Widget erscheint ein neuer Bereich namens Sichtbarkeit</li>
<li>Klick auf Regel hinzufügen</li>
<li>Wähl aus dem ersten Dropdown: Seite</li>
<li>Wähl aus dem zweiten Dropdown: ist eine bestimmte Seite</li>
<li>Wähl im dritten Feld die gewünschte Seite aus der Liste aus</li>
<li>Klick oben rechts auf Aktualisieren um zu speichern</li>
</ol>
<p><strong>Wichtig: Anzeigen oder Verstecken?</strong> Beim Anlegen einer Regel kann oben gewählt werden: Anzeigen (Widget erscheint NUR auf den ausgewählten Seiten) oder Verstecken (Widget erscheint überall AUSSER auf den ausgewählten Seiten). Für die meisten Fälle ist Anzeigen die einfachere Wahl.</p>
<p><strong>Praxisbeispiel: Unterschiedliche Widgets je Seite</strong></p>
<p>Ziel: auf der Seite Über uns ein Widget mit Vereinsadresse und Ansprechpartner, auf der Seite Angebote ein Widget mit Hinweis auf die Mitgliedschaft, auf allen anderen Seiten gar kein Widget.</p>
<ol>
<li>Widget 1 anlegen: Absatz-Widget, Inhalt: Vereinsadresse und Ansprechpartner eintippen</li>
<li>Bei Widget 1 unter Sichtbarkeit: Anzeigen -> Seite ist -> Über uns auswählen</li>
<li>Widget 2 anlegen: Absatz-Widget, Inhalt: Hinweis auf Mitgliedschaft eintippen</li>
<li>Bei Widget 2 unter Sichtbarkeit: Anzeigen -> Seite ist -> Angebote auswählen</li>
<li>Aktualisieren klicken - fertig! Jede Seite zeigt nun ihr eigenes Widget.</li>
</ol>
<p><strong>Tipp:</strong> Mehrere Seiten für ein Widget auswählen - nochmals auf Regel hinzufügen klicken und eine weitere Seite auswählen. Das Widget erscheint dann auf allen Seiten, für die eine Anzeigen-Regel angelegt wurde.</p>',
		)
	);
}

add_action( 'bsrd_register_theme_docs', 'ortsverein_nv_register_redaktions_doku' );
