<?php
/**
 * Theme: Ortsverein NV
 * functions.php – lädt modulare Inc-Dateien (Reihenfolge relevant).
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ortsverein_nv_inc = get_template_directory() . '/inc/';

// Setup (Sprachen, Theme-Support, Menüs).
require_once $ortsverein_nv_inc . 'theme-setup.php';
// Assets (CSS/JS, kritisches CSS).
require_once $ortsverein_nv_inc . 'enqueue.php';
// Funktionen für Templates (Home-URL, etc.).
require_once $ortsverein_nv_inc . 'template-functions.php';
// Install-/Initial-Logik (Seiten, Menüs, Optionen).
require_once $ortsverein_nv_inc . 'install.php';
// ICS-Kalender.
require_once $ortsverein_nv_inc . 'ics.php';
// Customizer.
require_once $ortsverein_nv_inc . 'customizer.php';
// Theme-Options-Seite (Backend).
require_once $ortsverein_nv_inc . 'theme-options.php';
// Cleanup (Gutenberg aus, Emojis, Embeds, Frontend-Assets abmelden).
require_once $ortsverein_nv_inc . 'cleanup.php';
// Head-Cleanup (RSD, WLW, Generator, REST-Link).
require_once $ortsverein_nv_inc . 'head-cleanup.php';
// Accessibility (ARIA, aria-current).
require_once $ortsverein_nv_inc . 'accessibility.php';
// KI-/LLM-Hinweisedatei (llms.txt).
require_once $ortsverein_nv_inc . 'llms.php';
// Sitemap (XML).
require_once $ortsverein_nv_inc . 'sitemap.php';
// Schema / strukturierte Daten.
require_once $ortsverein_nv_inc . 'schema.php';
// SEO-Basis (Title, Meta-Description-Hook, Social-Meta).
require_once $ortsverein_nv_inc . 'seo.php';
// Redaktions-Doku (BS-Plugin, nur wirksam wenn das Plugin aktiv ist).
require_once $ortsverein_nv_inc . 'redaktions-doku.php';
