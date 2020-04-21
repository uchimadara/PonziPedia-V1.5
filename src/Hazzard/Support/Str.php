<?php namespace Hazzard\Support;

use RuntimeException;

class Str {

	/**
	 * Determine if a given string matches a given pattern.
	 *
	 * @param  string  $pattern
	 * @param  string  $value
	 * @return bool
	 */
	public static function is($pattern, $value)
	{
		if ($pattern == $value) return true;

		$pattern = preg_quote($pattern, '#');

		$pattern = str_replace('\*', '.*', $pattern).'\z';

		return (bool) preg_match('#^'.$pattern.'#', $value);
	}

	/**
	 * Determine if a given string contains a given substring.
	 *
	 * @param  string  $haystack
	 * @param  string|array  $needles
	 * @return bool
	 */
	public static function contains($haystack, $needles)
	{
		foreach ((array) $needles as $needle) {
			if ($needle != '' && strpos($haystack, $needle) !== false) return true;
		}

		return false;
	}

	/**
	 * Convert a value to studly caps case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function studly($value)
	{
		$value = ucwords(str_replace(array('-', '_'), ' ', $value));
		return str_replace(' ', '', $value);
	}

	/**
	 * Convert a string to snake case.
	 *
	 * @param  string  $value
	 * @param  string  $delimiter
	 * @return string
	 */
	public static function snake($value, $delimiter = '_')
	{
		$replace = '$1'.$delimiter.'$2';

		return ctype_lower($value) ? $value : strtolower(preg_replace('/(.)([A-Z])/', $replace, $value));
	}

	/**
	 * Determine if a given string starts with a given substring.
	 *
	 * @param  string  $haystack
	 * @param  string|array  $needle
	 * @return bool
	 */
	public static function startsWith($haystack, $needles)
	{
		foreach ((array) $needles as $needle) {
			if ($needle != '' && strpos($haystack, $needle) === 0) return true;
		}

		return false;
	}

	/**
	 * Generate a more truly "random" alpha-numeric string.
	 *
	 * @param  int  $length
	 * @return string
	 */
	public static function random($length = 16)
	{
	    $string = '';

	    while (($len = strlen($string)) < $length) {
	        $size = $length - $len;

	        $bytes = random_bytes($size);

	        $string .= substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $size);
	    }

	    return $string;
	}

	/**
	 * Generate a "random" alpha-numeric string.
	 *
	 * Should not be considered sufficient for cryptography, etc.
	 *
	 * @deprecated since version 1.3. Use the "random" method directly.
	 *
	 * @param  int  $length
	 * @return string
	 */
	public static function quickRandom($length = 16)
	{
	    if (PHP_MAJOR_VERSION > 5) {
	        return static::random($length);
	    }

	    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	    return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
	}
}
