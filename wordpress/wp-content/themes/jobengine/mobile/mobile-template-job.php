<?php 
global $job;
//$colours  		= et_get_job_type_colors();

?>
<li data-icon="false" class="list-item">
	<span class="arrow-right"></span>
	<a href="<?php the_permalink() ?>" data-transition="slide">
		<p class="name"><?php the_title(); ?></p>
    	<p class="list-function">
    		<span class="postions"><?php echo get_the_author_meta('display_name') ?></span>
    		<?php if (count($job['job_types']) > 0 ) { ?>
				<span class="type-job color-<?php echo $job['job_types'][0]['color']; ?>"><span class="flags flag<?php echo $job['job_types'][0]['color']; ?>"></span><?php echo $job['job_types'][0]['name']; ?></span>
			<?php } ?>	
			<?php if ($job['location'] != '') { ?>
    			<span class="locations"><span class="icon" data-icon="@"> </span><?php echo $job['location']; ?></span>
			<?php } ?>
    	</p>
    </a>
</li>