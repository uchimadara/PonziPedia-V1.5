<?php
require_once 'app/init.php';

if (Auth::check()) redirect_to(App::url());

if (isset($_POST['submit']) && csrf_filter()) {
	
	Register::reminder($_POST['email'], @$_POST['g-recaptcha-response']);
				
	if (Register::passes()) {
		redirect_to('activation.php', array('activation_sent' => true));
	}
}
?>			
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<!-- Required for reCaptcha -->
	<meta name="referrer" content="never">
	<title>Activation</title>
</head>
<body>
	<?php if (Session::has('activation_sent')): Session::deleteFlash(); ?>
		<h3><?php _e('main.check_email') ?></h3>
		<?php _e('main.activation_check_email') ?>
	<?php else: ?>
		<h3><?php echo _e('main.send_activation') ?></h3>
		
		<?php if (Register::fails()) {
			echo '<ul>';
			foreach (Register::errors()->all('<li>:message</li>') as $error) {
			   echo $error;
			}
			echo '</ul>';
		} ?>
		
		<form action="" method="POST">				
			<?php csrf_input() ?>
			
			<p>
		        <label for="activation-email"><?php _e('main.enter_email') ?></label>
		        <input type="text" name="email" id="activation-email" value="<?php echo set_value('email') ?>">
		    </p>
			
			<?php if (Config::get('auth.captcha')): ?>
				<p>
					<?php display_captcha(); ?>
				</p>
			<?php endif ?>

		    <p>
		    	<button type="submit" name="submit"><?php _e('main.continue') ?></button>
		    </p>
		</form>
	<?php endif ?>
</body>
</html>