<?php

/**
 * Display captcha.
 *
 * @param  bool $jsLinkOnly
 * @param  bool $tagOnly
 * @return void
 */
function display_captcha($jsLinkOnly = false, $tagOnly = false)
{
	$lang = app('translator')->getLocale();
	$config = app('config')->get('services.recaptcha');
	
	$captcha = new \Hazzard\Support\Recaptcha($config['private_key'], $config['public_key'], $lang);

	if ($jsLinkOnly) {
		echo '<script src="'.$captcha->getJsLink().'" async defer></script>'."\n";
	} else {
		echo $captcha->display(array(), $tagOnly);
	}
}

/**
 * Display captcha tag only.
 *
 * @return void
 */
function display_captcha_tag()
{
	return display_captcha(false, true);
}

/**
 * Output JSON formated message.
 *
 * @param  string  $message
 * @param  bool    $success
 * @return void
 */
function json_message($message = null, $success = true)
{
	header('Content-Type: application/json');

	echo json_encode(compact('message', 'success'));

	exit;
}

/**
 * Redirect to given URL.
 *
 * @param  string  $url
 * @return void
 */
function redirect_to($url, array $flash = array())
{
	foreach ($flash as $key => $value) {
		app('session')->flash($key, $value);
	}

	if (headers_sent()) {
		echo '<html><body onload="redirect_to(\''.$url.'\');"></body>'.
			'<script type="text/javascript">function redirect_to(url) {window.location.href = url}</script>'.
			'</body></html>';
	} else {
		header('Location:' . $url);
	}

	exit;
}

/**
 * Get the current url.
 *
 * @return string
 */
function get_current_url()
{
	$https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    return ($https ? 'https://' : 'http://') . (!empty($_SERVER['REMOTE_USER']) ? 
		$_SERVER['REMOTE_USER'].'@' : '') . (isset($_SERVER['HTTP_HOST']) ? 
		$_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'] . 
		($https && $_SERVER['SERVER_PORT'] === 443 || $_SERVER['SERVER_PORT'] === 80 ? '' : 
		':'.$_SERVER['SERVER_PORT']))).$_SERVER['REQUEST_URI'];
}

/**
 * Check if is an ajax request.
 *
 * @param  string  $url
 * @return void
 */
