<?php et_get_mobile_header('mobile'); ?>
<div data-role="content" class="post-content">
	<h1 class="post-title job-title">
		<?php 
			$general_opt=	new ET_GeneralOptions();
			printf(__('Login to %s',ET_DOMAIN) , $general_opt->get_site_title () );
			
		?>
	</h1>
	<form action="" method="post">
		<div class="content-field inset-shadow">
			<h3><?php _e('Username',ET_DOMAIN); ?></h3>
			<div class="input-text">
				<input type="text" name="username" autocomplete="off" id="login_username">
			</div>
			<h3><?php _e('Password',ET_DOMAIN); ?></h3>
			<div class="input-text">
				<input type="password" name="Password" autocomplete="off" id="login_pass">
			</div>
		</div>
		<div class="content-field f-padding">
			<div class="input-button">
				<input type="button" class="et_login" value="<?php _e('Continue',ET_DOMAIN); ?>">
			</div>
			<div class="clearfix"></div>
		</div>
	</form>
</div><!-- /content -->

<?php et_get_mobile_footer('mobile'); ?>