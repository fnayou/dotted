<?php

ini_set('display_errors', true);

error_reporting(\E_ALL);

$autoloadPath = __DIR__.'/../vendor/autoload.php';

if (!file_exists($autoloadPath)) {
    exit('File autoload.php not found. Run \'composer install\' command.');
}

require $autoloadPath;

$content = [
    'keyOne' => 'valueOne',
    'keyTwo' => [
        'keyThree' => 3,
        'keyFour' => false,
        'keyFive' => [
            true,
            'valueFive',
            5,
        ],
    ],
];

dump($content);

$dotted = \Fnayou\Dotted::create($content);

// check if values exist
dump('$dotted->has(\'keyOne\') will output "true" : '.$dotted->has('keyOne'));
dump('$dotted->has(\'keyTwo.keySix\') will output "false" : '.$dotted->has('keyTwo.keySix'));

// access values
dump('$dotted->get(\'keyOne\') will output "valueOne" : '.$dotted->get('keyOne'));
dump('$dotted->get(\'keyTwo.keyThree\') will output "3" : '.$dotted->get('keyTwo.keyThree'));
dump('$dotted->get(\'keyTwo.keyFive.0\') will output "true" : '.$dotted->get('keyTwo.keyFive.0'));

// access non-existent value
dump('$dotted->get(\'keyTwo.keySix\') will output "null" : '.$dotted->get('keyTwo.keySix'));

// access value with default value
dump('$dotted->get(\'keyTwo.keySix\', \'defaultValue\') will output "defaultValue" : '.$dotted->get('keyTwo.keySix'));

// insert value
$dotted->set('keyTwo.keySix', 'valueSix');
dump('$dotted->set(\'keyTwo.keySix\', \'valueSix\'); $dotted->get(\'keyTwo.keySix\') will output "valueSix" : '.$dotted->get('keyTwo.keySix'));

// insert value with override
$dotted->set('keyTwo.keySix', 6);
dump('$dotted->set(\'keyTwo.keySix\', 6); $dotted->get(\'keyTwo.keySix\') will output "6" : '.$dotted->get('keyTwo.keySix'));

// access flatten values
dump('$dotted->flatten() will output : ', $dotted->flatten());
