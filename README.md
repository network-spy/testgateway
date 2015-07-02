# testgateway
Test task: demonstrate using pgateway library with Laravel 4

1) Setup DB config in file /app/config/database.php
2) Run DB migration or import db_dump.sql
3) Set server root dir /public/
4) Run "composer update" in command line to get components including pgateway library in /vendor/ directory
5) Set api keys into payment library config file /vendor/network-spy/pgateway/src/PGateway/Config.php or set in controller using \PGateway\Conig
