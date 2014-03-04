<?php et_get_mobile_header('mobile'); ?>
<?php 
global $post;
if(have_posts()) {
	the_post ();

	$jobseeker 	= get_userdata($post->post_author);
	$jobseeker 	= JE_Job_Seeker::convert_from_user($jobseeker);
	$resume 	= JE_Resume::convert_from_post($post);
	// var_dump($resume);

	if ( $jobseeker->et_contact || !isset($_GET['action']) || $_GET['action'] != 'send_message' ){
?>
	<div data-role="content" class="resume-contentpage">
		<h1 class="title-resume">
			<?php printf( __("Profile of %s",ET_DOMAIN), $jobseeker->display_name); ?>
		</h1>
		<div class="infor-resume inset-shadow clearfix">
			<div class="thumb-img">
	    		<?php echo et_get_resume_avatar($jobseeker->ID, 50); ?>
	    	</div>
	    	<div class="intro-text">
	    		<h1><?php the_title(); ?></h1>
	    		<p class="positions"><?php echo $resume->et_profession_title ?></p>	    		
	    	</div>
		</div>
		<?php if ( $resume->et_location != '') { ?>
			<div class="content-info">
				<span class="arrow-right"></span>
				<a class="list-link job-loc" href="<?php echo home_url(); ?>?post_type=resume&location=<?php echo $resume->et_location; ?>" rel="external" data-transition="slide" id="com_location">
					<span class="icon-locations"></span><?php echo $resume->et_location; ?> 
				</a>
			</div>
		<?php } ?>
		<?php if (!empty($resume->et_url)){ ?>
			<div class="content-info">
				<a class="list-link job-loc" href="<?php echo $resume->et_url ?>" rel="external" data-transition="slide">
					<span class="link-website"><span class="icon-link-website"></span><?php echo $resume->et_url ?></span>
				</a>
				<span class="arrow-right"></span>
			</div>
		<?php } ?>
		<?php if (!empty($resume->available) ) { 
			$values = array_map('et_mobile_resume_taxo_values',$resume->available); ?>
			<div class="content-info">
				<span class="arrow-right"></span>
				<a class="list-link job-loc" href="<?php echo home_url(); ?>?post_type=resume&available=<?php echo implode(',', $values); ?>" rel="external" data-transition="slide" id="com_location">
					<span class="icon-flags"></span><?php echo implode(', ', $values) ?>
				</a>
			</div>
		<?php } ?>
		<?php $content = get_the_content( ); 
		if($content != '') {
		?>
		<div class="content-info content-text">
			<h1><?php _e('ABOUT ME', ET_DOMAIN) ?></h1>
			<?php the_content(); ?>
		</div>
		<?php } ?>
		<?php if (!empty($resume->et_education)) { ?>
			<div class="content-info content-text content-timeline">
				<h1 class="line"><?php _e('EDUCATION', ET_DOMAIN) ?></h1>
				<?php foreach ($resume->et_education as $key => $item) { ?>
					<div class="line-stand">
						<span class="dotted"></span>
						<div class="intro">
							<span class="year"><?php echo $item['from']['display'] ?> - <?php echo $item['to']['display'] ?></span><br />
							<span class="name"><?php echo $item['name'] ?></span>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php } ?>

		<?php if (!empty($resume->et_experience)) { ?>
			<div class="content-info content-text content-timeline">
				<h1 class="line"><?php _e('WORK EXPERIENCE', ET_DOMAIN) ?></h1>
				<?php foreach ($resume->et_experience as $key => $item) { ?>
					<div class="line-stand">
						<span class="dotted"></span>
						<div class="intro">
							<span class="year"><?php echo $item['from']['display'] ?> - <?php echo $item['to']['display'] ?></span><br />
							<span class="name"><?php echo $item['name'] ?></span>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
		<?php if (!empty($resume->skill)) { ?>
		<div class="content-info content-text skill">
			<h1 class="line"><?php _e('Skill', ET_DOMAIN) ?></h1>
			<?php $skills = array_map('et_mobile_resume_taxo_values', $resume->skill) ?>
			<p>
			<?php echo implode('</p><p>', $skills) ?>
			</p>
		</div>
		<?php }
		if( !$jobseeker->et_contact ) { ?>
		<div class="content-info content-text">
			<a href="?action=send_message" data-role="button" class="btn_contact"><?php _e("CONTACT", ET_DOMAIN); ?></a>		
		</div>
		<?php } /*
			<div class="content-info content-text">
				<p class="fontsize14">Busy right now? You can remind this Profile by your email</p>
				<a href="#modal-mail" data-role="button" class="btn_remind modal-open" rel="leanModal">Remind this Profile</a>		
			</div>
			<div id="_modal-mail" class="modal remind-popup" style="display:none">			
				<h3>Email reminder</h3>
				<p>We will send you an email with the job information for later review.</p>
				<div class="input-text-remind">
					<input type="text" name="emails" placeholder="Enter Email Address" >
					<span class="icon input-icon" data-icon="M"></span>
				</div>
				<a href="#"  class="ui-btn-s btn-blue btn-wide">Send reminder</a>
			</div>

			<div id="modal-mail" class="modal remind-popup" style="display: none">
				<input type="hidden" id="current_job_id" value="<?php echo $job->ID; ?>">
				<h3><?php _e('Email reminder',ET_DOMAIN); ?></h3>
				<p><?php _e('We will send you an email with the job information for later review.',ET_DOMAIN); ?></p>
				<div class="input-text-remind">
					<input type="text" name="emails" id="remind_email">
					<span class="icon input-icon" data-icon="M"></span>
				</div>
				<a href="#" id="et_remind_email" class="ui-btn-s btn-blue btn-wide"><?php _e('Save this job',ET_DOMAIN); ?></a>
			</div>
		*/ ?>

	</div><!-- /content -->
	
	<div class="share-social">
		<h1><?php _e('Share',ET_DOMAIN); ?></h1>
		<ul>
			<li>
				<a href="http://twitter.com/home?status=<?php the_title(); ?> - <?php the_permalink(); ?>" class="ui-link">
					<span class="icon-tw"></span><?php _e('Tweet this profile',ET_DOMAIN); ?>
				</a>
			</li>
			<li>
				<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>" class="ui-link">
				<span class="icon-fb"></span><?php _e('Share on Facebook',ET_DOMAIN); ?>
				</a>
			</li>
			<li>
				<a href="mailto:type email address here?subject=share this post from <?php echo bloginfo('name'); ?>&body=<?php the_title(); ?>&#32;&#32;<?php the_permalink(); ?>" class="ui-link">
					<span class="icon-mail"></span><?php _e('Send via Email',ET_DOMAIN); ?>
				</a>
			</li>
		</ul>
	</div>
	<?php }  // end if not action "sending message"
	// sending message
	else { ?>
		<div data-role="content" class="post-content">
			<h1 class="post-title job-title">
				<?php 
					printf( __('Message %s', ET_DOMAIN), $jobseeker->display_name);
				?>
			</h1>
			<?php 
			$sender = array(
				'name' => empty($current_user->display_name) ? "" : $current_user->display_name,
				'email' => empty($current_user->user_email) ? "" : $current_user->user_email
				);
			?>
			<form action="" id="jobseeker_message" data-ajax="false" method="post">

				<input type="hidden" name="receive" value="<?php echo $resume->post_author ?>">
				<div class="content-field inset-shadow">
					<h3><?php _e('Your name',ET_DOMAIN); ?></h3>
					<div class="input-text">
						<input type="text" name="sender_name" autocomplete="off" value="<?php echo $sender['name'] ?>">
					</div>
					<h3><?php _e('Email address',ET_DOMAIN); ?></h3>
					<div class="input-text">
						<input type="text" name="sender_email" autocomplete="off" value="<?php echo $sender['email'] ?>">
					</div>
					<h3><?php _e('Message',ET_DOMAIN); ?></h3>
					<div class="input-text">
						<textarea name="message" id="" cols="30" rows="10"></textarea>
					</div>
				</div>
				<div class="content-field f-padding">
					<div class="input-button">
						<input type="submit" class="send" value="<?php _e('Send',ET_DOMAIN); ?>">
					</div>
					<div class="clearfix"></div>
				</div>
				<input type="hidden" id="cancel_url" value="<?php echo remove_query_arg( 'action' ) ?>">
			</form>
			<div data-role="popup" class="msg-success msg-pop" id="msg_pop">
				<p><p>
			</div>
		</div><!-- /content -->
	<?php } ?>
<?php } // end if have posts ?>
<?php et_get_mobile_footer('mobile'); ?>