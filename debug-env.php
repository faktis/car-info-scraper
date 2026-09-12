<?php

header('Content-Type: text/plain');

echo 'MYSQLHOST set: '
    . (getenv('MYSQLHOST') ? 'yes' : 'no')
    . PHP_EOL;

echo 'MYSQLPORT set: '
    . (getenv('MYSQLPORT') ? 'yes' : 'no')
    . PHP_EOL;

echo 'MYSQLUSER set: '
    . (getenv('MYSQLUSER') ? 'yes' : 'no')
    . PHP_EOL;

echo 'MYSQLPASSWORD set: '
    . (getenv('MYSQLPASSWORD') ? 'yes' : 'no')
    . PHP_EOL;

echo 'MYSQLDATABASE set: '
    . (getenv('MYSQLDATABASE') ? 'yes' : 'no')
    . PHP_EOL;