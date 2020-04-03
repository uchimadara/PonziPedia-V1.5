<?php require_once 'app/init.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>EasyLogin Pro</title>
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- CSRF Token -->
	<meta name="csrf-token" content="<?php echo csrf_token() ?>">

	<!-- Required for reCaptcha -->
	<meta name="referrer" content="never">
	
	<!-- CSS -->
	<link href="<?php echo asset_url('css/vendor/bootstrap-noconflict.min.css') ?>" rel="stylesheet">
	<link href="<?php echo asset_url('css/modal-only.css') ?>" rel="stylesheet">
	
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

	<?php if (Auth::guest()): ?>
		<!-- Show Login and Sigup buttons -->
		<p>
			<a href="#" data-toggle="modal" data-target="#loginModal">Log in</a> |
			<a href="#" data-toggle="modal" data-target="#signupModal">Sign up</a>
		</p>
	<?php else: ?>
		<!-- Show user name, avatar, etc -->
		<p>Howdy, <a href="profile.php?u=<?php echo Auth::user()->id ?>"><?php echo Auth::user()->display_name; ?></a></p>
		
		<p><img src="<?php echo Auth::user()->avatar ?>" width="50"></p>
		
		<p>
			<a href="#" data-toggle="modal" data-target="#settingsModal">Settings</a> |
			<a href="javascript:EasyLogin.openPMS()">Messages</a> <span class="pm-notification" style="padding:0"></span> |
			<?php if (Auth::userCan('dashboard')): ?><a href="admin.php">Admin</a> |<?php endif ?>
  			<a href="javascript:EasyLogin.logout()">Log out</a>
		</p>
	<?php endif ?>

	<a href="comments.php">Comments Page</a>
	
	<!-- Load modals -->
	<?php echo View::make('modals.load'); ?>
	
</body>
</html>