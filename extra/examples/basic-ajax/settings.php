<?php
require_once 'app/init.php';

use Hazzard\Support\MessageBag;

if (!Auth::check()) redirect_to(App::url());

$page = isset($_GET['p']) ? $_GET['p'] : 'account';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Settings</title>
		
	<!-- CSRF Token -->
	<meta name="csrf-token" content="<?php echo csrf_token() ?>">
	
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

<div class="row">
	<div class="col-md-2">
		<ul class="nav nav-pills nav-stacked">
			<li <?php echo $page == 'account' ? 'class="active"':'' ?>><a href="?p=account"><?php echo _e('main.account') ?></a></li>
			<li <?php echo $page == 'profile' ? 'class="active"':'' ?>><a href="?p=profile"><?php echo _e('main.profile') ?></a></li>
			<li <?php echo $page == 'password' ? 'class="active"':'' ?>><a href="?p=password"><?php echo _e('main.password') ?></a></li>
			<li <?php echo $page == 'messages' ? 'class="active"':'' ?>><a href="?p=messages"><?php echo _e('main.messages') ?></a></li>
			<li <?php echo $page == 'connect' ? 'class="active"':'' ?>><a href="?p=connect"><?php echo _e('main.connect') ?></a></li>
		</ul>
	</div>
	<div class="col-md-6">
