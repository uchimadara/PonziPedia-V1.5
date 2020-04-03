<?php
require_once '../app/init.php';

$provider = 'linkedin';

$settingsPage = App::url('settings.php?p=connect');

$scope = array('r_basicprofile', 'r_emailaddress');

if (! isset($_GET['error']) && ! isset($_GET['denied'])) {
	if (Auth::check() && isset($_GET['disconnect'])) {
		Usermeta::delete(Auth::user()->id, "{$provider}_id");
		Usermeta::delete(Auth::user()->id, "{$provider}_avatar");
		Usermeta::delete(Auth::user()->id, 'avatar_type', $provider);
		Usermeta::delete(Auth::user()->id, "{$provider}_profile");

		redirect_to($settingsPage);
	}

	Session::delete('oauth_user');

	$credentials = new OAuth\Common\Consumer\Credentials(
	    Config::get("services.{$provider}.id"),
	    Config::get("services.{$provider}.secret"),
	    App::url('linkedin')
	);

	$storage = new OAuth\Common\Storage\Session;
	
	$factory = new OAuth\ServiceFactory;

	// Use cURL
	// $factory->setHttpClient(new OAuth\Common\Http\Client\CurlClient);

	$service = $factory->createService($provider, $credentials, $storage, $scope);

	if (empty($_GET['code'])) {
		$authUrl = $service->getAuthorizationUri();
	} else {
		try {
			$state = isset($_GET['state']) ? $_GET['state'] : null;
			$service->requestAccessToken($_GET['code'], $state);
		} catch (Exception $e) {
			exit('Oauth Retrieve Access Token Error.');
		}
	}

	if (isset($authUrl)) {
		redirect_to($authUrl);
	}

	try {
		$user = with(new OAuth\UserData\ExtractorFactory)->get($service);

		$user = array(
			'id'         => $user->getUniqueId(),
			'email'      => $user->getEmail(),
			'username'   => str_replace('.', '', $user->getUsername()),
			'first_name' => $user->getFirstName(),
			'last_name'  => $user->getLastName(),
			'full_name'  => $user->getFullName(),
			'about'      => $user->getDescription(),
			'avatar'     => $user->getImageUrl(),
			'location'   => $user->getLocation(),
			'profile'    => $user->getProfileUrl(),
			'url'        => $user->getWebsite(),
			'birthday'   => $user->getField('birthday'),
			'locale'     => $user->getField('locale'),
			'gender'     => $user->getField('gender'),
			'provider'   => $provider,
		);

		Session::set('oauth_user', $user);

		$storage->clearAllTokens()->clearAllAuthorizationStates();

	} catch (Exception $e) {
		exit('Oauth Extract Data Error.');
	}

	redirect_to(App::url('oauth.php'));
} else {
	redirect_to(App::url());
}
