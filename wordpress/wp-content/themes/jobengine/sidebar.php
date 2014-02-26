<?php 
if ( is_home() || is_search() || is_post_type_archive('job') || is_tax('job_category') || is_tax('job_type') || apply_filters( 'je_need_index_sidebar', false ) ){ 
// index sidebar
?>
	<div class="widget-area second-column">

		<?php if(current_user_can ('edit_others_posts')) { ?>

			<aside class="widget widget-select widget_archive content-dot" id="archives-2" style="display:none;">
				<h3 class="widget-title"><?php _e('JOB STATUS', ET_DOMAIN) ?></h3>
				<?php $query_var = get_query_var('post_status'); 
				if ( !is_array($query_var) ){
					$query_var  = explode(',', $query_var);
				}
				?>
				<ul class="category-lists filter-jobstatus filter-joblist" id="status_filter">
					<?php $post_count = wp_count_posts('job'); ?>
					<li class="status-item status-reject" id="filter_reject"><a class="<?php if ( in_array('reject', $query_var) ) echo " active "; ?>" data="reject" href="#"><?php _e('Rejected Jobs', ET_DOMAIN) ?><span class="count"><?php echo $post_count->reject ?></span></a></li>
					<li class="status-item status-archive" id="filter_archive"><a class="<?php if ( in_array('archive', $query_var) ) echo " active "; ?>" data="archive" href="#"><?php _e('Archived Jobs', ET_DOMAIN) ?><span class="count"><?php echo $post_count->archive ?></span></a></li>
				</ul>
			</aside>  
		<?php }?>

		<div id="sidebar-main" class="widget <?php if(current_user_can('manage_options') ) echo 'sortable' ?>">
			<?php	
				if( is_active_sidebar ('sidebar-main')) {
					dynamic_sidebar ('sidebar-main');
				} else {
					if(current_user_can('manage_options')) _e("This sidebar is not active. Please go to the Widgets setting and add the widget to your sidebar.", ET_DOMAIN);
				}
			?>
			
		</div>

	</div>
<?php }

if( is_singular('job') ) {  // single job sidebar
?>
	<div class="second-column widget-area <?php if(current_user_can('manage_options') ) echo 'sortable' ?>" id="sidebar-job-detail">
				
	<?php 
		if(is_active_sidebar('sidebar-job-detail')) { 
			dynamic_sidebar('sidebar-job-detail');
		}  else {
			JE_Company_Profile ();
		}
	?>				

	</div>
<?php
}

if (is_page_template('page-companies.php')) { // companies list sidebar
?>
	<div class="second-column widget-area <?php if(current_user_can('manage_options') ) echo 'sortable' ?>" id="sidebar-companies">
				
		<?php 
		if(is_active_sidebar('sidebar-companies')) { // companies sidebar 
			dynamic_sidebar('sidebar-companies');
		} else {
			JE_Company_Count ();
		} ?>
		
	</div>
<?php
}
if(is_author()) { // author sidebar
?>
	<div class="second-column widget-area <?php if(current_user_can('manage_options') ) echo 'sortable' ?>" id="sidebar-company">
		
	<?php 
		if(is_active_sidebar('sidebar-company')) {
			dynamic_sidebar('sidebar-company');
		} else {
			JE_Company_Profile ();
		}
	?>
		
	</div>
<?php 
}