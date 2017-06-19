<?php
/**
 * This file is part of the fnayou/dotted package.
 *
 * Copyright (c) 2016. Aymen FNAYOU <fnayou.aymen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fnayou;

/**
 * Class FakeParameters.
 */
class FakeParameters
{
    /**
     * @return array
     */
    public static function getBaseArrayContent()
    {
        return [
            'key1' => 'value1',
            'key2' => 2,
            'key3' => true,
            'key4' => [
                'key5' => 'value5',
                'key6' => 6,
                'key7' => [],
            ],
            'key8' => [
                'value8',
                8,
            ],
        ];
    }

    /**
     * @return array
     */
    public static function getNewArrayContent()
    {
        return [
            'key9' => 'value9',
            'key10' => 10,
            'key11' => null,
            'key12' => [
                'value12',
                12,
            ],
            'key13' => [
                'key14' => 'value14',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function getFlattenContent()
    {
        return [
            'key1' => 'value1',
            'key2' => 2,
            'key3' => true,
            'key4.key5' => 'value5',
            'key4.key6' => 6,
            'key8.0' => 'value8',
            'key8.1' => 8,
        ];
    }
}
