<?php 
/**
 * Template Name: Companies Index
 */
get_header(); 
?>

<div class="wrapper content-container">

		<div class="heading">
			<div class="main-center">
				<h1 class="title"><?php _e("COMPANIES",ET_DOMAIN);?></h1>	
			</div>
		</div>		
		<div class="account-title">
			<div class="main-center clearfix">
				<ul class="list-alphabet">
					<?php $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
					echo '<li><a href="#" data="">'.__('All', ET_DOMAIN).'</a></li>';
					for($i = 0; $i < strlen($alphabet); $i++){
						echo '<li><a href="#filter/'.substr($alphabet, $i, 1).'" data="'.substr($alphabet, $i, 1).'">' . substr($alphabet, $i, 1) . '</a></li>';
					}
					?>
				</ul>        
			</div>
		</div>

		<div class="main-center margin-top25 clearfix ">

			<div class="main-column">

				<div class="keyword search-form btn-default">
		  			<input type="text" id="companies_filter" class="bg-default-input placeholder" placeholder="<?php _e('Enter a keyword ...', ET_DOMAIN) ?>" value="" />
		  			<span class="icon" data-icon="s"></span>
				</div>
				<div class="companies-container">
					<?php 
					global $wpdb;
					$count 				= et_get_job_count();
					$companies 			= et_get_active_companies();
					$companies_count 	= count($companies);
					$alphabet_list 		= et_get_companies_in_alphabet();
					if ( $companies_count > 0 ){
						?>
						<ul id="list_company" class="list-company jquery-multi-column">
						<?php foreach ($alphabet_list as $letter => $companies) { ?>
							<?php if ( !empty($companies) ){ ?>
							<li class="company-section" data-char="<?php echo trim($letter) ?>">
								<div class="title"><?php echo $letter == 'numbers' ? '0-9' : $letter; ?> </div>
								<ul>
								<?php foreach ((array)$companies as $company) { ?>
									<li><a class="company-item" href="<?php echo get_author_posts_url($company->ID, $company->user_login) ?>" title="<?php echo $company->display_name ?>"><?php echo $company->display_name ?></a></li>
								<?php }  ?>
								</ul>
							</li>
							<?php } ?>
						<?php } ?>
						</ul>
						<?php 
					}
					?>
				</div>
				

				<!-- sort result data of company index -->
				<div class="jquery-sort-column">
					<ul></ul>
					<ul></ul>
					<ul class="last-child"></ul>
				</div>

			</div>

			<?php get_sidebar() ?>
			
		</div>
	</div>
	<script type="text/javascript">
		<?php
		/**
		 * Render javascript objects for companies model
		 */
		if ( $companies_count > 0 ){
			echo 'var companies = [';
			$models = array();
			foreach ($alphabet_list as $letter => $companies){
				foreach ((array)$companies as $company) {
					$models[] = "{'id' : " . $company->ID . ",'display_name':'".$company->display_name."','post_url': '" . get_author_posts_url($company->ID) . "'}";
				}
			}
			echo implode(',', $models);
			echo '];';
		}
		?>
	</script>
<?php get_footer(); ?>