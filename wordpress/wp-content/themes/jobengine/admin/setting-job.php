<div class="et-main-main clearfix  inner-content" id="setting-job"  <?php if ($sub_section != 'job') echo 'style="display:none"' ?>>
	<?php require_once 'content-jobs.php';?>
	<div class="title font-quicksand"><?php _e("Pending jobs",ET_DOMAIN);?></div>
	<div class="desc">
	 	<?php _e("Enabling this will make every new job post pending until you review and approve it manually.",ET_DOMAIN);?>			
		<?php /*<!-- <a class="find-out font-quicksand" href="#">
	 		<?php _e("Find out more",ET_DOMAIN);?> <span class="icon" data-icon="i"></span>
	 	</a> --> */ ?>
		<div class="inner no-border btn-left">
			<div class="payment">
				<div class="button-enable font-quicksand">
					<?php et_display_enable_disable_button('pending_job', __("Pending job",ET_DOMAIN), 'pending_job'); ?>
				</div>
			</div>
		</div>	        				
	</div>	
	
</div>


