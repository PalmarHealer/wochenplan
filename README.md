# Wochenplan

Hallo und cool das du es auch das Repository vom Wochenplan geschafft hast. Wenn du wissen willst an was gerade gearbeitet wird schau doch gerne [Hier](https://github.com/users/PalmarHealer/projects/2/views/1) vorbei.

# Übersicht
 - [Installation](#installation)
	 - [Vorbereitung](#vorbereitung)
	 - [MySQL Datenbank](#mysql-datenbank)
	 - [Config.php](#configphp---allgemein)
		 - [Allgemein](#configphp---allgemein)
			 - [permission_level](#permission_level)
			 - [webroot](#webroot)
			 - [theme](#theme)
			 - [create_lessons](#create_lessons)
			 - [permission_needed](#permission_needed)
			 - [create_lessons_for_others](#create_lessons_for_others)
			 - [manage_other_users](#manage_other_users)
		 - [MySQL](#configphp---mysql)
			 - [Verbindung](#verbindung)
			 - [keep_pdo](#keep_dpo)
			 - [room_names und times](#room_names-und-times)
			 - [header](#header)
   - [Credits](#credits)
   - [Lizenz/Rechte](#lizenzrechte)

# Installation

## Vorbereitung

Es gibt gerade nur eine variante den Wochenplan zu installieren.
Software die benötigt wird:

 1. **Apache/Nginx** (Webserver mit PHP **8.1.10 oder neuer**)
 2. **MySQL** datenbank (vielleicht geht es auch mit **MariaDB**)
 3. MySQL backend (z.B. **phpMyAdmin**) alternativ kann es auch in der Befehlszeile gemacht werden, aber dafür gibt es hier keine Anleitung.
 4. Wochenplan dateien.

Für die entsprechenden tools gibt es ebenfalls hier keine Anleitung, falls eine gebraucht wird einfach "**how to install...**" auf YouTube suchen.


## MySQL Datenbank

Es wird hierfür eine **MySQL** datenbank und **phpMyAdmin** vorrausgesetzt.

 1. phpMyAdmin aufrufen und auf die Homepage gehen
 
 ![grafik](https://user-images.githubusercontent.com/93807726/224839607-de9cdbc0-0acf-468b-9210-399cfab59f40.png)


 2. Auf den Importieren Reiter wechseln ohne eine Datenbank ausgewählt zu haben.
 
 ![grafik](https://user-images.githubusercontent.com/93807726/224839859-82924172-eb8a-4fcd-8ca3-57421faef02a.png)


 3. Auf der Seite die Datei [sql_setup.sql](setup/sql_setup.sql) auswählen.
 
 ![grafik](https://user-images.githubusercontent.com/93807726/224840744-df959be5-e00a-49ef-bcd2-e0c9bde4da17.png)


 4. Unten auf der Seite auf Importieren drücken
<br/><br/><br/>
## config.php - Allgemein

Jetzt gehen wir noch durch die [config.php](dependencies/config.php) und sind dann einsatzbereit. Die config ist eigentlich ziemlich simpel deswegen wird alles hier nur kurz beschrieben.
<br/><br/><br/>
#### $permission_level
ist Standartmäßig auf 0 und ist dafür das wenn neue benutzerkonten angelegt werden, ist das der Standart wert mit dem die Nutzer registriert werden.

**0** = Ein Administrator muss die Accounts manuell noch freischalten.

**1** = Der Account kann direkt benutzt werden.

**1<** = Wird nicht empfohlen, nur für testzwecke oder Sie wissen genau was Sie da machen.
<br/><br/><br/>
#### $webroot
Gibt an wo der Wochenplan installiert wird. Das ist dafür das alle Dateien wissen wo sich die anderen befinden. Wenn sich der Wochenplan in einem unterverzeichniss befindet, muss das hier angegeben werden. z.B. **nauren.de/test** dann muss der $webroot auf **/test** gesetzt werden.

Dann gibt es noch den **$relative_path** der kann so bleiben, es sei denn der Wochenplan wurde auf einer Subdomain oder Toplevel-Domain installiert. Dann muss das auf nichts gesetzt werden.
<br/><br/><br/>
#### $theme
Setzt das Standart theme, es ist eigentlich so das Browser die einen Darkmode erzwingen nicht betroffen sind.

**light** = für das Helle design.

**dark** = für das Dunkle design.
<br/><br/><br/>
#### $permission_needed
Hat änliche funktionen wie $permission_level nur das es nicht das Dashboard betrifft d.h. bei Standard Konfiguration können Benutzer nur auf die Dashboard Seite.

**0** = Es alles einfach so erreichbar. (Ausgenommen Seiten die hier ihre eigenes Berechtigungslevel haben)

**1** = Standard.

**1<** = Wird nicht empfohlen, nur für testzwecke oder Sie wissen genau was Sie da machen.
<br/><br/><br/>
#### $create_lessons
Das ist die Berechtigung Angebote zu erstellen.

**5** = Standart.

hierfür gibt es keine Empfehlung da das einfach vom Anwendungszweck abhängt. 
<br/><br/><br/>
#### $create_lessons_for_others
Das ist die Berechtigung Angebote für andere zu erstellen zu erstellen. **Muss** höher (oder gleich sein) als $create_lessons. 

**6** = Standart.

hierfür gibt es keine Empfehlung da das einfach vom Anwendungszweck abhängt. 
<br/><br/><br/>
#### $manage_other_users
Das ist einfach gesagt die Administrations Berechtigung.

**10** = Standart.

hierfür gibt es keine Empfehlung da das einfach vom Anwendungszweck abhängt. 

<br/>

## config.php - MySQL

Das sind die Konfigurationen für die Datenbank verbindung.
#### Verbindung
**Benutze hierfür keinen Admin Account, sondern einen der nur zugriff auf die Datenbank hat**

**Benutzer:** $db_user

**Passwort:** $db_password

**PDO:** 
Das ist die Zeile wo die Verbindung aufgebaut wird. 

Bei **localhost** wird der Server aufgerufen auf dem die Datenbank ist. (Wenn ein Spezieller Port verwendet wird kann der dort einfach angehangen werden z.B. **localhost:3306**)

Bei **wochenplan** wird die Datenbank ausgewählt. **WICHTIG** wenn hier etwas geändert wird, muss das auch in der Datenbank gemacht werden, die sql_setup datei setzt automatisch eine Datanbank mit dem Namen wochenplan auf, also kann das in den meisten fällen einfach so gelassen werden.

`$pdo = new PDO('mysql:host=**localhost**;dbname=**wochenplan**', $db_user, $db_password);`


#### $keep_dpo

Das ist um die pdo verbindung zu schließen falls es vergessen wird. **NUR** in testumgebungen auf **true** setzten

#### $weekday_names

Sind die Abkürzungen für die Wochentage und werden auf dem [Plan](plan/index.php) angezeigt.

#### $room_names und $times

Gibt an wie viele Räume es insgesamt gibt, beim erstellen wird aus dieser Liste geladen, nur auf dem [Plan](plan/index.php) muss das **manuell** hinzugefügt werden, das gleiche **gilt auch** für $times.

#### $header
Das dürfen Sie gerne selber herrausfinden. :)

## Credits

Theme und Layout (exept plan): [Tinydash](https://github.com/themewagon/tinydash/)

Loading animation on Plan: [Animaion](https://codepen.io/AbubakerSaeed/pen/JjXERWW)

Idee: [Aktive Schule Leipzig - Freie Oberschule](https://www.aktive-schule-leipzig.de/oberschule/)

Techniche umsetzung und realisierung: [Palmar Healer - Nauren](https://nauren.de)


## Lizenz/Rechte

Nauren® Copyright © 2022 - 2023.

Code released under the GNU License.
