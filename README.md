Gesior2012
==========

Gesior 2012 - Account Maker (website) for OTSes

Version for TFS 1.4 engine ( https://github.com/otland/forgottenserver/tree/1.4 ).

Tested on PHP 7.0-7.4 and 8.0 with MariaDB.

Account maker SQL queries are not compatible with default config of MySQL/MariaDB.
You got to edit MySQL/MariaDB config and add there line:
```
sql_mode=''
```
and restart MySQL/MariaDB.

PHP extensions required:
```
bcmath
curl
dom
gd
json
mbstring
mysql
pdo
xml
```
Debian/Ubuntu:
```
sudo apt install php-bcmath php-curl php-dom php-gd php-mbstring php-mysql php-pdo php-xml
sudo apt install php-json
```
`php-json` is build-in in PHP 8.0, so you can't install it as extension, it's ok.
