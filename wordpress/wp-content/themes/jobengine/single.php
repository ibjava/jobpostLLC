<?php
get_header();
if(have_posts() ) {
	global $post;
	the_post();
	$date		=	get_the_date('d S M Y');
	$date_arr	=	explode(' ', $date );
	
	$cat		=	wp_get_post_categories($post->ID);
	if(isset($cat[0])) 
	$cat		=	get_category($cat[0]);

?>
<div class="wrapper clearfix">
	<div class="heading">
		<div class="main-center">
			<h1 class="title job-title" id="job_title"><?php _e("OUR BLOG",ET_DOMAIN);?></h1>
		</div>
	</div>
	<div class="main-center">
		<div class="main-column">
			<div class="entry-blog ">
				<div class="thumbnail font-quicksand">
					<div class="img-thumb">
						
							<?php echo get_avatar($post->post_author)?>
						
					</div>
					<div class="author">
						
						<?php the_author()?>
						
					</div>
					<div class="join-date"><?php the_date(); ?></div>
				</div>
        		<div class="content single-entry">
	          		<div class="header font-quicksand">
	           			<?php if(isset($cat->name ) )  { ?>
		           			<a href="<?php  echo get_category_link($cat)?>">
								<?php echo $cat->name ?>
		           			</a> 
		           		<?php } ?>
	           			<a href="<?php the_permalink()?>" class="comment">
	           				<span class="icon" data-icon="q"></span>
	           				<?php comments_number('0','1','%')?>
	           			</a>
	          		</div>
          			<h2 class="title">
           	 			<a href="<?php the_permalink()?>" title="<?php the_title()?>" ><?php the_title ()?></a>
          			</h2>
          			<div class="description tinymce-style">
		      	          <?php the_content('')?>
          			</div>
        		</div>
        		<div class="comments">
					<h3 class="title"><?php comments_number (__('0 Comment on this Article', ET_DOMAIN), __('1 Comment on this Article', ET_DOMAIN), __('% Comments on this Article', ET_DOMAIN))?> </h3>
		      	    <?php comments_template('', true)?>
		      	</div>      
		 	</div>
		</div>

		<?php if(is_active_sidebar('sidebar-blog')) {  ?>
			<div id="sidebar-blog" class="second-column f-right widget-area <?php if(current_user_can('manage_options') ) echo 'sortable' ?>">
			<?php dynamic_sidebar('sidebar-blog');?>
			</div>
		<?php } ?>
	</div>
</div>
<?php }
get_footer();