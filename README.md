Dotted
======

<img src="http://data.aymen-fnayou.com/dotted_logo.png" width="120px" align="left"/>

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fnayou/dotted/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fnayou/dotted/?branch=master)
[![Build Status](https://travis-ci.org/fnayou/dotted.svg?branch=master)](https://travis-ci.org/fnayou/dotted)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d322f96e-af47-4d90-ac5b-439f71815ee3/mini.png)](https://insight.sensiolabs.com/projects/d322f96e-af47-4d90-ac5b-439f71815ee3)
[![Version](http://img.shields.io/packagist/v/fnayou/dotted.svg?style=flat)](https://packagist.org/packages/fnayou/dotted)
[![Build Status](https://scrutinizer-ci.com/g/fnayou/dotted/badges/build.png?b=master)](https://scrutinizer-ci.com/g/fnayou/dotted/build-status/master)

**Dotted** is a PHP library to manage *multidimensional arrays* !

It will help you *checking*, *accessing* or *inserting* values of an array.

## Installation

use [Composer][link-composer] to install `dotted` library :

```shell
$ php composer.phar require fnayou/dotted
```

or [download the latest release][link-release] and include `src/Dotted.php` in your project.

## Usage

first, you create `dotted` object by passing the `array` content.

next you can *check*, *access* or *insert* values with ease.

```php
<?php

    use Fnayou\Dotted;

    $content = [
        'keyOne' => 'valueOne',
        'keyTwo' => [
            'keyThree' => 3, 
            'keyFour' => false,
            'keyFive' => [
                true,
                'valueFive',
                5,
            ]
        ]
    ];

    $dotted = new Dotted($content);
    // or
    $dotted = Dotted::create($content);

    // check if values exist
    echo $dotted->has('keyOne');                        // output : true
    echo $dotted->has('keyTwo.keySix');                 // output : false

    // access values
    echo $dotted->get('keyOne');                        // output : valueOne
    echo $dotted->get('keyTwo.keyThree');               // output : 3
    echo $dotted->get('keyTwo.keyFive.0');              // output : true

    // access non-existent value
    echo $dotted->get('keyTwo.keySix');                 // output : null

    // access value with default value 
    echo $dotted->get('keyTwo.keySix', 'defaultValue'); // output : defaultValue

    // insert value
    $dotted->set('keyTwo.keySix', 'valueSix');
    echo $dotted->get('keyTwo.keySix');                 // output : valueSix

    // insert value with override
    $dotted->set('keyTwo.keySix', 6);                   // output : 6

    // access values (array content) 
    $dotted->getValues();
    /** output : 
      array:2 [▼
        "keyOne" => "valueOne"
        "keyTwo" => array:3 [▼
          "keyThree" => 3
          "keyFour" => false
          "keyFive" => array:3 [▼
            0 => true
            1 => "valueFive"
            2 => 5
          ]
        ]
      ]
    */

    // access flatten values
    $dotted->flatten();
    /** output :
      array:6 [▼
        "keyOne" => "valueOne"
        "keyTwo.keyThree" => 3
        "keyTwo.keyFour" => false
        "keyTwo.keyFive.0" => true
        "keyTwo.keyFive.1" => "valueFive"
        "keyTwo.keyFive.2" => 5
      ]
    */
```

## Credits

[Aymen FNAYOU][link-author]

## License

![license](https://img.shields.io/badge/license-MIT-lightgrey.svg) Please see [License File](LICENSE.md) for more information.

[link-author]: https://aymen-fnayou.com
[link-composer]: https://getcomposer.org/
[link-release]: https://github.com/fnayou/dotted/releases
