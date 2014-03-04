<?php et_get_mobile_header('mobile');  
	global $et_global,$post,$wp_query;
	$arr             = array();
	$job_type_color  = et_get_job_types();
	$colours         = et_get_job_type_colors();
	$enable_featured = et_is_enable_feature();
?>
<div data-role="search" class="search-area">
	<div class="search">
		<a href="#" class="icon ui-btn-s search-btn  category-btn" data-icon="l"></a>
		<div class="search-text">
			<input type="text" name="search" id="txt_search" class="txt_search" placeholder="<?php _e("Enter job titles", ET_DOMAIN); ?>" >
			<span class="icon" data-icon="s"></span>
		</div>

		<div class="menu-filter">
			<div class="menu-filter-inner">
				<div class="icon-header">
					<a class="icon" data-icon="l"></a>
				</div>
	            <div class="search">
					<input type="text" name="search" id="search_location" title="<?php _e("Enter the location...", ET_DOMAIN); ?>" placeholder="<?php _e("Enter the location...", ET_DOMAIN); ?>">
					<span class="icon" data-icon="@"></span>
				</div>
				<div class="tabs job-tabs">
					<a class="ui-tabs ui-corner-left tab-active" id="cat">
						<?php _e('Categories',ET_DOMAIN); ?>
					</a>
					<a class="ui-tabs ui-corner-right" id="job-type" >
						<?php _e('Contract types',ET_DOMAIN) ?>
					</a>						
				</div>				

	            <div class="content-tabs job-contents">
	            	<div class="tab-cont cat">
	            		<div class="list-categories">
	            			<a data="" class="ui-list ui-list-active ui-list-main"><?php _e('All categories',ET_DOMAIN); ?></a>
	            			<ul>
	            				<?php et_template_front_category_mobile(); ?>
	            			</ul>
	            		</div>
	            	</div>
	            	<div class="tab-cont job-type">
	            		<div class="contact-type">
	                		<ul>
	            				<?php 
	            					foreach ($job_type_color as $key => $value) { ?>
	            						<li><a data="<?php echo $value->slug; ?>" class="ui-list color-<?php if(isset($colours[$value->term_id])) echo $colours[$value->term_id]; ?>"><?php echo $value->name ; ?><span class="icon-label flag"></span></a></li>
	            				<?php }	?>
	            					
	            			</ul>
	            		</div>
	            	</div>	                		
	            </div>

	            <a href="#" class="ui-btn-s btn-grey filter-search-btn" id="et_search_cat"> <?php _e('Search',ET_DOMAIN); ?> </a>
	        </div>
		</div>
	</div>
</div>

<div data-role="content" id="page" class="ui-home resume-content-home">
	<ul class="listview" data-role="listview" id="job-content">
		<?php  
		if ( have_posts() ) {
			$page       = $wp_query->max_num_pages;
			$class_name = '';
			$first_post = $post->ID;
			$flag       = 0;
			$flag_title = 0;
			//echo '<li class="list-divider">'.__("Feature Jobs",ET_DOMAIN).'</li>';
			while (have_posts() ) { the_post();
				//print_r( $job_type );
				$featured = et_get_post_field( $post->ID, 'featured' );//echo $featured;	
				global $job;
				$job = et_create_jobs_response($post);

				if ($flag_title == 0 && $featured == 1) {
				 	echo '<li class="list-divider">'.__("Featured Jobs",ET_DOMAIN).'</li>';
					$flag_title = 1;
				}
				else if ($featured == $flag ) {
					$flag = 1;
					echo '<li class="list-divider">'.__("Jobs",ET_DOMAIN).'</li>';
				}

				load_template( apply_filters( 'et_mobile_template_job', dirname(__FILE__) . '/mobile-template-job.php'), false);
				?>
					<!-- <li class="list-item">
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
					</li> -->
				<?php 
			}
		} ?>	
	</ul>
	<?php
		$cur_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
		if ( isset($page) && $cur_page < $wp_query->max_num_pages ) { ?>
			<a href="#" class="btn-grey btn-wide btn-load-more ui-corner-all et_loadmore" id="et_loadmore"><?php _e('Load More Jobs',ET_DOMAIN); ?></a>
	<?php } ?>
</div><!-- /content -->
<input type="hidden" id="cur_page_index" value="<?php echo $cur_page; ?>">
<?php et_get_mobile_footer('mobile'); ?>