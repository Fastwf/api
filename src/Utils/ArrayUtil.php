<?php

namespace Fastwf\Api\Utils;

use Fastwf\Api\Exceptions\KeyError;

/**
 * Utility class that help to perform some actions on arrays.
 */
class ArrayUtil {

    /**
     * Try to access to the key by extracting the value from the array.
     * 
     * When failed, a KeyError is thrown.
     *
     * @param array $array The array where find the value
     * @param mixed $key The key to find
     * @return mixed The value extracted
     * @throws KeyError when the key is missing
     */
    public static function &get(&$array, $key)
    {
        if (\array_key_exists($key, $array))
        {
            return $array[$key];
        }

        throw new KeyError("The key '$key' is missing");
    }

    /**
     * Access to the value associated to the key in the array or return the default value.
     *
     * @param array $array The array where find the value
     * @param mixed $key The key to find
     * @param mixed $default The default value to return when key is missing
     * @return mixed The value extracted
     */
    public static function &getSafe(&$array, $key, $default = null)
    {
        if (\array_key_exists($key, $array))
        {
            return $array[$key];
        }

        return $default;
    }

    /**
     * Merge the sub set of keys of $from array into $to array.
     * 
     * When $subSetKeys is null, all keys are injected in $to.
     *
     * @param array $from the array to use as source
     * @param array $to (out) the array to use as target
     * @param array|null $subSetKeys the array of keys to inject or null for all keys of $from
     * @return void
     */
    public static function merge(&$from, &$to, $subSetKeys = null)
    {
        if ($subSetKeys === null)
        {
            // Use from keys
            $subSetKeys = \array_keys($from);
        }
        else
        {
            // Filter by existing keys
            $subSetKeys = \array_filter($subSetKeys, function ($key) use ($from) { return \array_key_exists($key, $from); });
        }
        
        // Inject all values in $to array
        foreach ($subSetKeys as $key) {
            $to[$key] = $from[$key];
        }
    }

}
