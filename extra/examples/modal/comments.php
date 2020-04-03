<?php require_once 'app/init.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Comments</title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- CSRF Token -->
	<meta name="csrf-token" content="<?php echo csrf_token() ?>">
	
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

	<!-- Embeded comments -->
	<div id="embed_comments" style="max-width:800px"></div>
	<script src="<?php echo asset_url('js/embed-comments.js') ?>"></script>
	<script>
		var page 	  = '1'; 		// Page identifier
		var pageTitle = 'My Page';  // A name for the page

		embedComments('#embed_comments', page, pageTitle);
	</script>

	<!-- Load modals -->
	<?php echo View::make('modals.load'); ?>

</body>
</html>