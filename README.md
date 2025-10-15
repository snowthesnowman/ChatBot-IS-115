Disclaimer: Sjekk at du har oppdatert php til tidligst versjon 8.2, noen av dependencies funker ikke uten.
Pass også på at du har 7zip lastet ned, composer install fungerer ikke uten.

1. Last ned https://getcomposer.org/download/ (Jeg brukte installer bare)
2. 

composer global require "laravel/installer=~1.1"

composer require botman/botman

composer require botman/driver-web

3. Gjør php --ini i terminalen også gå innpå den filen og ta vekk ; fra

fileinfo
sqlite
sqlite3

DERETTER LAGRER DU!

4. php -m (Sjekk om de er her, De burde være der har du gjort det riktig php.ini fil)



5. (Bare copy paste, i vscode terminalen)

php -v

php -m | Select-String -Pattern "fileinfo|sqlite|pdo_sqlite"

composer install

Test-Path vendor/autoload.php 



6. Lager database fil (paste i vscode terminal)

if (!(Test-Path database\database.sqlite)) { New-Item -ItemType File database\database.sqlite | Out-Null }
php artisan migrate



7. 
php artisan config:clear

php artisan cache:clear
