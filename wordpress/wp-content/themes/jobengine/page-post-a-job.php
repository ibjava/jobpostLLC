<?php 
/**
 * Template Name: Post a Job
 */

global $current_user, $wp_rewrite;
$general_opt	= new ET_GeneralOptions();

$job	= get_query_var('job_id');



if (!!$job){
	$job	= get_post($job);
	if ( !isset($job->ID) || !isset($current_user->ID) || $job->post_author != $current_user->ID ){

		// not the job author, redirect to this page without var
		wp_redirect( et_get_page_link('post-a-job') );
		exit;
	}

	$job	= et_create_jobs_response($job);
}

$job_opt 		= new ET_JobOptions () ;
$contact_widget	= $job_opt->get_post_job_sidebar ();

if( isset($current_user->ID)) {
	$recent_location  	=	 et_get_user_field ($current_user->ID,'recent_job_location');
	
	$full_location 		=	isset($recent_location['full_location']) ? $recent_location['full_location'] : '' ;
	$location 			=	isset($recent_location['location']) ? $recent_location['location'] : '';
	$location_lat		=	isset($recent_location['location_lat']) ? $recent_location['location_lat'] : '';
	$location_lng 		=	isset($recent_location['location_lng']) ? $recent_location['location_lng'] : '';
	$company			=	et_create_companies_response($current_user->ID);
	$apply_method		=	$company['apply_method'];
	$apply_email		=	$company['apply_email'];
	$applicant_detail	=	$company['applicant_detail'];
} else {
	$apply_method		=	'isapplywithprofile';
	$apply_email		=	'';
	$applicant_detail	=	'';
	$full_location 		=	 '';
	$location 			=	 '';
	$location_lat		=	 '';
	$location_lng 		=	 '';
}

get_header(); ?>

