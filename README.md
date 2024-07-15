# Wochenplan

Welcome to the GitHub of the Wochenplan. Here you can find the code and guiding how to use/contribute and setup the Wochenplan. Right now the README.md is in a rework so feel free to join an help out.

## Version
The version system is structured as follows:
``state``.``version``.``feature``.``patch/fix``

## Preparation/Installation

There is currently only one way to install the weekly plan.
 Things required:

 1. **Apache/Nginx** (Webserver mit PHP **8.1.10 oder neuer**)
 2. **MySQL** database (maybe it also works with **MariaDB**)
 3. MySQL backend (e.g. **phpMyAdmin**) alternatively it can also be done in the command line, but there are no instructions here.
 4. Wochenplan files.
 There are no instructions for the corresponding tools here either. If you need one, simply search "**how to install...**" on YouTube.


## MySQL Database

A **MySQL** database and **phpMyAdmin** are required for this.

  1. Open phpMyAdmin and go to the homepage
 
 ![grafik](https://user-images.githubusercontent.com/93807726/224839607-de9cdbc0-0acf-468b-9210-399cfab59f40.png)


2. Switch to the Import tab without having selected a database.
 
 ![grafik](https://user-images.githubusercontent.com/93807726/224839859-82924172-eb8a-4fcd-8ca3-57421faef02a.png)


3. Select the file [sql_setup.sql](setup/sql_setup.sql) on the page.
 
 ![grafik](https://user-images.githubusercontent.com/93807726/224840744-df959be5-e00a-49ef-bcd2-e0c9bde4da17.png)


4. Press Import at the bottom of the page
<br/><br/><br/>

## Links

ToDo Board: [Link](https://github.com/users/PalmarHealer/projects/4/views/1)

## Credits

Theme und Layout (exept plan): [Tinydash](https://github.com/themewagon/tinydash/)

Color Picker: [Coloris](https://github.com/mdbassit/Coloris)

Mail libary: [PHPMailer](https://github.com/PHPMailer/PHPMailer)

Convert to PDF: [dompdf](https://github.com/dompdf/dompdf) (Not actively used right now)

Loading animation on Plan: [Animaion](https://codepen.io/AbubakerSaeed/pen/JjXERWW) (Not visible anymore but still exists in the code)

Idea: [Aktive Schule Leipzig e.V.](https://www.aktive-schule-leipzig.de/oberschule/)


## License/Rights

Nauren Copyright © 2022 - 2024.

Code released under the GNU License.
