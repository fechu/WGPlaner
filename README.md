#WGPlaner

Ein Tool zum organisieren deiner WG.

## Installation

Das einfachste ist es, ein [Package](https://github.com/fechu/WGPlaner/releases) von WGPlaner herunterzuladen. Diese beinhalten alles was du brauchst, um WGPlaner zu installieren und zu starten. 

### Installation mit einem Package

Entzippe das Package als erstes. Im Ordner findest du mehrere Dateien und Ordner. 

**Datenbank**

Erstelle eine MySQL Datenbank. Im entzippten Ordner findest du eine Datei mit dem Namen `database.sql`. Öffne sie mit einem Texteditor. Die darin enthaltenen SQL befehele führst du nun in deiner Datenbank aus. Sie erstellen die benötigten Tabellen und fügen einen Benutzer ein, mit dem du dich später einloggen kannst. 

**Konfiguration**

Suche nach der Datei `config/autoload/database.local.php.dist` und benenne sie um in `config/autoload/database.local.php` (entferne das `.dist`). Nun öffne diese Datei mit einem Editor deiner Wahl. Trage nun die Daten für die Verbindung zur Datenbank ein.

**Fertig**

Das wars. Sobald alles auf dem Server ist, kanns losgehen. Navigiere mit deinem Browser nach `(Pfad zum Hauptverzeichnis)/public`. Du wirst sofort zum Login weitergeleitet und kannst dich mit folgendem Benutzer einloggen:

- Benutzername: 	admin
- Password:		testtest

Benutzer erstellen und bearbeiten kannst du dann innerhalb der Applikation.

### Installation mit Git

TODO

## Voraussetzungen 

- PHP 5.4
- MySQL Datenbank

## Lizenz

Copyright (c) 2014 Sandro Meier
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

### Lizenz JpGraph

JpGraph wird unter der Q Public License vertrieben. Siehe http://opensource.org/licenses/qtpl.php für die gesamte Lizenz.