<div class="wrapper">
	<div class="heading">
		<div class="main-center">
			<h1 class="title"><span class="icon" data-icon="W"></span>
				<?php 
					if(!$job) {
						_e('Post A Job (FREE)', ET_DOMAIN);
					}
					else {
						_e('Renew this Job', ET_DOMAIN);
					}
				?>
			</h1>
		</div>
	</div>
	
	<div class="main-center margin-top25 clearfix">
	<?php 
		$main_colum	=	'full-column';
		if( !empty($contact_widget) || current_user_can('manage_options') ) {
			$main_colum	=	'main-column';
		}
	?>
		<div class="<?php echo $main_colum ?>" id="post_job">
			
			<?php if(!!$job){ // add the existed job data here for js ?>
				<script type="application/json" id="job_data">
					<?php echo json_encode($job);?>
				</script>
			<?php }?>

			<div class="post-a-job">
				<?php $steps = array('1','2','3','4'); ?>

				<div class="step current <?php // if(!!$job) echo 'completed';?>" id='step_package'>
					<div class="toggle-title f-left-all  <?php if(!!$job) echo 'toggle-complete';?>">
						<div class="icon-border"><?php echo array_shift($steps) ?></div>
						<span class="icon" data-icon="2"></span>
						<span><?php _e('Post Your Job', ET_DOMAIN);?></span>
					</div>
					<div class="toggle-content clearfix">
					<?php 
					global $current_user;
					$plans = et_get_payment_plans();
					do_action ('je_before_job_package_list');

					if ( !empty($plans) ){	?>
						
						<ul>
							<?php 
							foreach ($plans as $plan) :
								$sel = ( isset($job['job_package']) && $job['job_package'] == $plan['ID']) ? 'selected' : '';

								$featured_text = $plan['featured'] ? __('featured', ET_DOMAIN) : __('normal', ET_DOMAIN);
								$plan['quantity'] = isset($plan['quantity']) ? $plan['quantity'] : 1;
								if ($plan['quantity'] > 1){
									$content_plural = sprintf( __('Each job will be displayed as %s for %d days.', ET_DOMAIN), $featured_text, $plan['duration'] );
									$content_single = sprintf( __('Each job will be displayed as %s for %d day.', ET_DOMAIN), $featured_text, $plan['duration'] );
								}else {
									$content_plural = sprintf( __('Your job will be displayed as %s for %d days.', ET_DOMAIN), $featured_text, $plan['duration'] );
									$content_single = sprintf( __('Your job will be displayed as %s for %d day.', ET_DOMAIN), $featured_text, $plan['duration'] );
								}
								$desc = $plan['duration'] == 1 ? $content_single : $content_plural;
								$purchase_plans = !empty($current_user->ID) ? et_get_purchased_quantity($current_user->ID) : array();
							?>
							<li class="clearfix <?php // echo $sel;?>">
								<div class="label f-left">
									<div class="title">
										<?php echo $plan['title'] ?> 
										<?php if($plan['price'] > 0) {?>
											<span> <?php echo et_get_price_format( $plan['price'], 'sup' ) ?> </span> 
										<?php } ?>
										<?php 
										// if current user have purchased plans, show they 
										if (!empty($purchase_plans[$plan['ID']]) && $purchase_plans[$plan['ID']] > 0) {
											echo '<span class="quan"> - ';
											echo $purchase_plans[$plan['ID']] > 1 ? 
												sprintf( __('You have %d jobs in this plan', ET_DOMAIN), $purchase_plans[$plan['ID']]) : 
												sprintf( __('You have %d job in this plan', ET_DOMAIN), $purchase_plans[$plan['ID']]);
											echo '</span>';
										} else if($plan['price'] > 0) {
											echo '<span class="quan"> - ';
												echo $plan['quantity'] > 1 ? 
													sprintf( __('This plan includes %s jobs', ET_DOMAIN), $plan['quantity']) : 
													sprintf( __('This plan includes %s job', ET_DOMAIN), $plan['quantity']);
											echo '</span>';
										}
										?>

									</div>
									<div class="desc"><?php echo $desc ?></div>
								</div>
								<div class="btn-select f-right">
									<button class="bg-btn-hyperlink border-radius select_plan" data-package="<?php echo $plan['ID'];?>" data-price="<?php echo $plan['price'];?>"><?php _e('Select', ET_DOMAIN );?></button>
								</div>
							</li>
							<?php endforeach; ?>
						</ul>
					<?php }
					do_action ('je_after_job_package_list');
					 ?>
					<script id="package_plans" type="text/data">
					<?php echo json_encode($plans); ?>
					</script>						
					</div>
				</div>
				<?php if ( !et_is_logged_in() ){ ?>
				<div class="step" id='step_auth'>
					<div class="toggle-title f-left-all">
						<div class="icon-border"><?php echo array_shift($steps) ?></div>
						<span class="icon" data-icon="2"></span>
						<span><?php _e('Login or create an account', ET_DOMAIN );?></span>
					</div>
					<div class="toggle-content login clearfix" style="display: none">
						<div class="tab-title f-left-all clearfix">
							<div class="bg-tab active"><?php _e('Register', ET_DOMAIN );?></div>
							<div class="bg-tab"><span><?php _e('Already have an account?', ET_DOMAIN );?></span> <?php _e('Login', ET_DOMAIN );?></div>
						</div>
						<div class="tab-content">
							<div class="form current">
								<form id="register" novalidate="novalidate" autocomplete="on">
									<div class="form-item">
										<div class="label">
											<label for="reg_email">
												<h6 class="font-quicksand"><?php _e('USER NAME', ET_DOMAIN );?></h6>
												<?php _e('Please enter your username', ET_DOMAIN );?>
											</label>
										</div>
										<div>
											<input class="bg-default-input is_user_name" tabindex="1" name="reg_user_name" id="reg_user_name" type="text"/>
										</div>
									</div>
									<div class="form-item">
										<div class="label">
											<label for="reg_email">
												<h6 class="font-quicksand"><?php _e('EMAIL ADDRESS', ET_DOMAIN );?></h6>
												<?php _e('Please enter your email address', ET_DOMAIN );?>
											</label>
										</div>
										<div>
											<input class="bg-default-input is_email" tabindex="1" name="reg_email" id="reg_email" type="email"/>
										</div>
									</div>
									<div class="form-item">
										<div class="label">
											<label for="reg_pass">
												<h6 class="font-quicksand"><?php _e('PASSWORD', ET_DOMAIN );?></h6>
												<?php _e('Enter your password', ET_DOMAIN );?>
											</label>
										</div>
										<div>
											<input class="bg-default-input is_pass" tabindex="2" name="reg_pass" id="reg_pass" type="password" />
										</div>
									</div>
									<div class="form-item">
										<div class="label">
											<label for="reg_pass_again">
												<h6 class="font-quicksand repeat_pass "><?php _e('RETYPE YOUR PASSWORD', ET_DOMAIN );?></h6>
												<?php _e('Retype your password', ET_DOMAIN );?>
											</label>
										</div>
										<div>
											<input class="bg-default-input" tabindex="3" name="reg_pass_again" id="reg_pass_again" type="password" />
										</div>
									</div>
									<div class="form-item no-border-bottom clearfix">
										<div class="label">&nbsp;</div>
										<div class="btn-select">
											<button class="bg-btn-action border-radius" tabindex="4" type="submit" id="submit_register"><?php _e('CONTINUE', ET_DOMAIN );?></button>
										</div>
									</div>
								</form>
							</div>
							<div class="form">
								<form id="login" novalidate="novalidate" autocomplete="on">
									<div class="form-item">
										<div class="label">
											<h6 class="font-quicksand"><?php _e('USERNAME or EMAIL ADDRESS', ET_DOMAIN );?></h6>
											<?php _e('Please enter your username or email', ET_DOMAIN );?>
										</div>
										<div>
											<input class="bg-default-input is_email is_user_name" tabindex="1" name="log_email" id="log_email" type="text" />
										</div>
									</div>
									<div class="form-item">
										<div class="label">
											<h6 class="font-quicksand"><?php _e('PASSWORD', ET_DOMAIN );?></h6>
											<?php _e('Enter your password', ET_DOMAIN );?>
										</div>
										<div>
											<input class="bg-default-input is_pass" tabindex="2" name="log_pass" id="log_pass" type="password" />
										</div>
									</div>
									<div class="form-item no-border-bottom clearfix">
										<div class="label">&nbsp;</div>
										<div class="btn-select">
											<button class="bg-btn-action border-radius" tabindex="3" type="submit" id="submit_login"><?php _e('LOGIN', ET_DOMAIN );?></button>
										</div>
										<a href="#" class="forgot-pass-link"><?php _e('FORGOT PASSWORD', ET_DOMAIN)?></a>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="step <?php if(!!$job) echo 'completed';?>" id='step_job'>
					<div class="toggle-title f-left-all <?php if(!!$job) echo 'toggle-complete';?>">
						<div class="icon-border"><?php echo array_shift($steps) ?></div>
						<span class="icon" data-icon="2"></span>
						<span><?php _e('Describe your job and your company', ET_DOMAIN );?></span>
					</div>
					<div class="toggle-content login clearfix" style="display: none">
						<div class="form">
							<form id="job_form" method="post" enctype="multipart/form-data" novalidate="novalidate" autocomplete="on">
								<div id="job_info">
									<div class="form-item">
										<div class="label">
											<h6 class="font-quicksand"><?php _e('JOB TITLE', ET_DOMAIN );?></h6>
											<?php _e('Enter a short title for your job', ET_DOMAIN );?>
										</div>
										<div>
											<input class="bg-default-input" tabindex="1" name="title" id="title" type="text" value="<?php if(isset($job['title'])) echo $job['title'];?>" />
										</div>
									</div>
									<div class="form-item">
										<div class="label">
											<h6 class="font-quicksand"><?php _e('JOB DESCRIPTION', ET_DOMAIN );?></h6>
											<?php _e('Describe your job in a few paragraphs ', ET_DOMAIN );?>
										</div>
										<div class="job_description">
											<?php 
												// if(isset($job['content'])) $content	=	$job['content']; else $content	= '' ;
												// 	wp_editor( $content ,'content' , je_job_editor_settings () );
											// ?>
											<textarea class="bg-default-input tinymce" tabindex="2" name="content" id="content"><?php if(isset($job['content'])) echo $job['content']; else echo ' '; ?></textarea>
										</div>
									</div>
									<div class="form-item">
										<div class="label">
											<h6 class="font-quicksand"><?php _e('JOB LOCATION', ET_DOMAIN );?></h6>
											<?php _e('Enter a city and country or leave it blank', ET_DOMAIN );?>
										</div>
										<div>
											<div class="address">
												<?php 
													if(isset($job['full_location'])) $full_location 	=	 $job['full_location'];
													if(isset($job['location'])) 	$location 			=	 $job['location'];
													if(isset($job['location_lat'])) $location_lat		=	 $job['location_lat'];
													if(isset($job['location_lng'])) $location_lng 		=	 $job['location_lng'];
												?>
												<input class="bg-default-input" name="full_location" tabindex="3" id="full_location" type="text" value="<?php  echo $full_location ?>"/>
												<input type="hidden" name="location" id="location" value="<?php echo $location; ?>" />
												<input type="hidden" name="location_lat" id="location_lat" value="<?php echo $location_lat;?>" />
												<input type="hidden" name="location_lng" id="location_lng" value="<?php  echo $location_lng; ?>" />
												<div class="address-note">
													<?php _e('Examples: "Melbourne VIC", "Seattle", "Anywhere"', ET_DOMAIN) ?>
													<!--
													<?php _e('Display location as', ET_DOMAIN) ?>: <span title="<?php _e('Edit location', ET_DOMAIN) ?>" id="add_sample">"<?php echo $location; ?>"</span>
													<input style="display:none" type="text" maxlength="50" id="add_sample_input" value="<?php echo $location; ?>">
												-->
												</div>
											</div>
											<div class="maps">
												<div class="map-inner" id="map"></div>
											</div>
										</div>
									</div>
									<!-- How to apply -->
									<div class="form-item">
										<div class="label">
											<h6 class="font-quicksand"><?php _e('HOW TO APPLY', ET_DOMAIN );?></h6>
											<?php _e('Select how you would want jobseekers to submit their applications', ET_DOMAIN );?>
										</div>
										<div class="apply">
											<input type="hidden" id="apply_method" value="">
											<input type="radio" name="apply_method" id="isapplywithprofile" value="isapplywithprofile" <?php if($apply_method != 'ishowtoapply') echo 'checked' ?> />
											<label class="font-quicksand" for="isapplywithprofile">
												<?php _e("Allow job seekers to submit their cover letter and resume directly", ET_DOMAIN);?>
											</label>
											<div class="email_apply">
												<span class=""><?php _e("Send applications to this email address:", ET_DOMAIN); ?></span>&nbsp;
												<input class="bg-default-input application-email" type="text" name="apply_email" id="apply_email" value="<?php echo $apply_email; ?>"/> </br>
												<span class="example"><?php _e("e.g. 'application@demo.com'", ET_DOMAIN); ?></span>
											</div>
										
											<input type="radio" name="apply_method" id="ishowtoapply" value="ishowtoapply" <?php if( $apply_method == 'ishowtoapply') echo 'checked' ?> />
											<label class="font-quicksand" for="ishowtoapply" ><?php _e("Job seekers must follow the application steps below", ET_DOMAIN);?></label>
											<div class="applicant_detail">
												<?php  wp_editor( $applicant_detail, 'applicant_detail', je_job_editor_settings () ) ?>
												<!-- <textarea name="applicant_detail" id="applicant_detail"><?php echo $applicant_detail ?></textarea> -->
											</div>

										</div>
									</div>
									<!-- END How to apply -->
									<div class="form-item">
										<div class="label">
											<h6 class="font-quicksand"><?php _e('CONTRACT TYPE', ET_DOMAIN );?></h6>
											<?php _e('Select the correct type for your job', ET_DOMAIN );?>
										</div>
										<div class="select-style btn-background border-radius">
											<?php et_job_type_select('job_types'); ?>
										</div>
									</div>
									<div class="form-item">
										<div class="label">
											<h6 class="font-quicksand"><?php _e('JOB CATEGORY', ET_DOMAIN );?></h6>
											<?php _e('Select a category for your job', ET_DOMAIN );?>
										</div>
										<div class="select-style btn-background border-radius">
											<?php et_job_cat_select ('categories') ?>
										</div>
									</div>

									<!-- CUSTOM FIELD -->
									<?php do_action('et_post_job_fields') ?>

								</div>

								<div id="company_info">
									<div class="form-item">
										<div class="label">
											<h6 class="font-quicksand"><?php _e('COMPANY NAME', ET_DOMAIN );?></h6>
											<?php _e('Enter your company name', ET_DOMAIN );?>
										</div>
										<div>
											<input class="bg-default-input" tabindex="6" name="display_name" id="display_name" type="text" value="<?php if ( is_user_logged_in() ) { echo $current_user->display_name; }?>"/>
										</div>
									</div>
									<div class="form-item">
										<div class="label">
											<h6 class="font-quicksand"><?php _e('COMPANY WEBSITE', ET_DOMAIN );?></h6>
											<?php _e('Enter your company website', ET_DOMAIN );?>
										</div>
										<div>
											<input class="bg-default-input" tabindex="7" name="user_url" id="user_url" type="text" value="<?php if ( is_user_logged_in() ) { echo $current_user->user_url; }?>" />
										</div>
									</div>
									<?php $uploaderID = 'user_logo';?>
									<div class="form-item" id="<?php echo $uploaderID;?>_container" style="display:none;">
										<!-- <div class="label">
											<h6><?php _e('COMPANY LOGO', ET_DOMAIN );?></h6>
											<?php _e('Upload your company logo', ET_DOMAIN );?>
										</div> -->
										<!-- <div>
											<span class="company-thumbs" id="<?php echo $uploaderID;?>_thumbnail">
											<?php
												if ( is_user_logged_in() ) {
													$user_logo	= et_get_company_logo( $current_user->ID );
													if (!empty($user_logo)){
														?>
														<img src="<?php echo $user_logo['company-logo'][0]; ?>" id="<?php echo $uploaderID;?>_thumb" data="<?php echo $user_logo['attach_id'];?>" />
														<?php
													}
												}
											?>
											</span>
										</div> -->
										<!-- <div class="input-file clearfix et_uploader">
											<!-- <span class="btn-background border-radius button" id="<?php echo $uploaderID;?>_browse_button" tabindex="8" >
												<?php _e('Browse...', ET_DOMAIN );?>
												<span class="icon" data-icon="o"></span>
											</span> 
											 <span class="et_ajaxnonce" id="<?php echo wp_create_nonce( $uploaderID . '_et_uploader' ); ?>"></span>
										    <div class="clearfix"></div>
										    <div class="filelist"></div> 
										</div> -->
									</div>
								</div>

								<?php do_action('je_post_job_after_author_info') ?>

								<div class="form-item clearfix">
									<div class="label">&nbsp;</div>
									<div class="btn-select">
										<button class="bg-btn-action border-radius" tabindex="9" type="submit" id="submit_job"><?php _e('CONTINUE', ET_DOMAIN );?></button>										
									</div>									

									<div class="btn-cancel">
										<a href="<?php echo et_get_page_link('post-a-job') ?>" class="btn-background border-radius button" id="indeed_search">
											<?php _e("CANCEL", ET_DOMAIN); ?>
										</a>
									</div>
									
								</div>
							</form>
						</div>
					</div>
				</div>

				<?php 
				$payment_gateways	=	et_get_enable_gateways();
				
				if(!empty($payment_gateways) ) {
				?>
				<div class="step" id='step_payment'>
					<div class="toggle-title f-left-all">
						<div class="icon-border"><?php echo array_shift($steps) ?></div>
						<span class="icon" data-icon="2"></span>
						<span><?php _e('Send payment and submit your job', ET_DOMAIN );?></span>
					</div>

					<div class="toggle-content payment clearfix" style="display: none" id="payment_form">
						<form method="post" action="" id="checkout_form">
							<div class="payment_info"> </div>
							<div style="position:absolute; left : -7777px; " >
								<input type="submit" id="payment_submit" />
							</div>
						</form>
						<ul>
						<?php 
						$je_default_payment	=	array('google_checkout', 'paypal', 'cash', '2checkout')	;
						
						do_action ('before_je_payment_button', $payment_gateways);			
						foreach ($payment_gateways as $key => $payment) {
							if( !isset($payment['active']) || $payment['active'] == -1 || !in_array($key, $je_default_payment)) 
								continue;
						?>
							<li class="clearfix">
								<div class="f-left">
									<div class="title"><?php echo $payment['label']?></div>
									<?php if(isset($payment['description'])) {?>
									<div class="desc"><?php echo $payment['description'] ?></div>
									<?php }?>
								</div>
								<div class="btn-select f-right">
									<button class="bg-btn-hyperlink border-radius select_payment" data-gateway="<?php echo $key?>" ><?php _e('Select', ET_DOMAIN );?></button>
								</div>

							</li>
						<?php 
						}
						do_action ('after_je_payment_button', $payment_gateways);
						?>
						</ul>
					</div>
					
				</div>
				<?php }?>
			</div>

		</div>
		<?php 
			if( !empty($contact_widget) || current_user_can('manage_options') ) {
			
		?>
			<div class="second-column widget-area" id="static-text-sidebar">
			<div id="sidebar" class="post-job-sidebar">
				<?php foreach ($contact_widget as $key => $value) { ?>
				<div class="widget widget-contact bg-grey-widget" id="<?php echo $key ?>">
					<div class="view">
						<?php echo $value ?>
					</div>
					<?php if(current_user_can('manage_options')) { ?>
					<div class="btn-widget edit-remove"> 
						<a href="#" class="bg-btn-action border-radius edit"><span class="icon" data-icon='p'></span></a> 
						<a href="#" class="bg-btn-action border-radius remove"><span class="icon" data-icon='#'></span></a> 
					</div>
					<?php } ?>
				</div>
				
				<?php } ?>
			</div>

			<?php if(current_user_can('manage_options')) { ?>
				<div class="widget widget-contact bg-grey-widget" id="widget-contact">
					<a href="#" class="add-more"><?php _e('Add a text widget +', ET_DOMAIN) ?> </a>
				</div>
			<?php } ?>

			</div>
		<?php } ?>
	</div>

	

</div>

<?php get_footer(); 
