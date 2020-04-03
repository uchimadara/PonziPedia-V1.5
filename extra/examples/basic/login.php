<?php
require_once 'app/init.php';

if (Auth::check()) redirect_to(App::url());

if (isset($_POST['submit']) && csrf_filter()) {
	Auth::login($_POST['email'], $_POST['password'], isset($_POST['remember']));

	if (Auth::passes()) {
		$url = Config::get('auth.login_redirect');
		
		$url = empty($url) ? App::url() : $url;
		
		redirect_to($url);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
</head>
<body>
	<h3><?php _e('main.login') ?></h3>
		
	<?php if (Auth::fails()) {
		echo '<ul>';
		foreach (Auth::errors()->all('<li>:message</li>') as $error) {
		   echo $error;
		}
		echo '</ul>';
	} ?>

	<form action="" method="POST">
		<?php csrf_input() ?>
		
		<p>
	        <label for="email"><?php _e('main.email_username') ?></label>
	        <input type="text" name="email" id="email" value="<?php echo set_value('email') ?>">
	    </p>

	    <p>
	        <label for="password"><?php _e('main.password') ?></label>
	        <input type="password" name="password" id="password">
	    </p>

	    <p>
	        <label><input type="checkbox" name="remember" value="1" <?php echo set_checkbox('remember', '1') ?>> <?php _e('main.remember') ?></label>
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