<?php
switch ($page) {

	// Account
	case 'account':
		$user = User::find(Auth::user()->id);
		?>
		<h3 class="page-header"><?php echo _e('main.account') ?></h3>

		<form action="settingsAccount" class="ajax-form">
			<?php if (Config::get('auth.require_username') && Config::get('auth.username_change')): ?>
				<div class="form-group">
			        <label for="username"><?php _e('main.username') ?> <em><?php _e('main.required') ?></em></label>
			        <input type="text" name="username" id="username" value="<?php echo $user->username ?>" class="form-control">
			    </div>
			<?php endif ?>

			<div class="form-group">
		        <label for="email"><?php _e('main.email') ?> <em><?php _e('main.required') ?></em></label>
		        <input type="text" name="email" id="email" value="<?php echo $user->email ?>" class="form-control">
		    </div>

		    <div class="form-group">
		        <label for="locale"><?php _e('main.language') ?></label>
		        <select name="locale" id="locale" class="form-control">
		        <?php $locales = Config::get('app.locales'); ?>
	        	<?php foreach ($locales as $key => $lang) : ?>
					<option value="<?php echo $key ?>" <?php echo $user->locale == $key ? 'selected' : '' ?>><?php echo $lang ?></option>
				<?php endforeach ?>
				</select>
		    </div>

            <div class="form-group">
		    	<button type="submit" name="submit" class="btn btn-primary"><?php _e('main.save_changes') ?></button>
		    	<?php if (Config::get('auth.delete_account')): ?>
					<div class="pull-right v-middle"><a href="?p=delete"><?php _e('main.delete_my_account') ?></a></div>
				<?php endif ?>
		    </div>
		</form>
		<?php
	break;

	// Password
	case 'password':
		$user = User::find(Auth::user()->id);
		?>
		<h3 class="page-header"><?php echo _e('main.password') ?></h3>

		<form action="settingsPassword" class="ajax-form">
			<div class="form-group">
		        <label for="pass1"><?php _e('main.current_password') ?></label>
		        <input type="password" name="pass1" id="pass1" class="form-control" autocomplete="off" value="">
		    </div>
			<div class="form-group">
		        <label for="pass2"><?php _e('main.newpassword') ?></label>
		        <input type="password" name="pass2" id="pass2" class="form-control" autocomplete="off" value="">
		    </div>
		    <div class="form-group">
		        <label for="pass3"><?php _e('main.newpassword_confirmation') ?></label>
		        <input type="password" name="pass3" id="pass3" class="form-control" autocomplete="off" value="">
			</div>
			<div class="form-group">
		    	<button type="submit" name="submit" class="btn btn-primary"><?php _e('main.save_changes') ?></button>
		    </div>
		</form>
		<?php
	break;


	// Profile
	case 'profile':
		$user = User::find(Auth::user()->id);
		?>
		<link href="<?php echo asset_url('css/vendor/imgpicker.css') ?>" rel="stylesheet">

		<h3 class="page-header"><?php echo _e('main.profile') ?></h3>

		<form action="settingsProfile" class="ajax-form">
			<div class="avatar-container form-group">
				<label><?php _e('main.avatar') ?></label>

				<div class="clearfix">
					<div class="pull-left">
						<a href="<?php echo $user->avatar ?>" target="_blank"><img src="<?php echo $user->avatar ?>" class="avatar-image img-thumbnail" width="150"></a>
					</div>
					<div class="pull-left" style="margin-left: 10px;">
						<?php $avatarType = @$user->usermeta['avatar_type']; ?>
						<select name="avatar_type" class="form-control">
							<option value="" <?php echo $avatarType == '' ? 'selected' : '' ?>><?php _e('main.default') ?></option>
							<option value="image" <?php echo $avatarType == 'image' ? 'selected' : '' ?>><?php _e('main.uploaded') ?></option>
							<option value="gravatar" <?php echo $avatarType == 'gravatar' ? 'selected' : '' ?>>Gravatar</option>

							<?php foreach (Config::get('auth.providers', array()) as $key => $provider) {
								if (!empty($user->usermeta["{$key}_id"])) {
									echo '<option value="'.$key.'" '.($avatarType == $key ? 'selected' : '').'>'.$provider.'</option>';
								}
							} ?>
						</select>
						<div class="btn btn-info btn-sm ip-upload"><?php _e('main.upload') ?> <input type="file" name="file" class="ip-file"></div>
						<button type="button" class="btn btn-info btn-sm ip-webcam"><?php _e('main.webcam') ?></button>
					</div>
				</div>

				<div class="alert ip-alert"></div>
				<div class="ip-info"><?php _e('main.crop_info') ?></div>
				<div class="ip-preview"></div>
				<div class="ip-rotate">
					<button type="button" class="btn btn-default ip-rotate-ccw" title="Rotate counter-clockwise"><span class="icon-ccw"></span></button>
					<button type="button" class="btn btn-default ip-rotate-cw" title="Rotate clockwise"><span class="icon-cw"></span></button>
				</div>
				<div class="ip-progress">
					<div class="text"><?php _e('main.uploading') ?></div>
					<div class="progress progress-striped active"><div class="progress-bar"></div></div>
				</div>
				<div class="ip-actions">
					<button type="button" class="btn btn-sm btn-success ip-save"><?php _e('main.save_image') ?></button>
					<button type="button" class="btn btn-sm btn-primary ip-capture"><?php _e('main.capture') ?></button>
					<button type="button" class="btn btn-sm btn-default ip-cancel"><?php _e('main.cancel') ?></button>
				</div>
			</div>

			<?php if (UserFields::has('first_name') && UserFields::has('last_name')): ?>
				<div class="form-group">
			        <label for="display_name"><?php _e('main.display_name') ?></label>
			        <select name="display_name" id="display_name" class="form-control">
			        	<?php if (Config::get('auth.require_username')): ?>
							<option <?php echo $user->display_name == $user->username ? 'selected' : '' ?>><?php echo $user->username ?></option>
						<?php endif ?>

			        	<?php if (!empty($user->first_name)): ?>
			        		<option <?php echo $user->display_name == $user->first_name ? 'selected' : '' ?>><?php echo $user->first_name ?></option>
			        	<?php endif ?>
			        	
			        	<?php if (!empty($user->last_name)): ?>
			        		<option <?php echo $user->display_name == $user->last_name ? 'selected' : '' ?>><?php echo $user->last_name ?></option>
			        	<?php endif ?>
			        	
			        	<?php if (!empty($user->first_name) && !empty($user->last_name)): ?>
			        		<option <?php echo $user->display_name == "$user->first_name $user->last_name" ? 'selected' : '' ?>><?php echo "$user->first_name $user->last_name" ?></option>
			        		<option <?php echo $user->display_name == "$user->last_name $user->first_name" ? 'selected' : '' ?>><?php echo "$user->last_name $user->first_name" ?></option>
			        	<?php endif ?>
			        </select>
			    </div>
			<?php endif ?>

		    <?php echo UserFields::setData($user->usermeta)->build('user') ?>

            <div class="form-group">
		    	<button type="submit" name="submit" class="btn btn-primary"><?php _e('main.save_changes') ?></button>
		    </div>
		</form>

		<script src="<?php echo asset_url('js/vendor/jquery.Jcrop.min.js') ?>"></script>
		<script src="<?php echo asset_url('js/vendor/jquery.imgpicker.js') ?>"></script>
		<script> 
			$(function() {
				$('.avatar-container').imgPicker({
					url: '<?php echo App::url("ajax.php?action=avatar") ?>',
					messages: <?php echo json_encode(trans('imgpicker.js')) ?>,
					aspectRatio: 1,
					cropSuccess: function(img) {
						$('.avatar-image').attr('src', img.url + '?'+new Date().getTime());
						this.container.find('select').val('image');
					}
				});

				EasyLogin.generateDisplayName();
			}); 
		</script>
		<?php
	break;

	// Messages
	case 'messages':
		?>
		<h3 class="page-header"><?php echo _e('main.pms') ?></h3>
		
		<h4><?php _e('main.settings') ?></h4>
		<form action="settingsMessages" class="ajax-form">
			<div class="checkbox">
				<label>
					<input type="checkbox" value="1" name="email_messages" <?php echo empty(Auth::user()->usermeta['email_messages'])?'':'checked'; ?>><?php echo _e('main.email_messages') ?>
				</label>
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" value="1" name="email_comments" <?php echo empty(Auth::user()->usermeta['email_comments'])?'':'checked'; ?>><?php echo _e('main.email_comments') ?>
				</label>
			</div>
			<div class="form-group">
		    	<button type="submit" name="submit" class="btn btn-primary"><?php _e('main.save_changes') ?></button>
		    </div>
		</form>
		<br>
		<h4><?php _e('main.contacts') ?></h4>
		<ul class="list-group contact-list">
			<?php foreach (Contact::all(Auth::user()->id) as $contact): ?>
				<li class="list-group-item <?php echo empty($contact['accepted'])?'':'contact-confirmed' ?>" data-contact-id="<?php echo $contact['id'] ?>">
					<a href="<?php echo App::url("profile.php?u={$contact['id']}") ?>" target="_blank">
						<img src="<?php echo $contact['avatar'] ?>" class="contact-avatar"><?php echo $contact['name'] ?></a>

					<span class="label label-danger"><?php _e('main.contact_request') ?></span>
					<div class="pull-right">
						<span class="confirmed"><a href="javascript:EasyLogin.confirmContact(<?php echo $contact['id'] ?>)"><?php _e('main.confirm_contact') ?></a> |</span>
						<a href="javascript:EasyLogin.removeContact(<?php echo $contact['id'] ?>)"><?php _e('main.remove') ?></a>
					</div>
				</li>
			<?php endforeach ?>
		</ul>
		<style>.contact-confirmed .confirmed, .contact-confirmed .label { display: none; }</style>
		<?php
	break;


	// Connect
	case 'connect':
		?>
		<div class="row">
			<div class="col-md-6 social-icons">
				<?php foreach (Config::get('auth.providers', array()) as $key => $provider) {
					?>
					<ul class="list-group">
						<li class="list-group-item clearfix">
							<span class="icon-<?php echo $key ?>"></span> <?php echo $provider ?>
							<?php if (empty(Auth::user()->usermeta["{$key}_id"])): ?>
								<a href="oauth.php?provider=<?php echo $key ?>" class="btn btn-info btn-sm pull-right"><?php _e('main.connect') ?></a>
							<?php else: ?>
								<a href="oauth.php?provider=<?php echo $key ?>&disconnect=1" class="btn btn-danger btn-sm pull-right"><?php _e('main.disconnect') ?></a>
							<?php endif ?>
						</li>
					</ul>
					<?php
				} ?>
			</div>
		</div>
		<p>
			<span class="label label-warning"><?php _e('main.warning') ?></span>
			<?php _e('main.connect_warning', array('password' => '<a href="?p=password">'.trans('main.password').'</a>')) ?>
		</p>
		<?php
	break;
	
	// Delete account
	case 'delete':
		if (!Config::get('auth.delete_account')) {
			redirect_to('?p=account');
		}

		if (isset($_POST['submit']) && csrf_filter()) {
			$id = Auth::user()->id;

			User::where('id', $id)->limit(1)->delete();
			
			Usermeta::delete($id);

			Message::newQuery()->where('to_user', $id)
					->orWhere('from_user', $id)
					->delete();

			Contact::deleteAll($id);

			Comments::deleteUserComments($id);

			Auth::logout();
								
			redirect_to(App::url());
		}
		?>
		<h3 class="page-header"><?php echo _e('main.delete_account') ?></h3>
		<?php _e('main.delete_account_message') ?>
		<form action="" method="POST">
			<?php csrf_input() ?>
			<div class="form-group">
		    	<button type="submit" name="submit" class="btn btn-danger"><?php _e('main.delete_my_account_confirm') ?></button>
		    </div>
		</form>
		<?php
	break;

	default:
		redirect_to('?p=account');
	break;
}
?>
</body>
</html>