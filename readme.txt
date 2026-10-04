=== Najdi svůj sen ===
Contributors: Filozofická fakulta Univerzity Palackého v Olomouci
Requires at least: 7.0
Tested up to: 7.1
Requires PHP: 8.2
Stable tag: 0.3.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Theme for najdisvujsen.cz, the applicant website of the Faculty of Arts, Palacký University Olomouc.

== Description ==

A classic PHP theme with a locked-down block editor: editors change content,
the theme controls the design. It has no build step and no plugin dependencies.

The front page and study program pages are rendered from regular page
content. The content is split into sections by level 2 headings and each
section is matched by its HTML anchor (Advanced > HTML anchor in the editor).

Front page anchors:

* (text before the first heading) - "Why FF UP" facts
* univerzitni-mesto - university town
* programy - program catalogue: a level 3 heading per study level, a level 4
  heading per category and a list of links to programs
* dod - open days; dates are read from a sentence such as
  "v pátek 27. 11. 2026 od 8-14 hodin"
* slovensko - students from Slovakia

Study program pages are pages with a department filled in the "Záhlaví stránky"
box. Anchors with their own layout: proc, programy, olomouc, uplatneni,
zahranici, lide. Other sections are shown as text. Images with the "Fotopás"
block style become section photos.

Page fields ("Záhlaví stránky" box): short title, department and its website,
social profiles, careers shown in the header (one per line) and an emoji.
The page excerpt is the lead text, the featured image is the header photo.

Customizer:

* Přijímací řízení - application and admission information links
* Titulní stránka - YouTube video on the front page

== Installation ==

1. Upload the theme folder to wp-content/themes/najdisvujsen.
2. Activate it in Appearance > Themes.
3. Set a static front page in Settings > Reading.

When switching from the previous Divi-based site, page content has to be
converted to blocks and the page fields filled in before the theme is
activated on the live site; Divi shortcodes are not rendered. Deactivate
plugins that depend on Divi (Divi Pixel, DiviFlash).

== Copyright ==

Najdi svůj sen WordPress Theme, Copyright 2026 Filozofická fakulta
Univerzity Palackého v Olomouci.
Najdi svůj sen is distributed under the terms of the GNU GPL.

This theme bundles the following third-party resources:

Dederon Sans Std, Copyright Tomáš Brousil, Suitcase Type Foundry
License: proprietary; licensed to Filozofická fakulta Univerzity Palackého
for use on najdisvujsen.cz. The font files are not covered by the GPL and
must not be redistributed.
Source: https://www.suitcasetype.com/

Lucide icons, Copyright Lucide Contributors and Cole Bemis (Feather)
License: ISC
Source: https://lucide.dev/

== Changelog ==

= 0.3.0 =
* Campaign design for the front page and study program pages.

= 0.2.0 =
* Page header fields, block styles and admission call to action.

= 0.1.0 =
* Initial release.
