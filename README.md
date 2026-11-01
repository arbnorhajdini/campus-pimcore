# Campus Zentralschweiz

Eine kleine Demo-Kursplattform für eine fiktive Weiterbildungsschule mit mehreren Standorten.
Gebaut mit OpenDXP (Pimcore), Symfony, Twig, SCSS und Vanilla JavaScript.

> **Status:** im Aufbau. Die Checkliste unten zeigt, was schon fertig ist.

## Worum es geht

Das Projekt zeigt, wie ich ein Pimcore-Projekt aufbaue: von den Data Objects über Symfony-Controller
bis zu sauberem, barrierefreiem Frontend in Twig und SCSS.

- [x] OpenDXP mit Docker, Start mit einem Befehl
- [ ] Datenmodell: Kurse, Standorte, Anmeldungen als Data Objects
- [ ] Demo-Daten per Konsolenbefehl, damit die Seite nach dem Start nicht leer ist
- [ ] Layout mit Twig-Vererbung, SCSS-Grundgerüst (BEM, Mobile First)
- [ ] Startseite aus Areabricks: Hero, Teaser, nächste Kurse, Text mit Bild, FAQ
- [ ] Kursübersicht mit Filter nach Standort und Kategorie, Kursdetailseite
- [ ] Anmeldeformular mit Validierung und barrierefreien Fehlermeldungen
- [ ] Deutsch und Französisch
- [ ] Tests, statische Analyse und CI

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

`make setup` startet die Container, installiert die Pakete und richtet OpenDXP ein. Das dauert beim ersten Mal
einige Minuten.

| Was     | Adresse                  | Login             |
| ------- | ------------------------ | ----------------- |
| Website | <http://localhost>       |                   |
| Admin   | <http://localhost/admin> | `admin` / `admin` |

Der Login gilt nur für diese lokale Demo.

## Befehle

| Befehl       | Was er macht                                       |
| ------------ | -------------------------------------------------- |
| `make setup` | Erster Start: Container, Pakete, Installation      |
| `make up`    | Container starten                                  |
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
| Frontend  | Twig, HTML, SCSS, Vanilla JavaScript (ES-Module)     |
| Qualität  | Pest, PHPStan, PHP-CS-Fixer                          |
| Umgebung  | Docker Compose: PHP-FPM, Nginx, Supervisord          |

## Lizenz

GPL-3.0, wie das [OpenDXP-Skeleton](https://github.com/open-dxp/skeleton), auf dem das Projekt aufbaut.
Siehe [LICENSE.md](LICENSE.md).
