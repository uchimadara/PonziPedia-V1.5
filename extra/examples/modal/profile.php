<?php
require_once 'app/init.php';

if (empty($_GET['u'])) redirect_to(App::url());

$user = User::where('id', $_GET['u'])->orWhere('username', $_GET['u'])->first();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo @$user->display_name; ?></title>
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<meta name="csrf-token" content="<?php echo csrf_token() ?>">
	
	<link href="<?php echo asset_url('css/vendor/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?php echo asset_url('css/bootstrap-custom.css') ?>" rel="stylesheet">
	<link href="<?php echo asset_url('css/main.css') ?>" rel="stylesheet">
	<style>
		.container {
			background: #fff;
			-webkit-box-shadow: 0 1px 3px rgba(0,0,0,.13);
			box-shadow: 0 1px 3px rgba(0,0,0,.13);
			max-width: 500px;
			padding: 20px;
		}
		.col-md-8 { padding-left: 10px; }
		.col-md-8 .glyphicon { opacity: .7; padding-right: 5px; }
	</style>
	
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
	<div class="container">

		<?php if (is_null($user)): ?>
			<h3 class="page-header"><?php _e('errors.404') ?></h3>
			<?php _e('errors.page') ?>
		<?php else: ?>
			<h3 class="page-header">
				<?php echo $user->display_name; echo empty($user->username)?'':" <small>({$user->username})</small>"; ?>

				<?php if (!empty($user->verified)): ?>
					<span class="verified-account" title="<?php _e('main.verified') ?>" data-toggle="tooltip">
						<span class="glyphicon glyphicon-ok"></span>
					</span>
				<?php endif ?>
			</h3>
			
			<div class="row">
				<div class="col-md-3">
					<img src="<?php echo $user->avatar ?>" class="img-thumbnail" style="margin-bottom: 10px;">
				</div>
				<div class="col-md-8">
					<p><span class="glyphicon glyphicon-envelope"></span> <?php echo $user->email ?></p>

					<?php if (!empty($user->phone)): ?>
						<p><span class="glyphicon glyphicon-phone-alt"></span> <?php echo $user->phone ?></p>
					<?php endif ?>
					
					<!-- 
					<?php if ($user->gender == 'M' || $user->gender == 'F'): ?>
						<p><b><?php _e('main.gender') ?>:</b> <?php echo trans("main.gender_{$user->gender}") ?></p>
					<?php endif ?>
					<?php if (!empty($user->birthday)): ?>
						<p><b><?php _e('main.birthday') ?>:</b> <?php echo $user->birthday ?></p>
					<?php endif ?>
					 -->

					<?php if (!empty($user->url)): ?>
						<p><span class="glyphicon glyphicon-link"></span> <a href="<?php echo $user->url ?>"><?php echo str_replace(array('http://', 'https://'), '', $user->url) ?></a></p>
					<?php endif ?>

					<?php if (!empty($user->location)): ?>
						<p><span class="glyphicon glyphicon-map-marker"></span> <?php echo $user->location ?></a></p>
					<?php endif ?>

					<?php if (!empty($user->joined)): ?>
						<p><span class="glyphicon glyphicon-time"></span> <?php echo with(new DateTime($user->joined))->format('F Y') ?></a></p>
					<?php endif ?>

					<p class="social-icons">
						<?php foreach (Config::get('auth.providers') as $key => $provider) {
							if (!empty($user->usermeta["{$key}_profile"])) {
								echo '<a href="'.$user->usermeta["{$key}_profile"].'" target="_blank" title="'.$provider.'"><span class="icon-'.$key.'"></span></a>';
							}
						} ?>
					</p>

					<?php if (Auth::check() && Auth::user()->id != $user->id): ?>
						<p>
							<?php $contact = Contact::find(Auth::user()->id, $user->id); ?>
							<?php if (!empty($contact) && !empty($contact->accepted)): ?>
								<a href="javascript:EasyLogin.removeContact(<?php echo $user->id ?>)" data-contact-id="<?php echo $user->id ?>" class="btn btn-xs btn-danger"><?php _e('main.remove_contact') ?></a>
							<?php elseif (!empty($contact)): ?>
								<a href="javascript:EasyLogin.removeContact(<?php echo $user->id ?>)" data-contact-id="<?php echo $user->id ?>" class="btn btn-xs btn-warning"><?php _e('main.cancel_contact') ?></a>
							<?php else: ?>
								<a href="javascript:EasyLogin.addContact(<?php echo $user->id ?>)" data-contact-id="<?php echo $user->id ?>" class="btn btn-xs btn-info"><?php _e('main.add_contact') ?></a>
							<?php endif ?>
						</p>
					<?php endif ?>
				</div>
			</div>
			
			<?php if (!empty($user->about)): ?>
				<p><?php echo $user->about ?></p>
			<?php endif ?>

		<?php endif ?>
	</div>
	
	<?php echo View::make('modals.load') ?>
	
	</body>
</html>