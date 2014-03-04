<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<!-- Use the .htaccess and remove these lines to avoid edge case issues.
				 More info: h5bp.com/i/378 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<!-- 	<meta name="viewport" content="width=device-width, initial-scale=1"  /> -->
	<meta name="description" content="<?php echo get_bloginfo( 'description') ?>" />
	<meta name="keywords" content="Job, Jobs, company, employer, employee" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<title><?php 
		/*
		 * Print the <title> tag based on what is being viewed.
		 */
		global $page, $paged, $current_user, $user_ID;

		wp_title( '|', true, 'right' );

		// Add the blog name.
		//bloginfo( 'name' );
		
		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		//if ( $site_description && ( is_home() || is_front_page() ) )
		//	echo " | $site_description";

		// Add a page number if necessary:
		//if ( $paged >= 2 || $page >= 2 )
		//	echo ' | ' . sprintf( __( 'Page %s', ET_DOMAIN ), max( $paged, $page ) );

		

	?></title>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php
		if ( is_singular() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );

		$general_opts	= new ET_GeneralOptions();
		$favicon	= $general_opts->get_favicon();
		if($favicon){
		?>
			<link rel="shortcut icon" href="<?php echo $favicon[0];?>"/>
	<?php } ?>
	<!-- enqueue json library for ie 7 or below -->
	<!--[if LTE IE 7]>
		<?php wp_enqueue_script('et_json') ?>
	<![endif]-->
	<?php wp_head(); ?>
	

	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url')?>/css/custom-ie.css" charset="utf-8" /> 
	<![endif]-->

	<!--[if lte IE 8]> 
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url')?>/css/custom-ie8.css" charset="utf-8" /> 
		<script src="<?php bloginfo('template_url')?>/js/cufon-yui.js" type="text/javascript"></script>
		<script src="<?php bloginfo('template_url')?>/js/Pictos_RIP_400.font.js" type="text/javascript"></script>
	<![endif]-->

<script type='text/javascript'>
(function (d, t) {
  var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
  bh.type = 'text/javascript';
  bh.src = '//www.bugherd.com/sidebarv2.js?apikey=inxrqr9dcxx2m1dhnz9erq';
  s.parentNode.insertBefore(bh, s);
  })(document, 'script');
</script>

<script type="text/javascript">
$(document).ready(function(){
	$('.search-box').click(function(){
	if($(this).val() != ''){
	$('.sidebar-main-home').hide();	
	}	
	else{
	$('.sidebar-main-home').show();
	}
	});

});
</script>


