<?php require_once 'app/init.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>EasyLogin Pro</title>
</head>
<body>

	<?php if (Auth::guest()): ?>
		<p>
			<a href="login.php"><?php _e('main.login') ?></a> | 
			<a href="signup.php"><?php _e('main.signup') ?></a>
		</p>
	<?php else: ?>
		<p>Howdy, <a href="profile.php?u=<?php echo Auth::user()->id ?>"><?php echo Auth::user()->display_name; ?></a></p>
		<p><img src="<?php echo Auth::user()->avatar ?>" width="50"></p>
		<p>
			<a href="settings.php">Settings</a> |
			<?php if (Auth::userCan('dashboard')): ?><a href="admin.php">Admin</a> |<?php endif ?>
			<a href="logout.php">Log out</a>
		</p>
	<?php endif ?>

	<a href="comments.php">Comments Page</a>
</body>
</html>