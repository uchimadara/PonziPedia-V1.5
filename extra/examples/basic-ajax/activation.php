<?php
require_once 'app/init.php';

if (Auth::check()) redirect_to(App::url());
?>
	
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Activation</title>
	
	<!-- CSRF Token -->
	<meta name="csrf-token" content="<?php echo csrf_token() ?>">
	
	<!-- Required for reCaptcha -->
	<meta name="referrer" content="never">
		
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
	<?php if (Session::has('activation_sent')): Session::deleteFlash(); ?>
		<h3><?php _e('main.check_email') ?></h3>
		<?php _e('main.activation_check_email') ?>
	<?php else: ?>
		<h3><?php echo _e('main.send_activation') ?></h3>
		<form action="activation" class="ajax-form">			
			<p>
		        <label for="email"><?php _e('main.enter_email') ?></label>
		        <input type="text" name="email" id="email">
		    </p>
			
			<?php if (Config::get('auth.captcha')): ?>
				<p>
					<?php display_captcha_tag(); ?>
				</p>
			<?php endif ?>

		    <p>
		    	<button type="submit" name="submit"><?php _e('main.continue') ?></button>
		    </p>
		</form>
	<?php endif ?>
</body>
</html>
