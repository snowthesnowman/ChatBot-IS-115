1. Last ned https://getcomposer.org/download/ (Jeg brukte installer bare)
2. composer global require "laravel/installer=~1.1"

3. Gjør php --ini i terminalen også gå innpå den filen og ta vekk ; fra

fileinfo
sqlite
sqlite3

derreter lagrer du

4. php -m (Sjekk om de er her, De burde være der har du gjort det riktig php.ini fil)

5. (Bare copy paste alt)
php -v
php -m | Select-String -Pattern "fileinfo|sqlite|pdo_sqlite"
composer install
Test-Path vendor/autoload.php 

6. Lager database fil
if (!(Test-Path database\database.sqlite)) { New-Item -ItemType File database\database.sqlite | Out-Null }
php artisan migrate


7. 
php artisan config:clear
php artisan cache:clear