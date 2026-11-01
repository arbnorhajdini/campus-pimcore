# Campus Zentralschweiz

Eine kleine Demo-Kursplattform für eine fiktive Weiterbildungsschule mit mehreren Standorten.
Gebaut mit OpenDXP (Pimcore), Symfony, Twig, SCSS und Vanilla JavaScript.

> **Status:** im Aufbau. Die Checkliste unten zeigt, was schon fertig ist.

## Worum es geht

Das Projekt zeigt, wie ich ein Pimcore-Projekt aufbaue: von den Data Objects über Symfony-Controller
bis zu sauberem, barrierefreiem Frontend in Twig und SCSS.

- [x] OpenDXP mit Docker, Start mit einem Befehl
- [x] Datenmodell: Kurse, Standorte, Anmeldungen als Data Objects
- [x] Demo-Daten per Konsolenbefehl, damit die Seite nach dem Start nicht leer ist
- [x] Layout mit Twig-Vererbung, SCSS-Grundgerüst (BEM, Mobile First)
- [x] Kursübersicht mit Filter nach Standort und Kategorie, Kursdetailseite
- [x] Deutsch und Französisch, mit Sprachumschalter und hreflang
- [ ] Startseite aus Areabricks: Hero, Teaser, nächste Kurse, Text mit Bild, FAQ
- [ ] Anmeldeformular mit Validierung und barrierefreien Fehlermeldungen
- [x] Tests und statische Analyse
- [ ] CI mit GitHub Actions

## Warum OpenDXP

OpenDXP ist der Open-Source-Fork von Pimcore 11 und steht weiter unter der GPLv3. Seit Version 2025.1 läuft
die Pimcore Community Edition unter der Pimcore Open Core License und braucht bei der Installation einen
registrierten Produkt-Key.

Für ein Demo-Projekt heißt das: Mit OpenDXP kann jeder das Projekt ohne Registrierung starten.
Die Konzepte sind dieselben wie in Pimcore (Documents, Data Objects, Assets, Editables, Areabricks, Twig).
Im Code heißen die Namespaces `OpenDxp\` statt `Pimcore\`.

## Schnellstart

Voraussetzungen: Docker mit Docker Compose (Docker Desktop, Colima oder OrbStack), `make` und ein freier Port 80.
PHP und Composer brauchst du nicht, alles läuft im Container.

```bash
git clone https://github.com/arbnorhajdini/campus-pimcore.git
cd campus-pimcore
make setup
```

`make setup` startet die Container, installiert die Pakete, richtet OpenDXP ein und legt die Demo-Inhalte an.
Das dauert beim ersten Mal einige Minuten.

| Was     | Adresse                  | Login             |
| ------- | ------------------------ | ----------------- |
| Website | <http://localhost>       |                   |
| Admin   | <http://localhost/admin> | `admin` / `admin` |

Der Login gilt nur für diese lokale Demo.

## Datenmodell

Die Klassen liegen als Definition in [`var/classes/`](var/classes) und werden bei der Installation angelegt.

| Klasse         | Felder                                                                                       |
| -------------- | -------------------------------------------------------------------------------------------- |
| `Location`     | Name, Ort, Adresse                                                                           |
| `Course`       | Titel, Kurzbeschreibung, Beschreibung (je DE/FR), Slug, Kategorie, Form, Dauer, Preis, Start, Bild, Standorte |
| `Registration` | Kurs, Standort, Vorname, Nachname, E-Mail, Telefon, Nachricht, Sprache                       |

Die Demo-Inhalte stehen in [`fixtures/demo.yaml`](fixtures/demo.yaml). `make demo` legt sie an oder aktualisiert sie.
Die Kursbilder sind generierte Farbverläufe, damit das Repo keine Fotos mit unklarer Lizenz enthält.

## Seiten

| Seite         | Deutsch                     | Französisch                 | Technik                                  |
| ------------- | --------------------------- | --------------------------- | ---------------------------------------- |
| Start         | `/de`                       | `/fr`                       | Dokument mit Editables                   |
| Kursübersicht | `/de/kurse`                 | `/fr/cours`                 | Dokument, Controller lädt die Kurse      |
| Kursdetail    | `/de/kurse/web-entwicklung` | `/fr/cours/web-entwicklung` | Symfony-Route mit einem Pfad pro Sprache |

`/` leitet auf die Sprache weiter, die der Browser bevorzugt. Die Seiten beider Sprachen sind in OpenDXP als
Übersetzungen verknüpft, der Sprachumschalter führt deshalb immer auf dieselbe Seite in der anderen Sprache.

## Frontend

```text
templates/
    layouts/base.html.twig      Grundgerüst: head, Skip-Link, Header, main, Footer
    partials/                   Header mit Navigation aus dem Dokumentbaum, Footer, Sprachumschalter
    components/                 Wiederverwendbare Teile wie die Kurskarte
    macros/format.html.twig     Datum und Preis im Format der Sprache
    pages/, course/             Die Seiten
assets/
    styles/                     SCSS: abstracts, base, layout, components, pages
    js/                         ES-Module ohne Bundler, über die Symfony AssetMapper
translations/                   Texte der Oberfläche in DE und FR (ICU, mit Plural)
```

- **Kein Node.js nötig.** Das SCSS kompiliert das Symfony Sass-Bundle im PHP-Container, JavaScript lädt als
  natives ES-Modul über eine Importmap.
- **SCSS:** BEM, eine Datei pro Komponente, Design-Tokens als CSS-Variablen, Breakpoints nur über ein Mixin.
- **Barrierefreiheit:** semantisches HTML, Skip-Link, sichtbarer Fokus, Labels an allen Formularfeldern,
  `aria-current` in Navigation und Sprachumschalter, ausreichende Kontraste.
- **Bilder:** OpenDXP erzeugt die Thumbnails als `<picture>` mit AVIF, WebP und JPEG, jeweils in 1x und 2x.
- **Ohne JavaScript benutzbar:** Der Kursfilter ist ein normales GET-Formular, jede Auswahl hat ihre eigene URL.

## Befehle

| Befehl       | Was er macht                                       |
| ------------ | -------------------------------------------------- |
| `make setup` | Erster Start: Container, Pakete, Installation      |
| `make up`    | Container starten                                  |
| `make demo`  | Demo-Inhalte anlegen oder aktualisieren            |
| `make watch` | SCSS bei jeder Änderung neu kompilieren            |
| `make down`  | Container stoppen                                  |
| `make cache` | Caches leeren                                      |
| `make cs`    | Code-Stil korrigieren (PHP-CS-Fixer)               |
| `make stan`  | Statische Analyse (PHPStan)                        |
| `make test`  | Tests ausführen (Pest)                             |
| `make help`  | Alle Befehle anzeigen                              |

## Stack

| Bereich   | Technik                                              |
| --------- | ---------------------------------------------------- |
| CMS       | OpenDXP 1.5 (Pimcore-Fork) auf Symfony 7.4           |
| Backend   | PHP 8.3, MariaDB 10.11, Redis                        |
| Frontend  | Twig, HTML, SCSS, Vanilla JavaScript (ES-Module), Symfony AssetMapper |
| Qualität  | Pest, PHPStan, PHP-CS-Fixer                          |
| Umgebung  | Docker Compose: PHP-FPM, Nginx, Supervisord          |

## Lizenz

GPL-3.0, wie das [OpenDXP-Skeleton](https://github.com/open-dxp/skeleton), auf dem das Projekt aufbaut.
Siehe [LICENSE.md](LICENSE.md).
