<?php namespace Hazzard\Support;

/**
 * anhskohbo <anhskohbo@gmail.com>
 * https://github.com/anhskohbo/no-captcha
 */

class Recaptcha {

	const CLIENT_API = 'https://www.google.com/recaptcha/api.js';
	const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

	/**
	 * @var string
	 */
	protected $secret;

	/**
	 * @var string
	 */
	protected $sitekey;

	/**
	 * @var string
	 */
	protected $lang;

	/**
	 * @param string $secret
	 * @param string $sitekey
	 */
	public function __construct($secret, $sitekey, $lang = null)
	{
		$this->lang = $lang;
		$this->secret = $secret;
		$this->sitekey = $sitekey;
	}

	/**
	 * Get the recaptcha html.
	 *
	 * @param  array $attributes
	 * @param  bool  $tagOnly
	 * @return string
	 */
	public function display($attributes = array(), $tagOnly = false)
	{
		$attributes['data-sitekey'] = $this->sitekey;

		$html = '';

		if (! $tagOnly) {
			$html .= '<script src="'.$this->getJsLink().'" async defer></script>'."\n";
		}
		
		$html .= '<div class="g-recaptcha"'.$this->buildAttributes($attributes).'></div>';

		return $html;
	}

	/**
	 * @param  string $response
	 * @param  string $clientIp
	 * @return bool
	 */
	public function verifyResponse($response, $clientIp = null)
	{
		if (empty($response)) return false;

		$response = $this->sendRequestVerify(array(
			'secret'   => $this->secret,
			'response' => $response,
			'remoteip' => $clientIp
		));

		return isset($response['success']) && $response['success'] === true;
	}

	/**
	 * @return string
	 */
	public function getJsLink()
	{
		if ($this->lang)
		{
			return static::CLIENT_API.'?hl='.$this->lang;
		}

		return static::CLIENT_API;
	}

	/**
	 * @param  array  $query
	 * @return array
	 */
	protected function sendRequestVerify(array $query = array())
	{
		$ch = curl_init();

		curl_setopt_array($ch, [
		    CURLOPT_URL => static::VERIFY_URL,
		    CURLOPT_POST => true,
		    CURLOPT_POSTFIELDS => $query,
		    CURLOPT_RETURNTRANSFER => true
		]);

		$response = curl_exec($ch);
		curl_close($ch);

		return json_decode($response, true);
	}

	/**
	 * @param  array  $attributes
	 * @return string
	 */
	protected function buildAttributes(array $attributes)
	{
		$html = array();

		foreach ($attributes as $key => $value)
		{
			$html[] = $key.'="'.$value.'"';
		}

		return count($html) ? ' '.implode(' ', $html) : '';
	}

}
