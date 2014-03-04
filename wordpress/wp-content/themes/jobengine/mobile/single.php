<?php et_get_mobile_header('mobile'); 
if(have_posts()) { the_post ();
	global $post;
	$date       =   get_the_date('d S M Y');
    $date_arr   =   explode(' ', $date );
    
    $cat        =   wp_get_post_categories($post->ID);
    
    $cat        =   get_category($cat[0]);
?>

<div data-role="content" class="resume-contentpage">		
	<h1 class="title-resume"><?php _e("Our Blog", ET_DOMAIN); ?></h1>
	<div class="infor-resume inset-shadow clearfix" style="border-bottom:none !important;">
		<div class="thumb-img" style="margin-left: 0px !important;">
    		<a href="#"><?php echo get_avatar( $post->post_author, '50' ); ?></a>
    	</div>
    	<div class="intro-text">
    		<h1><a href="#"><?php the_author(); ?></a></h1>
    		<p class="blog-date">
                <?php the_date(); ?>,&nbsp;&nbsp;
                <span>
                    <a href="<?php echo get_category_link($cat)?>">
                        <?php echo $cat->name ?>
                    </a> 
                </span>&nbsp;&nbsp;
                <span class="blog-count-cmt">
                    <span class="icon" data-icon="q"></span>
                    <?php comments_number('0','1','%')?>
                </span>
            </p>
    	</div>
	</div>
    <div class="blog-content">
    	<h2 class="blog-title"><?php the_title(); ?></h2>
        <div class="blog-text">
        	<?php the_content(); ?>
        </div>
    </div>
	<div class="blog-info-cmt">
    	<h2><?php comments_number (__('0 Comment on this Article', ET_DOMAIN), __('1 Comment on this Article', ET_DOMAIN), __('% Comments on this Article', ET_DOMAIN))?></h2>
    </div>
    <?php comments_template('', true)?>
	
</div><!-- /content -->
<?php 
}
et_get_mobile_footer('mobile'); ?>