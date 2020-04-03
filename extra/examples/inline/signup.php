<?php
require_once 'app/init.php';

if (Auth::check()) redirect_to(App::url());
?>

<?php echo View::make('header')->render() ?>

<div class="row">
	<div class="col-md-6">
		<?php if (Session::has('signup_complete')): Session::deleteFlash(); ?>
			<h3 class="page-header"><?php _e('main.check_email') ?></h3>
			<?php _e('main.activation_check_email') ?>
		<?php else: ?>
			<h3 class="page-header"><?php _e('main.signup') ?></h3>

			<form action="signup"class="ajax-form clearfix">
				<?php if (Config::get('auth.require_username')): ?>
					<div class="form-group">
				        <label for="signup-username"><?php _e('main.username') ?></label>
				        <input type="text" name="username" id="signup-username" class="form-control">
				    </div>
				<?php endif ?>

			    <div class="form-group">
			        <label for="signup-email"><?php _e('main.email') ?></label>
			        <input type="text" name="email" id="signup-email" class="form-control">
			    </div>

			    <div class="form-group">
			        <label for="signup-pass1"><?php _e('main.password') ?></label>
			        <input type="password" name="pass1" id="signup-pass1" class="form-control" autocomplete="off" value="">
			    </div>

			    <!--
			    <div class="form-group">
			        <label for="signup-pass2"><?php _e('main.password_confirmation') ?></label>
			        <input type="password" name="pass2" id="signup-pass2" class="form-control" autocomplete="off">
			    </div>
			    -->

			    <?php echo UserFields::build('signup') ?>

				<?php if (Config::get('auth.captcha')): ?>
					<div class="form-group recaptcha">
						<?php display_captcha_tag(); ?>
					</div>
				<?php endif ?>

				<div class="form-group">
					<button type="submit" name="submit" class="btn btn-primary"><?php _e('main.signup') ?></button>
				</div>

				<?php if (count(Config::get('auth.providers'))): ?>
		            <span class="help-block"><?php _e('main.login_with2') ?></span>
		            <div class="social-connect clearfix">
		            	<?php foreach (Config::get('auth.providers') as $key => $provider): ?>
		            		<a href="oauth.php?provider=<?php echo $key ?>" class="connect <?php echo $key ?>" title="<?php _e("main.connect_with_{$key}") ?>"><?php echo $provider ?></a>
		            	<?php endforeach ?>
		            </div>
		        <?php endif ?>
			</form>
    	<?php endif ?>
	</div>
</div>

<?php echo View::make('footer')->render() ?>
