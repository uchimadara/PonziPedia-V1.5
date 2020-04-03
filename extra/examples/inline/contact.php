<?php require_once 'app/init.php'; ?>

<?php echo View::make('header')->render() ?>

<div class="row">
	<div class="col-md-6">
		<h3 class="page-header"><?php _e('main.webmaster_contact') ?></h3>
		<?php if (Auth::guest()): ?>
			<div class="alert alert-danger"><?php _e('main.logged_only') ?></div>
		<?php else: ?>
			<p class="help-block"><?php _e('main.webmaster_contact_help') ?></p>
			<br>
			<form action="webmasterContact" class="ajax-form">
				<div class="form-group">
	                <label for="message"><?php _e('main.message') ?></label>
	               <textarea class="form-control" name="message" id="message"></textarea>
	            </div>
		        <div class="form-group">
					<button type="submit" name="submit" class="btn btn-primary"><?php _e('main.send_message') ?></button>
				</div>
			</form>
		<?php endif ?>
	</div>
</div>

<?php echo View::make('footer')->render() ?>