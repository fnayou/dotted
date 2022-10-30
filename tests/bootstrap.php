<?php

ini_set('display_errors', '1');

error_reporting(\E_ALL);

$autoloadPath = __DIR__.'/../vendor/autoload.php';

if (!file_exists($autoloadPath)) {
    exit('File autoload.php not found. Run \'composer install\' command.');
}

require $autoloadPath;
