<?php require_once 'app/init.php'; ?>

<?php echo View::make('header')->render() ?>

<div class="jumbotron" style="text-align: center; background: none;">
	<h1><?php _e('main.hello') ?></h1>

 	<div style="margin-top: 50px;">
 		<?php if (Auth::guest()): ?>
			<a href="login.php" class="btn btn-primary"><?php _e('main.login') ?></a> &nbsp;
			<a href="signup.php" class="btn btn-primary"><?php _e('main.signup') ?></a>
		<?php endif ?>
 	</div>

 	<div style="min-height: 100px"></div>
</div>

<?php echo View::make('footer')->render() ?>