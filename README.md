Working scraper for work-test according to these instructions:
* Gör en scraper och läs in bilannonser från https://bilweb.se/ (åtminstone 500 st) i en Mysql-databas (text & beskrivande data).
* Gör ett Ajax-baserat sökformulär där man kan söka på märke, modellår, regnummer och få fram en lista av dessa.
* Gör koden i PHP och undvik framework (Boostrap är ok att använda), skriv rena och effektiva SQL:er.
* Undvik AI

Annat:

* Skicka in källkoden och länk till fungerande sida. 
* Enbart PHP samt eventuella anrop till externa eller inkompilerade funktioner såsom curl är okej att använda.


Info about solution:

schema for setting up sql database is in sql/schema.sql

To do a scrape of 500 vehicles go to /car-info-scraper/car-info-scraper.php
To search scraped vehicles go to /car-info-scraper
