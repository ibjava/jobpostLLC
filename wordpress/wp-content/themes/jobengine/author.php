<?php get_header(); ?>

<div class="wrapper clearfix content-container">
	<?php 
	global $et_global;
	$company_id		= get_query_var('author');	
	$company		= et_create_companies_response( $company_id );
	$company_logo	= $company['user_logo'];
	?>
	<div class="heading">
		<div class="main-center">
			<h1 class="main-column uppercase"><?php printf( __('Jobs at %s', ET_DOMAIN), $company['display_name'] ) ?></h1>
		</div>
	</div>
	<div class="account-title">
		<div class="main-center clearfix">
			<?php $count = et_get_job_count(array('post_author' => $company_id)); ?>
			<div class="main-column job-status">
				<?php 
				printf( et_number( __("%d active job by %s", ET_DOMAIN), __("%d active job by %s", ET_DOMAIN), __("%d active jobs by %s", ET_DOMAIN), $count['publish'] ), $count['publish'], $company['display_name'] ); 
				?>
			</div>
		</div>
	</div>

	<div class="main-center clearfix">

		<div class="main-column main-left" id="job_list_container">
			<?php 
			wp_reset_query();
			global $wp_query, $disable_actions;
			$disable_actions = true;
			$job_list = array();
			?>
			<ul class="list-jobs">
				<?php 
				if ( have_posts() ) : 
					while (have_posts()) : the_post();
						global $job;
						$job		= et_create_jobs_response($post);
						$latest_jobs[]	= $job;
						load_template( apply_filters( 'et_template_job', dirname(__FILE__) . '/template-job.php'), false);
					endwhile; // end while have_posts()
				endif; // end if have_posts ?>
			</ul>
			<?php if ( $wp_query->max_num_pages > 1 ) {?>
			<div class="button-more">
				<button class="btn-background border-radius"><?php _e('Load More Jobs', ET_DOMAIN );?></button>
			</div>
			<?php } ?>
		</div>

		<?php get_sidebar() ?>
		
	</div>

	<script type="application/json" id="jobs_list_data">
		<?php echo json_encode($job_list);?>
	</script>

	<script type="application/json" id="author_data">
		<?php
			echo json_encode(array(
				'display_name'	=> $company['display_name'],
				'user_url'		=> $company['user_url'],
				'user_logo'		=> $company_logo
			)
		);
		?>
	</script>

</div>

<?php get_footer(); ?>