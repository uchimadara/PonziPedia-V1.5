<?php
require_once 'app/init.php';

if (Auth::check() || (empty($_GET['reminder']) && !Session::has('password_updated'))) {
	redirect_to(App::url());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Reset Password</title>
	
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
		
	<?php if (Session::has('password_updated')): Session::deleteFlash(); ?>
		<h3><?php _e('main.reset_success') ?></h3>
		<p><?php _e('main.reset_success_msg') ?></p>
		<p><a href="login.php"><?php _e('main.login') ?></a></p>
	<?php else: ?>
		<h3><?php echo _e('main.recover_pass') ?></h3>
		<form action="reset" class="ajax-form">
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