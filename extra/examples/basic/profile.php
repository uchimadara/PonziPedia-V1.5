<?php
require_once 'app/init.php';

if (empty($_GET['u'])) redirect_to(App::url());

$user = User::where('id', $_GET['u'])->orWhere('username', $_GET['u'])->first();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo @$user->display_name; ?></title>
</head>
<body>

	<?php if (is_null($user)): ?>
		<h3><?php _e('errors.404') ?></h3>
		<?php _e('errors.page') ?>
	<?php else: ?>
		<h3>
			<?php echo $user->display_name; echo empty($user->username)?'':" <small>({$user->username})</small>"; ?>

			<?php if (!empty($user->verified)): ?>
				<em><?php _e('main.verified') ?></em>
			<?php endif ?>
		</h3>
		
		<img src="<?php echo $user->avatar ?>" width="150">

		<p><b>E-mail:</b> <?php echo $user->email ?></p>

		<?php if (!empty($user->phone)): ?>
			<p><b>Phone:</b> <?php echo $user->phone ?></p>
		<?php endif ?>
		
		<!-- 
		<?php if ($user->gender == 'M' || $user->gender == 'F'): ?>
			<p><b>Gender:</b> <?php echo trans("main.gender_{$user->gender}") ?></p>
		<?php endif ?>
		<?php if (!empty($user->birthday)): ?>
			<p><b>Birthday:</b> <?php echo $user->birthday ?></p>
		<?php endif ?>
		 -->

		<?php if (!empty($user->url)): ?>
			<p><b>Website:</b> <a href="<?php echo $user->url ?>"><?php echo str_replace(array('http://', 'https://'), '', $user->url) ?></a></p>
		<?php endif ?>

		<?php if (!empty($user->location)): ?>
			<p><b>Location:</b> <?php echo $user->location ?></a></p>
		<?php endif ?>

		<?php if (!empty($user->joined)): ?>
			<p><b>Joined:</b> <?php echo with(new DateTime($user->joined))->format('F Y') ?></a></p>
		<?php endif ?>

		<p>
			<?php foreach (Config::get('auth.providers') as $key => $provider) {
				if (!empty($user->usermeta["{$key}_profile"])) {
					echo '<a href="'.$user->usermeta["{$key}_profile"].'" target="_blank" title="'.$provider.'"><img src="'.asset_url("img/social-icons/{$key}.png").'" width="24"></a>';
				}
			} ?>
		</p>
		
		<b>About:</b>
		<?php if (!empty($user->about)): ?>
			<p><?php echo $user->about ?></p>
		<?php endif ?>

	<?php endif ?>
</body>
</html>