</head>
<body <?php body_class()?>>
	<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
			 chromium.org/developers/how-tos/chrome-frame-getting-started -->
	<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
	<header>
		<div class="main-header bg-main-header" id="header_top">
			<div class="main-center">

				<!-- left content on header -->
				<div class="f-left f-left-all">

					<?php

					$general_opts	= new ET_GeneralOptions();
					$website_logo	= $general_opts->get_website_logo();
					?>
					<!-- fix logo middle -->
					<table class="fix-logo"><tr><td>
						<a href="<?php echo home_url()?>" class="logo"><img src="<?php echo $website_logo[0];?>" alt="<?php echo $general_opts->get_site_title();  ?>" /></a>	
					</td><tr></table>
					
					<div class="slogan"><?php echo $site_description; ?></div>
				</div>

				<!-- right content on header -->
				<div class="header-technical f-right f-left-all">
					<div class="category">
						<?php 
							je_header_menu ();
						?>
						
					</div>

					<div class="ver-line"></div>
					<?php 
						$roles		=	$current_user->roles;
						$user_role	=	array_pop ($roles);
						//if($role == 'jobseeker')
						
						if ( function_exists('et_is_resume_menu') && et_is_resume_menu() && !is_user_logged_in() ) { ?>
							
						<div class="post-job">
						
							<a href="<?php echo et_get_page_link( array('page_type' => 'jobseeker-signup' , 'post_title' => 'Create a Resume' ) ); ?>" class="bg-btn-action btn-header border-radius" title="<?php _e('Create a Resume', ET_DOMAIN)?>">
								<?php _e('CREATE A RESUME', ET_DOMAIN );?><span class="icon f-right" data-icon="W"></span>
							</a>
						</div>
						<div class="ver-line"></div>

						<?php  } else {
							
							if( $user_role == 'company' || !is_user_logged_in() || current_user_can('manage_options') ) {
							 ?>
							<div class="post-job">
								<a href="<?php echo et_get_page_link('post-a-job')?>" class="bg-btn-action btn-header border-radius" title="<?php _e('Post a Job', ET_DOMAIN)?>">
									<?php _e('POST A JOB', ET_DOMAIN );?><span class="icon f-right" data-icon="W"></span>
								</a>
							
							</div>
							<div class="ver-line"></div>
					<?php } } ?>
					<div class="account">
						<ul class="menu-header-top">
							<?php if ( et_is_logged_in() ){
								$roles	=	$current_user->roles;
								$role	=	array_pop($roles);
							 ?>
								<li <?php if(is_page_template('page-dashboard.php') || is_page_template('page-profile.php') || is_page_template('page-password.php')){ ?> class="selected" <?php } ?>>
									<a href="<?php echo apply_filters ('je_filter_header_account_link', et_get_page_link('dashboard') ) ; ?>" class="bg-btn-header btn-header" title="<?php echo $role == 'jobseeker' ? __('My profile', ET_DOMAIN) : __("Account",ET_DOMAIN);?>">
										<span class="icon" data-icon="U"></span>
									</a>
								</li>
								<li>
									<a id="requestLogout" href="<?php echo wp_logout_url( home_url() ); ?>" class="bg-btn-header btn-header" title="<?php _e('Logout', ET_DOMAIN);?>">
										<span class="icon" data-icon="Q"></span>
									</a>
								</li>
							<?php } else { ?>
								<li>
									<a id="requestLogin" class="" rel="modal-box" href="#login" title="<?php _e('Login', ET_DOMAIN);?>">
										<span data-icon="U" rel="">Login / Register</span>
									</a>
								</li>
							<?php } ?>
						</ul>
	
<!-- Start User's Fname Display on the Header-->
<?php global $current_user;
      get_currentuserinfo();

      //echo 'Username: ' . $current_user->user_login . "\n";
      //echo 'User email: ' . $current_user->user_email . "\n";
      //echo 'User first name: ' . $current_user->user_firstname . "\n";
      //echo 'User last name: ' . $current_user->user_lastname . "\n";
      //echo 'User display name: ' . $current_user->display_name . "\n";
      //echo 'User ID: ' . $current_user->ID . "\n";
?>

<?php
if ( is_user_logged_in() ) {

?>

   <div style="color:#fff; width: 315px; margin-top: -19px;">&nbsp;&nbsp;
   <?php  echo 'Welcome ' . $current_user->user_firstname ; ?>

<?php
if ( is_user_logged_in() ) {
global $current_user;
get_currentuserinfo();
echo $image = get_avatar( $current_user->ID , $size='50' );
}
?>
   </div>

<?php 
} else {
    //echo 'Welcome, visitor!';
}
?>
	
<!-- End User's Fname Display on the Header-->			</div>

				</div>

			</div>
		</div>
		<?php if( !is_home() && !is_tax() && !is_post_type_archive('job') &&  !is_search() )  { ?>
		<div class="header-second">
			<div class="main-center breadcrumb">
				<?php 
				if(!is_page () ) 
					echo et_breadcrumbs(array ('showCurrent' => false, 'home'			=>	__('Home', ET_DOMAIN)));
				else echo et_breadcrumbs(array ('showCurrent' => true, 'home'			=>	__('Home', ET_DOMAIN))); ?>
			</div>
		</div>
		<?php } ?>

		<?php if( et_is_logged_in() ){ // insert current user data here for js
			$role	=	$current_user->roles;
			$user_role	=	array_pop($role);
			if( $user_role == 'company' || $user_role == 'administrator' ) 
				$user_data	=	et_create_companies_response($current_user);
			else 
				$user_data	=	et_create_user_response($current_user);
		 ?>
			<script type="application/json" id="current_user_data">
				<?php echo json_encode( $user_data );?>
			</script>
		<?php } ?>
		<div class="clear"></div>
	</header>
