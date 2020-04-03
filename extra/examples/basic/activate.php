<?php
require_once 'app/init.php';

if (Auth::check() || (empty($_GET['reminder']) && !isset($_GET['activated']))) {
	redirect_to(App::url());
}

if (isset($_GET['reminder'])) {
	
	Register::activate($_GET['reminder']);
	
	if (Register::passes()) {
		redirect_to('activate.php?activated=1');
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Activate</title>
</head>
<body>
	<?php if (isset($_GET['activated'])): ?>
		<h3><?php _e('main.activate_success') ?></h3>
		<p><?php _e('main.activate_success_msg') ?></p>
		<p><a href="login.php"><?php _e('main.login') ?></a></p>
	<?php else: ?>
		<h3><?php _e('main.activate_account') ?></h3>
		<?php if (Register::fails()) {
			echo Register::errors()->first(null, '<p>:message</p>');
		} ?>
		<p><a href="activation.php"><?php _e('main.resend_activation') ?></a></p>
	<?php endif ?>
</body>
</html>