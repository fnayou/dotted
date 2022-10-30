<?php
/**
 * This file is part of the fnayou/dotted package.
 *
 * Copyright (c) 2016. Aymen FNAYOU <fnayou.aymen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Fnayou;

/**
 * Class Dotted.
 */
class Dotted
{
    public const SEPARATOR = '.';

    private array $values = [];

    final public function __construct(array $values = [])
    {
        $this->setValues($values);
    }

    public static function create(array $values = []): self
    {
        return new static($values);
    }

    /**
     * @return \Fnayou\Dotted
     */
    public function setValues(array $values): self
    {
        $this->values = $values;

        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param string $default
     *
     * @return mixed
     */
    public function get(string $path, string $default = null)
    {
        if (true === empty($path)) {
            throw new \InvalidArgumentException('"path" parameter cannot be empty.');
        }

        $keys = $this->explode($path);
        $values = $this->values;

        foreach ($keys as $key) {
            if (true === isset($values[$key])) {
                $values = $values[$key];
            } else {
                return $default;
            }
        }

        return $values;
    }

    /**
     * @return \Fnayou\Dotted
     */
    public function add(string $path, array $values): self
    {
        $pathValues = (array) $this->get($path);

        $this->set($path, $this->arrayMergeRecursiveDistinct($pathValues, $values));

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @throws \InvalidArgumentException
     *
     * @return \Fnayou\Dotted
     */
    public function set(string $path, $value): self
    {
        if (true === empty($path)) {
            throw new \InvalidArgumentException('"path" parameter cannot be empty.');
        }

        $values = &$this->values;
        $keys = $this->explode($path);
        while (0 < \count($keys)) {
            if (1 === \count($keys)) {
                if (true === \is_array($values)) {
                    $values[array_shift($keys)] = $value;
                } else {
                    throw new \InvalidArgumentException(sprintf('cannot set value at "path" (%s) : not array.', $path));
                }
            } else {
                $key = array_shift($keys);
                if (!isset($values[$key])) {
                    $values[$key] = [];
                }
                $values = &$values[$key];
            }
        }

        return $this;
    }

    public function has(string $path): bool
    {
        $keys = $this->explode($path);
        $values = $this->values;

        foreach ($keys as $key) {
            if (true === isset($values[$key])) {
                $values = $values[$key];
                continue;
            }

            return false;
        }

        return true;
    }

    public function flatten(): array
    {
        return $this->arrayFlattenRecursive($this->values, null);
    }

    public function explode(string $path): array
    {
        return explode(static::SEPARATOR, $path);
    }

    /**
     * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
     * keys to arrays rather than overwriting the value in the first array with the duplicate
     * value in the second array, as array_merge does. I.e., with array_merge_recursive,
     * this happens (documented behavior):.
     *
     * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('org value', 'new value'));
     *
     * arrayMergeRecursiveDistinct does not change the datatypes of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge, i.e.:
     *
     * arrayMergeRecursiveDistinct(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('new value'));
     *
     * Parameters are passed by reference, though only for performance reasons. They're not
     * altered by this function.
     *
     * If key is integer, it will be merged like array_merge do:
     * arrayMergeRecursiveDistinct(array(0 => 'org value'), array(0 => 'new value'));
     *     => array(0 => 'org value', 1 => 'new value');
     *
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     * @author Anton Medvedev <anton (at) elfet (dot) ru>
     * @author Aymen Fnayou <fnayou (dot) aymen (at) gmail (dot) com>
     */
    public function arrayMergeRecursiveDistinct(array &$array1, array &$array2): array
    {
        $result = $array1;

        foreach ($array2 as $key => &$value) {
            if (true === \is_array($value) && true === isset($result[$key]) && true === \is_array($result[$key])) {
                if (true === \is_int($key)) {
                    $result[] = $this->arrayMergeRecursiveDistinct($result[$key], $value);

                    continue;
                }

                $result[$key] = $this->arrayMergeRecursiveDistinct($result[$key], $value);

                continue;
            }

            if (true === \is_int($key)) {
                $result[] = $value;

                continue;
            }

            $result[$key] = $value;
        }

        return $result;
    }

    public function arrayFlattenRecursive(array $array, string $parent = null): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (true === \is_array($value)) {
                $result = array_merge($result, $this->arrayFlattenRecursive($value, $key));

                continue;
            }

            if (null === $parent) {
                $result[$key] = $value;

                continue;
            }

            $result[$parent.'.'.$key] = $value;
        }

        return $result;
    }
}
