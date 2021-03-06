<?php

/**
 * bbPress User Profile Edit Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<form class="col-lg-10 col-md-10 col-sm-9" id="bbp-your-profile" action="<?php bbp_user_profile_edit_url( bbp_get_displayed_user_id() ); ?>" method="post" enctype="multipart/form-data">

	<h2 class="entry-title gi-name"><?php _e( 'Name', 'govintranet' ) ?></h2>

	<?php do_action( 'bbp_user_edit_before' ); ?>

	<fieldset class="bbp-form gi-name">
		<legend><?php _e( 'Name', 'govintranet' ) ?></legend>

		<?php do_action( 'bbp_user_edit_before_name' ); ?>

		<div>
			<label for="first_name"><?php _e( 'First name', 'govintranet' ) ?></label>
			<input type="text" name="first_name" id="first_name" value="<?php bbp_displayed_user_field( 'first_name', 'edit' ); ?>" class="regular-text" tabindex="<?php bbp_tab_index(); ?>" />
		</div>

		<div>
			<label for="last_name"><?php _e( 'Last name', 'govintranet' ) ?></label>
			<input type="text" name="last_name" id="last_name" value="<?php bbp_displayed_user_field( 'last_name', 'edit' ); ?>" class="regular-text" tabindex="<?php bbp_tab_index(); ?>" />
		</div>

		<div>
			<label for="nickname"><?php _e( 'Nickname', 'govintranet' ); ?></label>
			<input type="text" name="nickname" id="nickname" value="<?php bbp_displayed_user_field( 'nickname', 'edit' ); ?>" class="regular-text" tabindex="<?php bbp_tab_index(); ?>" />
		</div>

		<div>
			<label for="display_name"><?php _e( 'Display name', 'govintranet' ) ?></label>

			<?php bbp_edit_user_display_name(); ?>

		</div>

		<?php do_action( 'bbp_user_edit_after_name' ); ?>

	</fieldset>

	<h2 class="entry-title gi-about"><?php  _e( 'About', 'govintranet' ) ; ?></h2>

	<fieldset class="bbp-form gi-about">
		<legend><?php _e( 'About', 'govintranet' ) ; ?></legend>

		<?php do_action( 'bbp_user_edit_before_about' ); ?>

		<div>
			<label for="description"><?php _e( 'About me', 'govintranet' ); ?></label>
			<textarea name="description" id="description" rows="5" cols="30" tabindex="<?php bbp_tab_index(); ?>"><?php bbp_displayed_user_field( 'description', 'edit' ); ?></textarea>
		</div>

		<?php do_action( 'bbp_user_edit_after_about' ); ?>

	</fieldset>



	<h2 class="entry-title gi-account"><?php _e( 'Account', 'govintranet' ) ?></h2>

	<fieldset class="bbp-form gi-account">
		<legend><?php _e( 'Account', 'govintranet' ) ?></legend>

		<?php do_action( 'bbp_user_edit_before_account' ); ?>

		<div>
			<label for="user_login"><?php _e( 'Username', 'govintranet' ); ?></label>
			<input type="text" name="user_login" id="user_login" value="<?php bbp_displayed_user_field( 'user_login', 'edit' ); ?>" disabled="disabled" class="regular-text" tabindex="<?php bbp_tab_index(); ?>" />
		</div>

		<div class="gi-email">
			<label for="email"><?php _e( 'Email', 'govintranet' ); ?></label>

			<input type="text" name="email" id="email" value="<?php bbp_displayed_user_field( 'user_email', 'edit' ); ?>" class="regular-text" tabindex="<?php bbp_tab_index(); ?>" />

			<?php

			// Handle address change requests
			$new_email = get_option( bbp_get_displayed_user_id() . '_new_email' );
			if ( !empty( $new_email ) && $new_email !== bbp_get_displayed_user_field( 'user_email', 'edit' ) ) : ?>

				<span class="updated inline">

					<?php printf( __( 'There is a pending email address change to <code>%1$s</code>. <a href="%2$s">Cancel</a>', 'govintranet' ), $new_email['newemail'], esc_url( self_admin_url( 'user.php?dismiss=' . bbp_get_current_user_id()  . '_new_email' ) ) ); ?>

				</span>

			<?php endif; ?>

		</div>
	<?php if ( !function_exists("GoogleAppsLogin")): ?>

		<div id="password">
			<label for="pass1"><?php _e( 'New password', 'govintranet' ); ?></label>
			<fieldset class="bbp-form password">
				<input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" tabindex="<?php bbp_tab_index(); ?>" />
				<span class="description"><?php _e( 'If you would like to change the password type a new one. Otherwise leave this blank.', 'govintranet' ); ?></span>

				<input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" tabindex="<?php bbp_tab_index(); ?>" />
				<span class="description"><?php _e( 'Type your new password again.', 'govintranet' ); ?></span><br />

				<div id="pass-strength-result"></div>
				<span class="description indicator-hint"><?php _e( 'Your password should be at least ten characters long. Phrases with numbers and symbols are easier to remember, e.g. "Her Imperial Highness has 30 handbags!"', 'govintranet' ); ?></span>
			</fieldset>
		</div>
	<?php endif; ?>
		
		<?php do_action( 'bbp_user_edit_after_account' ); ?>

	</fieldset>



	<?php if ( current_user_can( 'edit_users' ) && ! bbp_is_user_home_edit() ) : ?>

		<h2 class="entry-title gi-role"><?php _e( 'Role', 'govintranet' ) ?></h2>

		<fieldset class="bbp-form gi-role">
			<legend><?php _e( 'Role', 'govintranet' ); ?></legend>

			<?php do_action( 'bbp_user_edit_before_role' ); ?>

			<?php if ( is_multisite() && is_super_admin() && current_user_can( 'manage_network_options' ) ) : ?>

				<div>
					<label for="super_admin"><?php _e( 'Network role', 'govintranet' ); ?></label>
					<label>
						<input class="checkbox" type="checkbox" id="super_admin" name="super_admin"<?php checked( is_super_admin( bbp_get_displayed_user_id() ) ); ?> tabindex="<?php bbp_tab_index(); ?>" />
						<?php _e( 'Grant this user super admin privileges for the Network.', 'govintranet' ); ?>
					</label>
				</div>

			<?php endif; ?>

			<?php bbp_get_template_part( 'form', 'user-roles' ); ?>

			<?php do_action( 'bbp_user_edit_after_role' ); ?>

		</fieldset>

	<?php endif; ?>

	<?php if ( get_option("options_module_staff_directory")): ?>
	<h2 class="entry-title"><?php _e('Staff directory','govintranet'); ?></h2>
	<?php endif; ?>

	<?php 
	do_action( 'bbp_user_edit_after' ); 
	?>

	<fieldset class="submit">
		<legend><?php _e( 'Save changes', 'govintranet' ); ?></legend>
		<div>

			<?php bbp_edit_user_form_fields(); ?>

			<label for="bbp_user_edit_submit" class="sr-only"><?php bbp_is_user_home_edit() ? _e( 'Update profile', 'govintranet' ) : _e( 'Update user', 'govintranet' ); ?></label>
			<input type="submit" id="bbp_user_edit_submit" name="bbp_user_edit_submit" class="button submit user-submit" value="<?php bbp_is_user_home_edit() ? _e( 'Update profile', 'govintranet' ) : _e( 'Update user', 'govintranet' ); ?>" />
		</div>
	</fieldset>

</form>