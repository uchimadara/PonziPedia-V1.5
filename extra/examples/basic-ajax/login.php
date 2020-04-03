<?php
require_once 'app/init.php';

if (Auth::check()) redirect_to(App::url());
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	
	<!-- CSRF Token -->
	<meta name="csrf-token" content="<?php echo csrf_token() ?>">
	
	<!-- JavaScript -->
	<script src="<?php echo asset_url('js/vendor/jquery-1.11.1.min.js') ?>"></script>
	<script src="<?php echo asset_url('js/vendor/bootstrap.min.js') ?>"></script>
	<script src="<?php echo asset_url('js/easylogin.js') ?>"></script>
	<script src="<?php echo asset_url('js/main.js') ?>"></script>
	<script>
		EasyLogin.options = {
			ajaxUrl: '<?php echo App::url("ajax.php") ?>',
			lang: <?php echo json_encode(trans('main.js')) ?>,
			debug: <?php echo Config::get('app.debug')?1:0; ?>,
		};
	</script>

</head>
<body>
	<h3><?php _e('main.login') ?></h3>
		
	<form action="login" class="ajax-form">
		<p>
	        <label for="email"><?php _e('main.email_username') ?></label>
	        <input type="text" name="email" id="email">
	    </p>

	    <p>
	        <label for="password"><?php _e('main.password') ?></label>
	        <input type="password" name="password" id="password">
	    </p>

	    <p>
	        <label><input type="checkbox" name="remember" value="1"> <?php _e('main.remember') ?></label>
	    </p>

	    <p>
			<button type="submit" name="submit"><?php _e('main.login') ?></button>
		</p>

		<p>
			<a href="reminder.php"><?php _e('main.forgot_pass') ?></a> <br>
			<a href="activation.php"><?php _e('main.resend_activation') ?></a>
		</p>
	</form>

	<?php if (count(Config::get('auth.providers'))): ?>
	    <p><?php _e('main.login_with2') ?></p>
	    <p>
	    	<?php foreach (Config::get('auth.providers', array()) as $key => $provider): ?>
	    		<a href="<?php echo App::url("oauth.php?provider={$key}") ?>"><?php echo $provider ?></a>
	    	<?php endforeach ?>
	    </p>
	<?php endif ?>
</body>
</html>