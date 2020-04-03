<?php
require_once 'app/init.php';

if (Auth::check()) redirect_to(App::url());
?>

<?php echo View::make('header')->render() ?>

<div class="row">
	<div class="col-md-6">
		<h3 class="page-header"><?php _e('main.login') ?></h3>
		
		<form action="login" class="clearfix ajax-form">
			<div class="form-group">
                <label for="email"><?php _e('main.email_username') ?></label>
                <input type="text" name="email" id="email" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="password"><?php _e('main.password') ?></label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            
            <div class="form-group">
                <div class="checkbox">
	                <label><input type="checkbox" name="remember" value="1"> <?php _e('main.remember') ?></label>
	            </div>
            </div>

	        <div class="form-group pull-left">
				<button type="submit" name="submit" class="btn btn-primary"><?php _e('main.login') ?></button>
			</div>

			<div class="form-group pull-right">
				<a href="reminder.php"><?php _e('main.forgot_pass') ?></a> <br>
				<a href="activation.php"><?php _e('main.resend_activation') ?></a>
			</div>

			<div class="clearfix"></div>
			
			<?php if (count(Config::get('auth.providers'))): ?>
				<span class="help-block"><?php _e('main.login_with2') ?></span>
				<div class="social-connect clearfix">
	            	<?php foreach (Config::get('auth.providers', array()) as $key => $provider): ?>
	            		<a href="oauth.php?provider=<?php echo $key ?>" class="connect <?php echo $key ?>" title="<?php _e("main.connect_with_{$key}") ?>"><?php echo $provider ?></a>
					<?php endforeach ?>
				</div>
			<?php endif ?>
		</form>
	</div>
</div>

<?php echo View::make('footer')->render() ?>