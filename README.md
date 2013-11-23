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