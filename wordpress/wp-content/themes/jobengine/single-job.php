<?php
global $et_global, $post, $user_ID;
$imgUrl	=	$et_global['imgUrl'];
$jsUrl	=	$et_global['jsUrl'];
$job      = $post;
get_header ();
?>
<div class="wrapper content-container" id="single-job">
<style type="text/css">
	.plupload	 {
		width: 200px !important;
		height: 100px !important;
	}
</style>
<?php 
if(have_posts()) { the_post ();

	$job_data	=	et_create_jobs_response($post);
	
	$job_cats  	= $job_data['categories'];
	// get all job types
	$job_types		=	$job_data['job_types'];
	$job_location	= 	$job_data['location'];
	$job_full_location 	= $job_data['full_location'];

	$company		= et_create_companies_response( $post->post_author );
	$company_logo	= $company['user_logo'];

	$expire = $job_data['expired_date'];

	if(current_user_can('edit_others_posts')) {
	?>
		<div class="heading-message message" <?php if ($post->post_status ==  'publish' ) { echo 'style ="display:none;"' ; }?>>
			<div class="main-center">
				<div class="text">
				<?php 
				$statuses	= array(
					'draft'		=> __('NOT READY',ET_DOMAIN),
					'pending'	=> __('PENDING',ET_DOMAIN),
					'archive'	=> __('ARCHIVED',ET_DOMAIN),
					'reject'	=> __('REJECTED',ET_DOMAIN),
					'publish'	=> __('ACTIVE', ET_DOMAIN)
				);
				if($post->post_status == 'pending')
					_e("THIS JOB IS PENDING. YOU CAN APPROVE OR REJECT IT.",ET_DOMAIN);
				else 
					printf(__("THIS JOB IS %s.",ET_DOMAIN), $statuses[$post->post_status]);
				?>
				</div>
				<div class="arrow"></div>
			</div>
		</div>
		<?php }?>

		<div class="heading">
			<div class="main-center">
				<?php if(current_user_can('edit_others_posts') || $user_ID == $job->post_author) {   ?>
				<div class="technical font-quicksand f-right job-controls">

					<!-- admin action -->
					<?php if (current_user_can ('edit_others_posts') ) {?>
					<div class="f-right" id="adminAction" <?php if ($post->post_status == 'publish' ) { echo 'style ="display:none;"' ; }?> >
						<a href="#" class="color-active" id="approveJob">
							<span data-icon="3" class="icon"></span>
							<?php _e("APPROVE",ET_DOMAIN);?>
						</a>
						<a rel="modal-box" href="#modal_reject_job" class="color-pending">
							<span data-icon="*" class="icon"></span><?php _e("REJECT",ET_DOMAIN);?>
						</a>
					</div>
					<?php } ?>
					<!-- admin action -->

					<a rel="modal-box" href="#modal_edit_job" class="color-edit">
						<span data-icon="p" class="icon"></span><?php _e("EDIT THIS JOB",ET_DOMAIN);?>
					</a>          
				</div>
				<?php } ?>
				
				<h1 data="<?php echo $job->ID;?>" class="title job-title" id="job_title"><?php the_title()?>
					<?php if($job_data['post_views'] > 0) { ?>  
					<span class="vcount">(<?php if($job_data['post_views'] == 1) _e("1 view", ET_DOMAIN); else printf(__("%d views", ET_DOMAIN), $job_data['post_views'])  ?>)</span>
					<?php } ?>
				</h1>
			</div>
		</div>
	
		<div class="heading-info clearfix mapoff">
			<div class="main-center">
				<div class="info f-left f-left-all">
					<div class="company job-info">
						<?php
							if (0){ //!empty($company_logo)
								?>
								<div class="thumb_logo">
									<a id="job_author_thumb" class="thumb job_author_link" href="<?php echo $company['post_url'] ?>" 
										title="<?php printf(__('View posted jobs by %s', ET_DOMAIN), $company['display_name']) ?>">
										<img src="<?php echo $company_logo['thumbnail'][0]; ?>" id="company_logo_thumb" data="<?php echo (isset($company_logo['attach_id'])) ? $company_logo['attach_id'] : '';?>" />
									</a>
								</div>
								<?php
							}
						?>
						
						<!-- Job author, type, location, posted date -->
						<div class="company-name">
							<a  href="<?php echo get_author_posts_url($company['ID'])?>" data="<?php echo $company['ID'];?>"
								title="<?php printf(__('View posted jobs by %s', ET_DOMAIN), $company['display_name']) ?>" class="name job_author_link" id="job_author_name">
							  <?php echo $company['display_name']?>
							</a>
						</div>

						<!-- job type -->
						<div id="job_type" class="job-type">
							<?php if( !empty($job_types) ) {
								foreach($job_types as $job_type){
								?>
								<input class="job-type-slug" type="hidden" value="<?php echo $job_type['slug']; ?>"/>
								<a class="<?php echo 'color-' . $job_type['color']; ?>" href="<?php echo $job_type['url'] ?>" title="<?php printf(__('View posted jobs in %s ', ET_DOMAIN), $job_type['name']) ?>">
									<span class="flag"></span>
									<?php echo $job_type['name'] ?>
								</a>
								<?php 
									break;
								}
							}?>
						</div>
						<!-- end job type -->

						<?php if($job_location != '') { ?>
							<span class="icon location" data-icon="@"></span>
							<?php 
							$tooltip 	= '';
							if($job_location != __('Anywhere', ET_DOMAIN) && $job_data['location_lat'] != '' && $job_data['location_lng'] != '') {
								$tooltip = __('View map', ET_DOMAIN);
							}
							?>
							<div title="<?php echo $tooltip ?>" class="job-location" id="job_location">								
								<?php echo $job_location ?>
							</div>
							<input type="hidden" name="jobFullLocation" value="<?php echo $job_full_location ?>" >
							<input type="hidden" name="jobLocLat" value="<?php echo $job_data['location_lat'] ?>" >
							<input type="hidden" name="jobLocLng" value="<?php echo $job_data['location_lng'] ?>" >
						<?php } ?>

						<span class="icon date" data-icon="\"></span>
						<div class="date">							
							<?php the_date () ?>
						</div>
					</div>

				</div>

				<!-- social share -->
				<div class="sharing">
					<?php 
					$api = get_option('et_addthis_api', '');
					//$api	=	'ra-525f557a07fee94d';
					if ($api)
						$api = '#pubid=' . $api;
					?>
					<span class="h"><?php _e('Share', ET_DOMAIN) ?>:</span>
					<!-- AddThis Button BEGIN -->
					<div class="addthis_toolbox addthis_default_style ">
						<ul>
							<li><a id="addthis_button_facebook  sharing-btn" class="addthis_button_facebook at300b sharing-btn"><img src="<?php bloginfo( 'template_directory' ) ?>/img/share-fb.png" width="40" height="40" border="0" alt="Share to Facebook" /></a></li>
							<li><a id="addthis_button_twitter  sharing-btn" class="addthis_button_twitter at300b sharing-btn"><img src="<?php bloginfo( 'template_directory' ) ?>/img/share-twitter.png" width="40" height="40" border="0" alt="Share to Twiiter" /></a></li>
							<li><a id="addthis_button_google_plusone_share  sharing-btn" class="addthis_button_google_plusone_share at300b sharing-btn"><img src="<?php bloginfo( 'template_directory' ) ?>/img/share-gplus.png" width="40" height="40" border="0" alt="Share to Google plus" /></a></li>
							<li><a id="addthis_button_linkedin  sharing-btn"  class="addthis_button_linkedin at300b sharing-btn"><img src="<?php bloginfo( 'template_directory' ) ?>/img/share-in.png" width="40" height="40" border="0" alt="Share to LinkedIn" /></a></li>
						</ul>
					</div>
						<script type="text/javascript">var addthis_config = {
							"data_track_addressbar":false,
						};
					</script>
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js<?php echo $api; ?>"></script>
					<!-- AddThis Button END -->
				</div>
				<!-- end social share -->

				<div class="clear"></div>

				<!-- job map -->
				<div id="jmap" class="<?php if ($job_location == __('Anywhere', ET_DOMAIN)) echo 'anywhere '; ?>heading-map hide">
				</div>
				
			</div>
		</div>

		<div class="main-center padding-top30">

			<div class="main-column">
				
				<div class="job-detail tinymce-style">
					<?php do_action( 'je_before_job_description', $job);?>
					<div class="description" id="job_description">
					
					<?php 
						/**
						 * job description 
						*/
						the_content();
					?>
					</div>
					<?php 
					// action for plugin job fields
					do_action( 'je_single_job_fields', $job); 

					do_action( 'je_after_job_description', $job);
					?>
				</div>
				

				<?php  
				/**
				 *  apply template  
				*/
				//include(locate_template('template-apply.php'));
				get_template_part('template-apply');

				?>				
				
			</div>

			<?php get_sidebar() ?>
			
			<div class="clearfix"></div>

			<!-- inject job data here for bootstrapping model -->
			<script type="application/json" id="job_data">
				<?php echo json_encode( $job_data ); ?>
			</script>
			<script type="application/json" id="company_data">
				<?php echo json_encode( $company ); ?>
			</script>

			<script type="text/template" id="apply_button">
				<button title="<?php _e('Apply for this job',ET_DOMAIN); ?>" class="bg-btn-action border-radius btn-default btn-apply applyJob" id="apply2">
					<?php _e("APPLY FOR THIS JOB",ET_DOMAIN); ?>
					<span class="icon" data-icon="R"></span>
				</button> 
			</script>
			<script type="text/template" id="how_to_apply_button">
				<button title="<?php _e('HOW TO APPLY', ET_DOMAIN); ?>" class="bg-btn-action border-radius btn-default btn-apply applyJob" id="apply3">
					<?php _e("HOW TO APPLY",ET_DOMAIN); ?>
					<span class="icon" data-icon="O"></span>
				</button> 
			</script>
			<script type="text/template" id="apply_detail">
				<h5><?php _e("HOW TO APPLY FOR THIS JOB", ET_DOMAIN); ?></h5>
				<div class="description"><%=applicant_detail%></div>
				<a href="#" class="back-step icon" data-icon="D"></a>
			</script>
		</div>
<?php }?>
</div>

<?php 
	get_footer();