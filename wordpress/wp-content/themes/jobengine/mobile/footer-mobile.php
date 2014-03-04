<style type="text/css">


</style>
	<div class="ui-footer">
		<?php 
			$general_opt	=	new ET_GeneralOptions();
			$copyright		=	$general_opt->get_copyright();
			if( $copyright != '') {
				echo '<p><strong>'.$copyright.'</strong></p>';
			}	
		if(has_nav_menu( 'et_mobile_footer' ))
			wp_nav_menu(array (
					'theme_location' => 'et_mobile_footer',
					'container' => 'ul',
					'menu_class'	=> 'menu-bottom',
			)); ?>
	</div>

	
	       
    	
    </div>
    
	<script type="text/template" id="template_job">
		<?php echo et_template_mobile_job(); ?>
	</script>
	<script type="text/template" id="template_resume">
		<?php echo et_template_mobile_resume(); ?>
	</script>
	<?php do_action('et_mobile_footer') ?>
    
</body>
</html>