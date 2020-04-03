<?php require_once 'app/init.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Comments</title>

	<!-- jQuery -->
	<script src="<?php echo asset_url('js/vendor/jquery-1.11.1.min.js') ?>"></script>
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
</body>
</html>