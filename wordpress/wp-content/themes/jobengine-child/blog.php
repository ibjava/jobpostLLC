<?php

/**
 * Template Name: Blog
 */


get_header();
?>
<?php if (have_posts()) {
	the_post();
	$column	= 'full-column';
	if(is_active_sidebar('sidebar-page')) $column	=	'main-column'; 
?>







<div class="wrapper clearfix">
	<div class="heading">
		<div class="main-center">
			<h1 class="title job-title" id="job_title"><?php the_title()?></h1>
		</div>
	</div>
	<div class="main-center">





<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
		
               
             
				<?php
			

$wp_query->query('showposts=500&cat=1,2,3,4,5,6,7,8,9,10,11,12,13,14,15'.'&paged='.$paged);
				
while ($wp_query->have_posts()) : $wp_query->the_post();
?>
				


<div style="width:1000px; margin:auto; margin-top:50px; border-bottom: 1px dashed #ccc; overflow: hidden;">

<div style="width:200px; float:left; margin-right: -28px;">
<a href="<?php the_permalink(); ?>">
<?php //the_post_thumbnail( array(150,150) ); ?>
<?php if ( has_post_thumbnail() ) {
the_post_thumbnail( array(150,150) );
} else { ?>
<img src="<?php bloginfo('template_url'); ?>/img/default-image.png" width="150" height="150" alt="<?php the_title(); ?>" />
<?php } ?>

</a>
</div>

<div style="width:750px; float:left; margin-bottom: 30px;">
<a href="<?php the_permalink(); ?>" style="font-family: Arial, san-serif; font-size: 20px; text-decoration: none; color:#6b6b6b; font-weight: bold;
"><?php the_title();?></a>	
<p style="margin-bottom: 35px;"><font class="join-date" style="font-weight: bold;">posted on</font>  <span class="header font-quicksand"><?php the_time('F j, Y g:i a') ?> /<?php the_author_posts_link(); ?>  /  <?php the_category(', ') ?>  /  <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></span></p>

<div style="margin-top: -19px; text-align: justify;"><?php //the_excerpt(75); ?><?php echo substr(get_the_excerpt(), 0,400); ?> <a href="<?php the_permalink(); ?>"  class="header font-quicksand">Read More</a></div>
</div>


</div>

<?php endwhile; ?>


	<!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->			
				
				
<?php /* Display navigation to next/previous pages when applicable */ ?>

<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">←</span> Older posts', 'twentythirteen' ) ); ?></div>
<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">→</span>', 'twentythirteen' ) ); ?></div>

<!-- using wp-paginate plugin for the coloring and designing -->




<!-- ******************************************************************************** -->
	

<!-- Page Layout Ends -->



	</div><!-- #content -->
	</div><!-- #primary -->


<div style="clear:both;"></div>









	</div>
</div>





<?php }

get_footer();
