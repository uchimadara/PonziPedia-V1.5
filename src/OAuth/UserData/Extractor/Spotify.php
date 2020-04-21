<?php

namespace OAuth\UserData\Extractor;

use OAuth\UserData\Utils\ArrayUtils;
use OAuth\UserData\Utils\StringUtils;

class Spotify extends LazyExtractor
{
	const REQUEST_PROFILE = '/me';

	const FIELD_BIRTHDAY = 'birthday';

	public function __construct()
	{
	    parent::__construct(
	        self::getLoadersMap(), 
	        self::getNormalizersMap(), 
	        self::getSupportedFields()
	    );
	}

	protected static function getLoadersMap()
	{
		return array_merge(self::getDefaultLoadersMap(), array(
            self::FIELD_BIRTHDAY => self::FIELD_BIRTHDAY,
		));
	}

	protected static function getNormalizersMap()
	{
		return array_merge(self::getDefaultNormalizersMap(), array(
    		self::FIELD_BIRTHDAY => self::FIELD_BIRTHDAY
    	));
	}

	public static function getSupportedFields()
	{
		return array_merge(
			array_diff(self::getAllFields(), array(self::FIELD_LOCATION)), 
			array(self::FIELD_BIRTHDAY)
		);
	}

	protected function profileLoader()  
	{
	    return json_decode($this->service->request(self::REQUEST_PROFILE), true);
	}

	protected function uniqueIdNormalizer($data)
	{
	    return isset($data['id']) ? $data['id'] : null;
	}

	protected function usernameNormalizer($data)
	{
	    return isset($data['uri']) ? str_replace('spotify:user:', '', $data['uri']) : null;
	}

	protected function fullNameNormalizer($data)
	{
	    return isset($data['display_name']) ? $data['display_name'] : null;
	}

	protected function firstNameNormalizer()
	{
	    $fullName = $this->getField(self::FIELD_FULL_NAME);
	    
	    if ($fullName) {
	        $name = explode(' ', $fullName);

	        return $name[0];
	    }

	    return null;
	}

	protected function lastNameNormalizer()
	{
	    $fullName = $this->getField(self::FIELD_FULL_NAME);
	    
	    if ($fullName) {
	        $name = explode(' ', $fullName);

	        return $name[sizeof($name) - 1];
	    }

	    return;
	}

	protected function descriptionNormalizer($data)
	{
	    return;
	}

	protected function websitesNormalizer($data)
	{
	    if (isset($data['external_urls'])) {
	        return array_values($data['external_urls']);
	    }

	    return array();
	}

	protected function websiteNormalizer($data)
	{
	    $websites = $this->getField(self::FIELD_WEBSITES);

	    return count($websites) > 0 ? $websites[0] : null;
	}

	protected function profileUrlNormalizer($data)
	{
	    if (isset($data['external_urls']['spotify'])) {
	        return $data['external_urls']['spotify'];
	    }
	}

	protected function imageUrlNormalizer($data)
	{
	    return isset($data['images'][0]['url']) ? $data['images'][0]['url'] : null;
	}

	protected function birthdayLoader()
	{
	    return $this->getExtras();
	}

	protected function birthdayNormalizer($data)
	{
	    return isset($data['birthday']) ? $data['birthday'] : null;
	}

	protected function emailNormalizer($data)
	{
	    return isset($data['email']) ? $data['email'] : null;
	}

	public function verifiedEmailNormalizer()
	{
	    return false; 
	}

	protected function extraNormalizer($data)
	{
	    return ArrayUtils::removeKeys($data, array(
	        'id',
	        'uri',
	        'email',
	        'full_name',
	        'display_name',
	        'external_urls',
	    ));
	}
}
