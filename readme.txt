=== Najdi svůj sen ===
Contributors: Filozofická fakulta Univerzity Palackého v Olomouci
Requires at least: 7.0
Tested up to: 7.1
Requires PHP: 8.2
Stable tag: 0.5.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Theme for najdisvujsen.cz, the applicant website of the Faculty of Arts, Palacký University Olomouc.

== Description ==

A classic PHP theme with a locked-down block editor: editors change content,
the theme controls the design. It has no build step and no plugin dependencies.

Study program pages ("Obory" in the admin, post type najdisvujsen_obor) and
the front page are edited in a form instead of the block editor: tabs for
page sections, repeatable items (programs, teachers, reasons...), media
pickers and a simple text editor with paragraphs, lists, links, a subheading
and a highlight box. Values are stored as post meta (_najdisvujsen_obor,
_najdisvujsen_front) with revisions; a readable copy is kept in the post
content. Preview shows unsaved changes to the current user only.

Study program pages keep top-level URLs (/historie/). Programs marked "Zobrazit
v katalogu" are listed in the front page catalogue under the category of their
page, sorted alphabetically; programs without a page are added on the front
page form. Open day dates are hidden after they pass.

Roles: administrators and editors manage everything. The "Správce oboru" role
edits only study program pages assigned on the user's profile screen and
cannot create, delete or unpublish them or change their URL.

Admin screens:

* Titulní stránka - front page form
* Obory - study program pages, guide for editors (Návod)
* Nastavení webu - application and admission information links

Other pages are regular block editor pages.

== Installation ==

1. Upload the theme folder to wp-content/themes/najdisvujsen.
2. Activate it in Appearance > Themes.
3. Set a static front page in Settings > Reading.

When switching from the previous Divi-based site, the content of study
program pages and the front page has to be moved into the forms before the
theme is activated on the live site; Divi shortcodes are not rendered.
Deactivate plugins that depend on Divi (Divi Pixel, DiviFlash).

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

= 0.5.3 =
* Program page title bubble: whole words, text optically centred.
* Teachers one per row on phones.
* Parallax background in the program page header.

= 0.5.2 =
* Mobile layout fixes: header with the admin bar, menu, catalogue, open days, trains.

= 0.5.1 =
* Video in the university town section of the front page, played on the page.
* Notes below program cards use the full width.

= 0.5.0 =
* Form-based editing of study program pages and the front page.
* Study program post type with top-level URLs, program editor role.
* Program catalogue built from study program pages.
* Site settings screen and editor guide.

= 0.4.0 =
* Uniform study program cards with study level tabs.
* Uniform teachers section.
* Parallax background in the admission call to action.

= 0.3.0 =
* Campaign design for the front page and study program pages.

= 0.2.0 =
* Page header fields, block styles and admission call to action.

= 0.1.0 =
* Initial release.
