<?php 
et_get_mobile_header('mobile'); 
global $wp_query;
$queried_object =   $wp_query->queried_object;

?>
<div data-role="content" class="resume-contentpage">		
	<h1 class="title-resume"><?php _e("OUR BLOG",ET_DOMAIN);?></h1>
    <div class="list-blog inset-shadow">
    	<ul>
            <?php while(have_posts()) { the_post(); 
                $date       =   get_the_date('d S M Y');
                $date_arr   =   explode(' ', $date );
                
                $cat        =   wp_get_post_categories($post->ID);
                
                $cat        =   get_category($cat[0]);
            ?>
            	<li>
                    <div class="infor-resume clearfix" style="border-bottom:none !important;">
                    	<span class="arrow-right"></span>
                        <div class="thumb-img" style="margin-left: 0px !important;">
                            <a href="#"><?php echo get_avatar($post->post_author, '50')?> </a>
                        </div>
                        <div class="intro-text">
                            <h1><?php the_author()?> </h1>
                            <p class="blog-date">
                                <?php echo  get_the_date(); ?>,&nbsp;&nbsp;
                                <span>
                                    <a href="<?php echo get_category_link($cat)?>">
                                        <?php echo $cat->name ?>
                                    </a> 
                                </span>
                                &nbsp;&nbsp;
                                <span class="blog-count-cmt">
                                    <span class="icon" data-icon="q"></span>
                                    <?php comments_number('0','1','%')?>
                                </span>
                            </p>	    		
                        </div>
                    </div>
                    <div class="blog-content">
                        <a href="<?php the_permalink(); ?>" class="blog-title"><?php the_title(); ?></a>
                        <div class="blog-text">
                            <?php the_excerpt(); ?>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
    <?php if($wp_query->max_num_pages > 1 ) { ?>
    <div class="load-blog inset-shadow">
    	<div class="button-more">
		  <button  id="load-more-post" class="btn-background border-radius"><?php _e("Load More Articles", ET_DOMAIN); ?></button>
          <input type="hidden" name="template" id="template" value="<?php echo $wp_query->query_vars['cat'] ?>"/>
		</div>
    </div>
    <?php } ?>
</div><!-- /content -->

<?php et_get_mobile_footer('mobile'); ?>