function is_ajax_request()
{
	return (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

/**
 * Get the url to the asset file
 *
 * @param   string  $path
 * @return  string
 */
function asset_url($path = '')
{
	return app()->url("assets/{$path}");
}

/**
 * Get Gravatar URL for a specified email address.
 *
 * @param  string  $email
 * @param  string  $size
 * @param  string  $default  Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param  string  $rating   Maximum rating (inclusive) [ g | pg | r | x ]
 * @return string
 */
function get_gravatar($email, $size = 80, $default = 'mm', $rating = 'g')
{   
    $url = 'http://www.gravatar.com/avatar/';
    
    $url .= md5(strtolower(trim($email)));
    
    $url .= "?s=$size&d=$default&r=$rating";

    return $url;
}

/**
 * Get the root Facade application instance.
 *
 * @param  string  $make
 * @return mixed
 */
function app($make = null)
{
	if (!is_null($make)) {
		return app()->make($make);
	}

	return Hazzard\Support\Facades\Facade::getFacadeApplication();
}

/**
 * Get the path to the application folder.
 *
 * @param   string  $path
 * @return  string
 */
function app_path($path = '')
{
	return app('path').($path ? '/'.$path : $path);
}

/**
 * Get the path to the storage folder.
 *
 * @param   string $path
 * @return  string
 */
function storage_path($path = '')
{
	return app('path.storage').($path ? '/'.$path : $path);
}

/**
 * Get the value from the POST array.
 *
 * @param	string	$field
 * @param	string	$default
 * @param	bool	$escape
 * @return	string
 */
function set_value($field, $default = '', $escape = true)
{
	if (isset($_POST[$field])) {
		return $escape ? escape($_POST[$field]) : $_POST[$field];
	}

	return $default;
}

/**
 * Get te selected value of <select> from the POST array.
 *
 * @param	string  $field
 * @param	string  $value
 * @param	bool    $default
 * @return	string
 */
function set_select($field, $value = '', $default = false)
{
	if (isset($_POST[$field]) && $_POST[$field] == (string) $value) {
		return ' selected="selected"';
	}

	return $default ? ' selected="selected"' : '';
}

/**
 * Get the selected value of a checkbox input from the POST array.
 *
 * @param	string  $field
 * @param	string  $value
 * @param	bool    $default
 * @return	string
 */
function set_checkbox($field = '', $value, $default = false)
{
	if (isset($_POST[$field]) && $_POST[$field] == (string) $value) {
		return ' checked="checked"';
	}

	return $default ? ' checked="checked"' : '';
}

/**
 * Get the selected value of a radio input from the POST array.
 *
 * @param	string  $field
 * @param	string  $value
 * @param	bool    $default
 * @return	string
 */
function set_radio($field, $value = '', $default = false)
{
	if (isset($_POST[$field]) && $_POST[$field] == (string) $value) {
		return ' checked="checked"';
	}

	return $default ? ' checked="checked"' : '';
}

/**
 * Escape HTML entities in a string.
 *
 * @param  string  $value
 * @return string
 */
function escape($value)
{
	return trim(htmlentities($value, ENT_QUOTES, 'UTF-8'));
}

/**
 * Echo the CSRF input.
 *
 * @return mixed
 */
function csrf_input()
{
	echo '<input type="hidden" name="_token" value="'.csrf_token().'">';
}

/**
 * Get the CSRF token value.
 *
 * @return string
 */
function csrf_token()
{
	return app('session')->token();
}

/**
 * Check if input token match session token.
 *
 * @return string
 */
function csrf_filter() 
{
	if (!app('config')->get('app.csrf')) return true;
	
	$check = isset($_POST['_token']) && $_POST['_token'] == csrf_token();
	
	app('session')->regenerateToken();

	return $check;
}

/**
 * Translate and echo the given message.
 *
 * @param  string  $id
 * @param  array   $parameters
 * @param  string  $locale
 * @return string
 */
function _e($id, $parameters = array(), $locale = null)
{
	echo app('translator')->trans($id, $parameters, $locale);
}

/**
 * Translate the given message.
 *
 * @param  string  $id
 * @param  array   $parameters
 * @param  string  $locale
 * @return string
 */
function trans($id, $parameters = array(), $locale = null)
{
	return app('translator')->trans($id, $parameters, $locale);
}

/**
 * Sanitizes a string key.
 *
 * @param string $key String key
 * @return string Sanitized key
 */
function sanitize_key($key)
{
	$key = strtolower($key);
	$key = preg_replace('/[^a-z0-9_\-]/', '', $key);

	return $key;
}

/**
 * Decode value only if it was encoded to JSON.
 *
 * @param  string  $original
 * @param  bool    $assoc
 * @return mixed
 */
function maybe_decode($original, $assoc = true)
{
	if (is_numeric($original)) return $original;
	
	$data = json_decode($original, $assoc);
	
	return (is_object($data) || is_array($data)) ? $data : $original;
}

/**
 * Encode data to JSON, if needed.
 *
 * @param  mixed  $data
 * @return mixed
 */
// function maybe_encode($data)
// {
// 	if (is_array($data) || is_object($data)) {
// 		return json_encode($data);
// 	}
	
// 	return $data;
// }

/**
 * Check value to find if it was serialized.
 *
 * @param  string  $data
 * @param  bool    $strict
 * @return bool
 */
function is_serialized( $data, $strict = true ) 
{
	if (!is_string($data)) return false;
	
	$data = trim($data);
 	
 	if ('N;' == $data) return true;
	if (strlen($data) < 4) return false;
	if (':' !== $data[1]) return false;
	
	if ($strict) {
		$lastc = substr($data, -1);
		
		if (';' !== $lastc && '}' !== $lastc) {
			return false;
		}
	} else {
		$semicolon = strpos($data, ';');
		$brace     = strpos($data, '}');
		
		if (false === $semicolon && false === $brace) return false;
		if (false !== $semicolon && $semicolon < 3) return false;
		if (false !== $brace && $brace < 4) return false;
	}
	
	$token = $data[0];
	
	switch ($token) {
		case 's' :
			if ($strict) {
				if ('"' !== substr($data, -2, 1)) {
					return false;
				}
			} elseif (false === strpos($data, '"')) {
				return false;
			}
		case 'a' :
		case 'O' :
			return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
		case 'b' :
		case 'i' :
		case 'd' :
			$end = $strict ? '$' : '';
			return (bool) preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
	}

	return false;
}

/**
 * Unserialize value only if it was serialized.
 *
 * @param  string $original
 * @return mixed
 */
function maybe_unserialize($original) 
{
	if (is_serialized($original)) {
		return @unserialize( $original );
	}

	return $original;
}

/**
 * Serialize data, if needed.
 *
 * @param  mixed  $data
 * @return mixed
 */
function maybe_serialize($data) 
{
	if (is_array($data) || is_object($data)) {
		return serialize($data);
	}

	return $data;
}

/**
 * Return the first element in an array passing a given truth test.
 *
 * @param  array    $array
 * @param  Closure  $callback
 * @param  mixed    $default
 * @return mixed
 */
function array_first($array, $callback, $default = null)
{
	foreach ($array as $key => $value) {
		if (call_user_func($callback, $key, $value)) return $value;
	}

	return value($default);
}

/**
 * Get an item from an array using "dot" notation.
 *
 * @param  array   $array
 * @param  string  $key
 * @param  mixed   $default
 * @return mixed
 */
function array_get($array, $key, $default = null)
{
	if (is_null($key)) return $array;

	if (isset($array[$key])) return $array[$key];

	foreach (explode('.', $key) as $segment) {
		if (!is_array($array) || ! array_key_exists($segment, $array)) {
			return $default;
		}

		$array = $array[$segment];
	}

	return $array;
}

/**
 * Set an array item to a given value using "dot" notation.
 *
 * If no key is given to the method, the entire array will be replaced.
 *
 * @param  array   $array
 * @param  string  $key
 * @param  mixed   $value
 * @return array
 */
function array_set(&$array, $key, $value)
{
	if (is_null($key)) return $array = $value;

	$keys = explode('.', $key);

	while (count($keys) > 1) {
		$key = array_shift($keys);

		if ( ! isset($array[$key]) || ! is_array($array[$key])) {
			$array[$key] = array();
		}

		$array =& $array[$key];
	}

	$array[array_shift($keys)] = $value;

	return $array;
}

/**
 * Remove an array item from a given array using "dot" notation.
 *
 * @param  array   $array
 * @param  string  $key
 * @return void
 */
function array_forget(&$array, $key)
{
	$keys = explode('.', $key);

	while (count($keys) > 1) {
		$key = array_shift($keys);

		if ( ! isset($array[$key]) || ! is_array($array[$key])) {
			return;
		}

		$array =& $array[$key];
	}

	unset($array[array_shift($keys)]);
}

/**
 * Get the first element of an array.
 *
 * @param  array  $array
 * @return mixed
 */
function head($array)
{
	return reset($array);
}

/**
 * Determine if a given string matches a given pattern.
 *
 * @param  string  $pattern
 * @param  string  $value
 * @return bool
 */
function str_is($pattern, $value)
{
	return Hazzard\Support\Str::is($pattern, $value);
}

/**
 * Determine if a given string contains a given substring.
 *
 * @param  string        $haystack
 * @param  string|array  $needles
 * @return bool
 */
function str_contains($haystack, $needles)
{
	return Hazzard\Support\Str::contains($haystack, $needles);
}

/**
 * Convert a value to studly caps case.
 *
 * @param  string  $value
 * @return string
 */
function studly_case($value)
{
	return Hazzard\Support\Str::studly($value);
}

/**
 * Convert a string to snake case.
 *
 * @param  string  $value
 * @param  string  $delimiter
 * @return string
 */
function snake_case($value, $delimiter = '_')
{
	return Hazzard\Support\Str::snake($value, $delimiter);
}

/**
 * Determine if a given string starts with a given substring.
 *
 * @param  string 		 $haystack
 * @param  string|array  $needle
 * @return bool
 */
function starts_with($haystack, $needles)
{
	return Hazzard\Support\Str::contains($haystack, $needles);
}

/**
 * Generate a "random" alpha-numeric string.
 *
 * @param  int     $length
 * @return string
 */
function str_random($length = 16)
{
	return Hazzard\Support\Str::random($length);
}

/**
 * Return the given object.
 *
 * @param  mixed  $object
 * @return mixed
 */
function with($object)
{
	return $object;
}

/**
 * Return ajax comments view.
 * 
 * @param  string  $page
 * @param  string  $pageTitle
 * @param  string  $pageUrl
 * @return string
 */
function ajax_comments($page = null, $pageTitle = null, $pageUrl = null) 
{
	$view = app('view')->make('comments');
	
	if ($page) {
		$view->with('page', $page);
	}

	if ($pageTitle) {
		$view->with('page_title', $pageTitle);
	}

	if ($pageUrl) {
		$view->with('page_title', $pageUrl);
	}

	return $view->render();
}

/**
 * Convert a value to non-negative integer.
 * 
 * @param  mixed $value
 * @return int
 */
function absint($value) 
{
	return abs(intval($value));
}
