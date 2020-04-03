<?php
require_once '../../app/init.php';

if (Auth::check() || (empty($_GET['reminder']) && !Session::has('password_updated'))) {
	redirect_to(App::url());
}

if (isset($_POST['submit']) && csrf_filter()) {
	
	Password::reset($_POST['pass1'], $_POST['pass2'], $_POST['reminder']);
				
	if (Password::passes()) {
		redirect_to('reset.php', array('password_updated' => true));
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Reset Password</title>
</head>
<body>
		
	<?php if (Session::has('password_updated')): Session::deleteFlash(); ?>
		<h3><?php _e('main.reset_success') ?></h3>
		<p><?php _e('main.reset_success_msg') ?></p>
		<p><a href="login.php"><?php _e('main.login') ?></a></p>
	<?php else: ?>
		<h3><?php echo _e('main.recover_pass') ?></h3>
		
		<?php if (Password::fails()) {
			echo Password::errors()->first(null, '<p>:message</p>');
		} ?>
		
		<form action="" method="POST">
			<?php csrf_input() ?>
			
			<p>
                <label for="reset-pass1"><?php _e('main.newpassword') ?></label>
                <input type="password" name="pass1" id="reset-pass1">
            </p>
            
            <p>
                <label for="reset-pass2"><?php _e('main.newpassword_confirmation') ?></label>
                <input type="password" name="pass2" id="reset-pass2">
            </p>
            
            <p>
				<button type="submit" name="submit"><?php _e('main.change_pass') ?></button>
			</p>
			
			<p>
				<a href="reminder.php"><?php _e('main.new_reminder') ?></a>
			</p>

			<input type="hidden" name="reminder" value="<?php echo escape($_GET['reminder']) ?>">
		</form>
	<?php endif ?>
</body>
</html>