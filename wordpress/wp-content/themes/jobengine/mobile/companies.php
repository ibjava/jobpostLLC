<?php et_get_mobile_header('mobile'); ?>
<div data-role="content" class="post-content">
	<?php  
		global $et_global,$post,$wp_query;
		$company_id      = get_query_var('author');	
		$company         = et_create_companies_response( $company_id );
		$company_logo    = $company['user_logo']['company-logo'][0];
		if ( empty($company_logo) ){
			$general_opt  = new ET_GeneralOptions();
			$temp = $general_opt->get_website_logo();
			$company_logo = $temp[0];
		}
		$count           = et_get_job_count(array('post_author' => $company_id));
		$colours         = et_get_job_type_colors();
		$enable_featured = et_is_enable_feature();
		$arr = array(
			'author' => $company_id
		);
		$temp = et_query_jobs($arr);
	?>
	<h1 class="post-title job-title">
		<?php echo $company['display_name']; ?>
		<a href="#" class="post-title-link icon" data-icon="A"></a>
	</h1>
	<div class="company-info inset-shadow">
		<div class="company-thumb">
			<div class="thumb"><img src="<?php echo $company_logo; ?>" /></div>
		</div>
		<div class="company-detail">
			<?php if ( !empty($company['user_url']) ) : ?>
				<div class="content-info">
					<a class="list-link job-employer" rel="nofollow" target="_blank" href="<?php echo $company['user_url']; ?>"><?php echo $company['user_url']; ?></a>
					<a data-icon="A" class="post-title-link icon ui-link" href="<?php echo $company['user_url']; ?>" rel="nofollow" target="_blank"></a>
				</div>
			<?php endif; ?>
			<div class="content-info">
				<a class="list-link job-loc" href=""> 
					<?php echo _n( sprintf('%d active job',$count['publish']), sprintf('%d active jobs', $count['publish']), $count['publish'] ); ?>
				</a>
			</div>
		</div>
	</div>
	
	<ul class="listview">
	<?php  
	$args = array();
	$publish_job	=	et_query_jobs ( $args );

	if( have_posts() ){
		$class_name = '';
		$first_post = $post->ID;
		$first_job_type = et_get_the_job_type( $first_post, 'featured' );
		$first_job_type_tax = isset($first_job_type[0]) ? $first_job_type[0]->term_taxonomy_id : '';
		$flag = 0;
		$flag_title = 0;

		while (have_posts()) {	the_post();
			$job_id		=	get_the_ID();

			$job_cat 	=	et_get_the_job_category ($job_id);
			$job_cat 	=	isset($job_cat[0]) ? $job_cat[0] : '';

			$job_type	=	et_get_the_job_type ($job_id);
			$job_type 	=	isset($job_type[0]) ? $job_type[0] : '';

			$job_location =	et_get_post_field ($job_id, 'location');
			$featured = et_get_post_field( $job_id, 'featured' );
			//echo $featured;	
			if ($flag_title == 0 && $featured == 1) {
				echo '<li class="list-divider">'.__("Featured Jobs",ET_DOMAIN).'</li>';
				$flag_title = 2;
			}
			if($flag == 0 && $featured == 1){	?>
				<li class="list-item">
					<a href="<?php the_permalink() ?>" data-transition="slide">
						<h2 class="list-title">
							<?php the_title(); ?>
						</h2>
						<p class="list-subtitle">
							<?php if( $job_cat != '') { ?>
								<span class="list-info job-loc"><?php echo $job_cat->name; ?></span>
							<?php } ?>
							<?php if ($job_type != '') { ?>
							<span class="list-info job-title color-<?php echo $colours[$job_type->term_id]; ?>"><span class="icon-label flag"></span><?php echo $job_type->name; ?></span>
							<?php } ?>
							<?php if ($job_location != '') { ?>
								<span class="list-info job-loc icon" data-icon="@"><?php echo $job_location; ?></span>
							<?php } ?>
						</p>
					</a>
					<div class="mblDomButtonGrayArrow arrow">
						<div></div>
					</div>
				</li>
	<?php	}	  
			if ($featured == $flag ) {
				$flag = 1;
				echo '<li class="list-divider">'.__("Jobs",ET_DOMAIN).'</li>';
			}
			if ($flag == 1 && $featured == 0) {		?>
				<li class="list-item">
					<a href="<?php the_permalink() ?>">
						<h2 class="list-title">
							<?php the_title(); ?>
						</h2>
						<p class="list-subtitle">
							<?php if( $job_cat != '') { ?>
								<span class="list-info job-loc"><?php echo $job_cat->name; ?></span>
							<?php } ?>
							<?php if ($job_type != '') { ?>
							<span class="list-info job-title color-<?php echo $colours[$job_type->term_id]; ?>"><span class="icon-label flag"></span><?php echo $job_type->name; ?></span>
							<?php } ?>	
							<?php if ($job_location != '') { ?>
								<span class="list-info job-loc icon" data-icon="@"><?php echo $job_location; ?></span>
							<?php } ?>
						</p>
					</a>
					<div class="mblDomButtonGrayArrow arrow">
						<div></div>
					</div>
				</li>
	<?php	}
		}	
	}	?>
	</ul>
	<?php  
		$max_page_company = $wp_query->max_num_pages;
		$cur_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
		if ($max_page_company > 1) {	?>
			<a href="#" class="btn-grey btn-wide btn-load-more ui-corner-all" id="lm_com_job"><?php _e('Load More Jobs',ET_DOMAIN); ?></a>
<?php	}	?>
	<input type="hidden" id="company" value="<?php echo $company_id; ?>">
	<input type="hidden" id="max_page_com" value="<?php echo $max_page_company ;?>">
	<input type="hidden" id="cur_page" value="<?php echo $cur_page; ?>">
</div><!-- /content -->
<?php et_get_mobile_footer('mobile'); ?> 