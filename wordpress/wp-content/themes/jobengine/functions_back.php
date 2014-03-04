<?php 
define ( 'TEMPLATEURL', get_bloginfo('template_url') );
// change this to 'production' when publishing the theme, to use minified scripts & styles instead
define ( 'ENGINE_ENVIRONMENT', 'development' );
define ( 'ENV_PRODUCTION', false ); 

define("ET_UPDATE_PATH",    "http://www.enginethemes.com/?do=product-update");
define("ET_VERSION", '2.4.1');

define("ET_ADDTHIS_API", 'ra-4e20665e3a59616c');

if(!defined('ET_URL'))
	define('ET_URL', 'http://www.enginethemes.com/');

if(!defined('ET_CONTENT_DIR'))
	define('ET_CONTENT_DIR', WP_CONTENT_DIR.'/et-content/');

if(!defined('ET_LANGUAGE_PATH') )
	define('ET_LANGUAGE_PATH', WP_CONTENT_DIR.'/et-content/lang/');




require_once TEMPLATEPATH . '/includes/index.php';
require_once TEMPLATEPATH . '/includes/exception.php';

require_once TEMPLATEPATH . '/includes/company.php';
require_once TEMPLATEPATH . '/includes/job.php';
require_once TEMPLATEPATH . '/includes/payment.php';
require_once TEMPLATEPATH . '/includes/languages.php';
require_once TEMPLATEPATH . '/includes/application.php';
require_once TEMPLATEPATH . '/includes/template.php';
require_once TEMPLATEPATH . '/includes/ajax_mobile.php';
require_once TEMPLATEPATH . '/includes/schedule.php';
require_once TEMPLATEPATH . '/includes/importer.php';
require_once TEMPLATEPATH . '/includes/customizer.php';
require_once TEMPLATEPATH . '/includes/widgets.php';
require_once TEMPLATEPATH . '/includes/update.php';

require_once TEMPLATEPATH . '/admin/index.php';
require_once TEMPLATEPATH . '/admin/overview.php';
require_once TEMPLATEPATH . '/admin/settings.php';
require_once TEMPLATEPATH . '/admin/companies.php';
require_once TEMPLATEPATH . '/admin/payments.php';
require_once TEMPLATEPATH . '/admin/wizard.php';

require_once TEMPLATEPATH . '/mobile/functions.php';

// activate resumes

require_once TEMPLATEPATH . '/resumes/index.php';

//require_once TEMPLATEPATH . '/min/utils.php';

// include resume option no matter what
// require_once TEMPLATEPATH . '/resumes/admin.php';

//require_once TEMPLATEPATH . '/includes/parsers.php';
//require_once TEMPLATEPATH . '/includes/importer.php';


//declare default template pages
et_register_page_template(array(
	'post-a-job' => __('Post a Job', ET_DOMAIN), 
	'dashboard' => __('Dashboard', ET_DOMAIN),
	'companies' => __('Companies', ET_DOMAIN),
	'profile' => __('Profile', ET_DOMAIN),
	'password' => __('Change Password', ET_DOMAIN),
	'process-payment' => __('Process Payment', ET_DOMAIN),
	'reset-password' => __('Reset Password', ET_DOMAIN)
));

/**
 * 
 */
class ET_JobEngine extends ET_Engine{
	/**
	 * company url slug
	*/
	protected $company_url;
	/**
	 * job slug: remember use this should rewrite if change slug
	*/

	static $slug 	=	array('job_archive' => 'job', 'job' => 'job' , 'company' => 'company' , 'job_category' => 'cat' , 'job_type' => 'job-type') ;

	//
	// declare post_types, scripts, styles ... which are uses in theme
	function __construct(){
		parent::__construct();
		/**
		 * filter job slug
		*/
		self::$slug	=	apply_filters( 'je_job_slug', self::$slug );
		$this->company_url	=	self::$slug['company'];

		global $current_user;
		$this->js_path = ENV_PRODUCTION ? TEMPLATEURL . '/js/min' : TEMPLATEURL . '/js';

		// declare post type
		$this->post_types = array(
			'job' => array(
				'labels' => array(
					'name' => _x('Jobs', 'post type general name', ET_DOMAIN),
					'singular_name' => _x('Job', 'post type singular name', ET_DOMAIN),
					'add_new' => _x('Add New', 'job', ET_DOMAIN),
					'add_new_item' => __('Add New Job', ET_DOMAIN),
					'edit_item' => __('Edit Job', ET_DOMAIN),
					'new_item' => __('New Job', ET_DOMAIN),
					'all_items' => __('All Jobs', ET_DOMAIN),
					'view_item' => __('View Job', ET_DOMAIN),
					'search_items' => __('Search Jobs', ET_DOMAIN),
					'not_found' =>  __('No jobs found', ET_DOMAIN),
					'not_found_in_trash' => __('No jobs found in Trash', ET_DOMAIN), 
					'parent_item_colon' => '',
					'menu_name' => 'Jobs'
					),
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true, 
				'show_in_menu' => true, 
				'query_var' => true,
				'rewrite' => array ('slug' => self::$slug['job']),
				'capability_type' => 'job',
				'capabilities' => array(
					'publish_posts' => 'publish_jobs',
					'edit_posts' => 'edit_jobs',
					'edit_others_posts' => 'edit_others_jobs',
					'delete_posts' => 'delete_jobs',
					'delete_other_posts' => 'delete_other_jobs',
					'read_private_posts' => 'read_private_jobs',
					'edit_post' => 'edit_job',
					'delete_post' => 'delete_job',
					'read_post' => 'read_job' 
					),
				'has_archive' => self::$slug['job_archive'], 
				'hierarchical' => true,
				'menu_position' => null,
				'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
				),
			'payment_plan' => array(
				'labels' => array(
					'name' => _x('Plans', 'post type general name', ET_DOMAIN),
					'singular_name' => _x('Plan', 'post type singular name', ET_DOMAIN),
					'add_new' => _x('Add New', 'payment_plan', ET_DOMAIN),
					'add_new_item' => __('Add New Plan', ET_DOMAIN),
					'edit_item' => __('Edit Plan', ET_DOMAIN),
					'new_item' => __('New Plan', ET_DOMAIN),
					'all_items' => __('All Plans', ET_DOMAIN),
					'view_item' => __('View Plan', ET_DOMAIN),
					'search_items' => __('Search Plans', ET_DOMAIN),
					'not_found' =>  __('No Plans found', ET_DOMAIN),
					'not_found_in_trash' => __('No Plans found in Trash', ET_DOMAIN), 
					'parent_item_colon' => '',
					'menu_name' => 'Plans'
					),
				'public' => false,
				'publicly_queryable' => true,
				'show_ui' => false, 
				'show_in_menu' => false, 
				'query_var' => true,
				'rewrite' => true,
				'capability_type' => 'post',
				'has_archive' => true, 
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array( 'custom_fields' )
				),
			'application' => array(
				'labels' => array(
						'name' => _x('Application', 'post type general name',ET_DOMAIN),
						'singular_name' => _x('Application', 'post type singular name', ET_DOMAIN),
						'add_new' => _x('Add New', 'application', ET_DOMAIN),
						'add_new_item' => __('Add New Application', ET_DOMAIN),
						'edit_item' => __('Edit Application', ET_DOMAIN),
						'new_item' => __('New Application', ET_DOMAIN),
						'all_items' => __('All Applications', ET_DOMAIN),
						'view_item' => __('View Application', ET_DOMAIN),
						'search_items' => __('Search Applications', ET_DOMAIN),
						'not_found' =>  __('No applications found', ET_DOMAIN),
						'not_found_in_trash' => __('No applications found in Trash', ET_DOMAIN), 
						'parent_item_colon' => '',
						'menu_name' => 'Applications'
					),
					'public' => false,
					'publicly_queryable' => true,
					'show_ui' => true, 
					'show_in_menu' => true, 
					'query_var' => true,
					'rewrite' => true,
					'capability_type' => 'post',
					'has_archive' => true, 
					'hierarchical' => false,
					'menu_position' => null,
					'supports' => array( 'title', 'editor', 'author','custom-fields' )
				),
			);

		// declare taxonomies
		$this->taxonomies = array(
			'job_category' => array(
				'object_type' => array('job'),
				'args' => array(
					'hierarchical' => true,
					'labels' => array(
						'name' => _x( 'Categories', 'taxonomy general name', ET_DOMAIN ),
						'singular_name' => _x( 'Category', 'taxonomy singular name', ET_DOMAIN ),
						'search_items' =>  __( 'Search Categories', ET_DOMAIN ),
						'all_items' => __( 'All Categories', ET_DOMAIN ),
						'parent_item' => __( 'Parent Category', ET_DOMAIN ),
						'parent_item_colon' => __( 'Parent Category:', ET_DOMAIN ),
						'edit_item' => __( 'Edit Category', ET_DOMAIN ), 
						'update_item' => __( 'Update Category', ET_DOMAIN ),
						'add_new_item' => __( 'Add New Category', ET_DOMAIN ),
						'new_item_name' => __( 'New Category Name', ET_DOMAIN ),
						'menu_name' => __( 'Categories', ET_DOMAIN ),
						),
					'show_ui' => true,
					'query_var' => true,
					'rewrite' => array( 'slug' => self::$slug['job_category'] ),
					'show_in_nav_menus' => false
					)
				),
			'job_type' => array(
				'object_type' => array('job'),
				'args' => array(
					'hierarchical' => true,
					'labels' => array(
						'name' => _x( 'Job Types', 'taxonomy general name', ET_DOMAIN ),
						'singular_name' => _x( 'Job Type', 'taxonomy singular name' , ET_DOMAIN),
						'search_items' =>  __( 'Search Job Types', ET_DOMAIN ),
						'all_items' => __( 'All Job Types', ET_DOMAIN ),
						'parent_item' => __( 'Parent Job Type', ET_DOMAIN ),
						'parent_item_colon' => __( 'Parent Job Type:', ET_DOMAIN ),
						'edit_item' => __( 'Edit Job Type', ET_DOMAIN ), 
						'update_item' => __( 'Update Job Type', ET_DOMAIN ),
						'add_new_item' => __( 'Add New Job Type', ET_DOMAIN ),
						'new_item_name' => __( 'New Job Type Name', ET_DOMAIN ),
						'menu_name' => __( 'Job Types', ET_DOMAIN ),
						),
					'show_ui' => true,
					'query_var' => true,
					'rewrite' => array( 'slug' => self::$slug['job_type'] ),
					'show_in_nav_menus' => false
					)
				)
			);

		$this->styles = array(
			'stylesheet' 	=> array('src' => get_bloginfo('stylesheet_directory') . '/style.css'),
			'screen'		=> array('src' => TEMPLATEURL . '/css/screen.css', 'media' => 'screen' ),
			'font-face'		=> array('src' => TEMPLATEURL . '/css/fonts/font-face.css' ),
			'boilerplate'	=> array('src' => TEMPLATEURL . '/css/boilerplate.css' ),
			'custom'		=> array('src' => TEMPLATEURL . '/css/custom.css', 'ver' =>  '2.3.22' ),
			//'tinymce-style'	=> array('src' => TEMPLATEURL . '/css/tinymce-style.css' ),
			'job-label'		=> array('src' => TEMPLATEURL . '/css/job-label.css' ),			
			'customization'	=> array('src' =>  et_get_customize_css_path() )
		);

		// if preview mode is triggered
		if ( isset($_GET['style_preview']) && $_GET['style_preview'] == true ){
			$this->styles['customization'] = array('src' => TEMPLATEURL . '/css/customization-preview.css?var='. rand(0,9999999) );
		}

		$this->scripts = array(
			// main application
			'job_engine'	=> array(
				'src'	=> $this->js_path . '/job_engine.js',
				'deps'	=> array('jquery', 'et-underscore', 'et-backbone')
			),
			'front'	=> array(
				'src'	=> $this->js_path . '/front.js',
				'deps'	=> array('jquery','et-underscore','et-backbone','job_engine'),
				'ver'	=> '2.3.3'
			)
		);

		// disable admin bar if user can not manage options
		if (!current_user_can('manage_options')) :
			show_admin_bar(false);
		endif;

		// add custom query var 'location'
		add_filter('query_vars', array($this, 'add_query_vars'));

		add_filter('posts_where', array($this, 'posts_where'));

		// map meta capabilities
		add_filter('map_meta_cap', array($this, 'map_meta_cap'), 10, 4);

		add_filter( 'display_post_states', array( $this, 'custom_post_state' ) );

		add_filter('author_link', array($this, 'custom_author_link'));

		add_action('wp_head', array($this, 'custom_style'));

		add_filter('et_registered_scripts', array($this, 'register_scripts'));

		add_filter('et_localize_scripts', array($this, 'filter_localize_scripts'));

		add_filter('wp_dropdown_users', array($this, 'custom_dropdown_users'));

		add_action('wp', array($this,'remove_filter_orderby'));

		add_action('admin_notices', array($this, 'notice_after_installing_theme'));

		add_action('save_post', array($this, 'save_post'));

		register_nav_menus ( array (
			'et_top'			=>	__('Menu display on the header',ET_DOMAIN),
			'et_footer'			=>	__('Menu display on the footer',ET_DOMAIN),
			'et_mobile_footer'	=>	__('Menu display on the mobile footer',ET_DOMAIN)	
		));

		add_action('template_redirect', array($this, 'authorize_page'));
		add_action('admin_menu', 'et_prevent_user_access_wp_admin');

		add_action('wp_footer',array($this, 'localize_validator'),200);
		add_action('admin_print_footer_scripts',array($this, 'localize_validator'),200);

		add_action("wp_before_admin_bar_render", array($this,"customize_admin_bar_menu") );

		remove_all_actions('do_feed_rss2');
		add_action('do_feed_rss2', array($this, 'custom_feed'), 10, 1 );	

		et_create_content_directory (); // core
		//et_create_content_directory_jobengine();//includes languages.php

		add_filter( 'et_jobengine_demonstration','do_shortcode' );

		add_action('wp_title', array ($this, 'wp_title' ) , 10, 2);

	}

    public function custom_dropdown_users($output){
        global $post;
        if($post->post_type == "job"){

        	$args = array(
				'who' => '',
				'name' => 'post_author_override',
				'selected' => empty($post->ID) ? $user_ID : $post->post_author,
				'include_selected' => true
			);

			$defaults = array(
				'show_option_all' => '', 'show_option_none' => '', 'hide_if_only_one_author' => '',
				'orderby' => 'display_name', 'order' => 'ASC',
				'include' => '', 'exclude' => '', 'multi' => 0,
				'show' => 'display_name', 'echo' => 1,
				'selected' => 0, 'name' => 'user', 'class' => '', 'id' => '',
				'blog_id' => $GLOBALS['blog_id'], 'who' => '', 'include_selected' => false
			);

			$defaults['selected'] = is_author() ? get_query_var( 'author' ) : 0;

			$r = wp_parse_args( $args, $defaults );
			extract( $r, EXTR_SKIP );

			$query_args = wp_array_slice_assoc( $r, array( 'blog_id', 'include', 'exclude', 'orderby', 'order', 'who' ) );
			$query_args['fields'] = array( 'ID', $show );
			$users = get_users( $query_args );

			$output = '';
			if ( !empty($users) && ( empty($hide_if_only_one_author) || count($users) > 1 ) ) {
				$name = esc_attr( $name );
				if ( $multi && ! $id )
					$id = '';
				else
					$id = $id ? " id='" . esc_attr( $id ) . "'" : " id='$name'";

				$output = "<select name='{$name}'{$id} class='$class'>\n";

				if ( $show_option_all )
					$output .= "\t<option value='0'>$show_option_all</option>\n";

				if ( $show_option_none ) {
					$_selected = selected( -1, $selected, false );
					$output .= "\t<option value='-1'$_selected>$show_option_none</option>\n";
				}

				$found_selected = false;
				foreach ( (array) $users as $user ) {
					$user->ID = (int) $user->ID;
					$_selected = selected( $user->ID, $selected, false );
					if ( $_selected )
						$found_selected = true;
					$display = !empty($user->$show) ? $user->$show : '('. $user->user_login . ')';
					$output .= "\t<option value='$user->ID'$_selected>" . esc_html($display) . "</option>\n";
				}

				if ( $include_selected && ! $found_selected && ( $selected > 0 ) ) {
					$user = get_userdata( $selected );
					$_selected = selected( $user->ID, $selected, false );
					$display = !empty($user->$show) ? $user->$show : '('. $user->user_login . ')';
					$output .= "\t<option value='$user->ID'$_selected>" . esc_html($display) . "</option>\n";
				}

				$output .= "</select>";
			}               
        }
        ?>
        <script type="text/javascript">
	        jQuery(document).ready(function($) {
	        	$("select#post_author_override").change(function(){
	        		$("input[name='et_author']").val(jQuery(this).val());
	        		$("input#et_company").val($("select#post_author_override").find("option:selected").text());
	        	});
	        });
        </script>
        <?php
        return $output;
    }

	public function localize_validator () {
		if( (is_admin() && isset($_GET['page']) && $_GET['page'] == 'engine-settings') || !is_admin() ) {
			?>
				<script type="text/javascript">
				(function ($) {
					$.extend($.validator.messages, {
					    required: "<?php _e("This field is required.",ET_DOMAIN) ?>",
					    email: "<?php _e("Please enter a valid email address.", ET_DOMAIN) ?>",
					    url: "<?php _e("Please enter a valid URL.", ET_DOMAIN) ?>",
					    number: "<?php _e("Please enter a valid number.", ET_DOMAIN) ?>",
					    digits: "<?php _e("Please enter only digits.", ET_DOMAIN) ?>",
					    equalTo: "<?php _e("Please enter the same value.", ET_DOMAIN) ?>"
					});
				})(jQuery);
				</script>
			<?php 
		}
	}

	public function register_scripts($scripts){

		$http		=	et_get_http();		

		$new_scripts = array(

			'et-underscore'	=> array(
				'src'		=> "$http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.3.3/underscore-min.js",
				'fallback'	=> FRAMEWORK_URL . '/js/lib/underscore-min.js'
				),
			'et-backbone'	=> array(
				'src'		=> "$http://cdnjs.cloudflare.com/ajax/libs/backbone.js/0.9.2/backbone-min.js",
				'fallback'	=> FRAMEWORK_URL . '/js/lib/backbone-min.js'
				),

			'jquery_validator'	=> array(
				'src'	=> TEMPLATEURL . '/js/lib/jquery.validate.min.js',
				'deps'	=> array('jquery')
			),
			
			'google_map_api'	=>	array('src' => "$http://maps.googleapis.com/maps/api/js?sensor=true" ),
			'gmap'		=> 	array(
				'src'	=> TEMPLATEURL . '/js/lib/gmaps.js',
				'deps'	=> array('jquery', 'google_map_api', 'job_engine','front')
			),
			// 'tiny_mce'	=>	array(
			// 	'src'	=> TEMPLATEURL . '/js/lib/tiny_mce/tiny_mce.js',
			// 	'deps'	=> array('jquery')
			// ),
			'autocomplete' => array(
				'src'	=> TEMPLATEURL . '/js/lib/jquery.autocomplete.js',
				'deps'	=> array('jquery', 'et-underscore', 'et-backbone', 'job_engine','front')
			),
			'wookmark' 	=> array(
				'src' 	=> TEMPLATEURL . '/js/lib/jquery.wookmark.min.js',
				'deps'	=> array('jquery')
			),
			'tiny_scrollbar' => array(
				'src' 	=> TEMPLATEURL . '/js/lib/jquery.tinyscrollbar.min.js',
				'deps' 	=> array('jquery')
			),
			// 'js-editor' => array(
			// 	'src' => $this->js_path . '/editor.js',
			// 	'deps' =>array('jquery','tiny_mce')
			// ),
			'companies' => array(
				'src'	=> $this->js_path . '/company.js',
				'deps'	=> array('jquery', 'et-underscore', 'et-backbone', 'job_engine', 'front')
			),
			'author' => array(
				'src'	=> $this->js_path . '/author.js',
				'deps'	=> array('jquery', 'et-underscore', 'et-backbone', 'job_engine', 'front')
			),
			'index' => array(
				'src'	=> $this->js_path . '/index.js',
				'deps'	=> array('jquery', 'et-underscore', 'et-backbone', 'job_engine', 'front'),
				'ver'	=> '2.3.3'
			),
			'post-archive' => array(
				'src'	=> $this->js_path . '/post-archive.js',
				'deps'	=> array('jquery', 'et-underscore', 'et-backbone', 'job_engine', 'front'),
			),
			'post_job'	=> array(
				'src'	=> $this->js_path . '/post_job.js',
				'deps'	=> array('jquery', 'et-underscore', 'et-backbone', 'job_engine', 'front')
			),
			'single_job'	=> array(
				'src'	=> $this->js_path . '/single-job.js',
				'deps'	=> array('jquery', 'et-underscore', 'et-backbone', 'job_engine', 'front')
			),
			'dashboard' => array(
				'src' => $this->js_path . '/dashboard.js',
				'deps'	=> array('jquery', 'et-underscore', 'et-backbone', 'job_engine', 'front')
			),
			'profile' => array(
				'src' => $this->js_path . '/profile.js',
				'deps'	=> array('jquery', 'et-underscore', 'et-backbone', 'job_engine', 'front')
			),
			'password' => array(
				'src' => $this->js_path . '/password.js',
				'deps'	=> array('jquery', 'et-underscore', 'et-backbone', 'job_engine', 'front')
			),
			'resetpassword' => array(
				'src' => $this->js_path . '/resetpassword.js',
				'deps'	=> array('jquery', 'et-underscore', 'et-backbone', 'job_engine', 'front')
			),
			'widget-sidebar' => array(
				'src' => $this->js_path . '/widget-sidebar.js',
				'deps'	=> array('jquery', 'et-underscore', 'et-backbone', 'job_engine', 'front')
			),
			'page-not-found'	=> array(
				'src'	=> $this->js_path . '/404.js',
				'deps'	=> array('jquery')
			),
		);

		$this->scripts = wp_parse_args($this->scripts, $new_scripts);
		return $this->scripts;
	}

	/**
	 * Print out the scripts
	 */
	public function print_scripts(){
		//var_dump(wp_script_is('job_engine', 'registered'));
		$general_opt=	new ET_GeneralOptions();
		echo $general_opt->get_google_analytics();

		wp_enqueue_script('jquery_validator');
		wp_enqueue_script ( 'et-underscore' );
		wp_enqueue_script ( 'et-backbone' );
		wp_enqueue_script('job_engine');
		wp_enqueue_script('jquery-ui-sortable');

		// only enqueue these scripts when needing to post/edit jobs
		if (is_singular('job') || is_page_template('page-post-a-job.php') ||
				is_page_template('page-dashboard.php') ||
				// index && having administrator rights
				( (is_home() || is_search() || is_post_type_archive('job') ||
					is_tax('job_category') || is_tax('job_type') || apply_filters( 'je_is_need_edit_job_enqueue_script', false ) ) &&
					current_user_can('manage_options') ) 
				) {

			//wp_enqueue_script('tiny_mce');
			//wp_enqueue_script('js-editor');
			wp_enqueue_script('google_map_api');
			wp_enqueue_script('gmap');
			wp_enqueue_script('plupload-all');
		}

		if(current_user_can('manage_options') && (is_page_template('page-dashboard.php') || is_page_template('page-post-a-job.php') || is_page())  ) {
			wp_enqueue_script('widget-sidebar');
		}

		// homepage & single job & post job
		if ( is_home() || is_search() || is_post_type_archive('job') || is_tax('job_category') || is_tax('job_type') || apply_filters( 'je_is_index_enqueue_script', false ) ){
			wp_enqueue_script('index');
		}
		elseif ( is_singular('job') ){
			wp_enqueue_script('single_job');
		}
		elseif (is_page_template('page-post-a-job.php')){
			wp_enqueue_script('google_map_api');
			wp_enqueue_script('gmap');
			wp_enqueue_script('post_job');

			// $_2co_api	=	ET_2CO::get_api();
		
			// if($_2co_api['use_direct']) {
			// 	wp_enqueue_script( '2co_direct_script', ET_2CO::$direct_script);
			// }
		}

		// company index, profile, dashboard, account, password
		elseif ( is_page_template('page-companies.php') ){
			wp_enqueue_script('companies');
			wp_enqueue_script('wookmark');
		}
		elseif (is_author()){
			wp_enqueue_script('author');
		}
		elseif ( is_page_template('page-dashboard.php') && is_user_logged_in() ){
			wp_enqueue_script('dashboard');
		}
		elseif ( is_page_template('page-profile.php') ){
			wp_enqueue_script('plupload-all');
			wp_enqueue_script('profile');
		}
		elseif ( is_page_template('page-password.php') ){
			wp_enqueue_script('password');
		}
		elseif ( is_page_template('page-reset-password.php') ){
			wp_enqueue_script('resetpassword');
		}

		// post category or date
		elseif (is_category() || is_date()){
			wp_enqueue_script('post-archive');
			if(current_user_can('manage_options')) wp_enqueue_script('jquery-ui-sortable');
		}
		elseif ( is_404() ){
			wp_enqueue_script('page-not-found');
		} elseif (!is_admin() ) {
			wp_enqueue_script('front');
		}

		wp_enqueue_script('tiny_scrollbar');
	}

	// print styles for job engine
	public function print_styles(){
		// enqueue google web font
		//$customization = et_get_current_customization();
		$heading 	= et_get_current_customization('font-heading');
		$text 		= et_get_current_customization('font-text');
		$action 	= et_get_current_customization('font-action');
		$fonts = array(
			'quicksand' => array(
				'fontface' 	=> 'Quicksand, sans-serif',
				'link' 		=> 'Quicksand'
			),
			'ebgaramond' => array(
				'fontface' 	=> 'EB Garamond, serif',
				'link' 		=> 'EB+Garamond'
			),
			'imprima' => array(
				'fontface' 	=> 'Imprima, sans-serif',
				'link' 		=> 'Imprima'
			),
			'ubuntu' => array(
				'fontface' 	=> 'Ubuntu, sans-serif',
				'link' 		=> 'Ubuntu'
			),
			'adventpro' => array(
				'fontface' 	=> 'Advent Pro, sans-serif',
				'link' 		=> 'Advent+Pro'
			),
			'mavenpro' => array(
				'fontface' 	=> 'Maven Pro, sans-serif',
				'link' 		=> 'Maven+Pro'
			),
		);
		$home_url	=	home_url();
		$http		=	substr($home_url, 0,5);
		if($http != 'https') {
			$http	=	'http';
		}
		foreach ($fonts as $key => $font) {
			//if ( $heading == $font['fontface'] || $text == $font['fontface'] || $action == $font['fontface'] ){
				echo "<link href='".$http."://fonts.googleapis.com/css?family=" . $font['link'] . "' rel='stylesheet' type='text/css'>";
			//}
		}
	}

	public function filter_localize_scripts($scripts){
		$root_url = home_url();

		return array_merge($scripts,  array(
			'job_engine' => array(
				'object_name'	=> 'et_globals',
				'data'			=> array(
					'ajaxURL'		=> admin_url('admin-ajax.php'),
					'homeURL'		=> home_url(),
					'imgURL'		=> TEMPLATEURL . '/img',
					'jsURL'			=> TEMPLATEURL . '/js',
					'dashboardURL'	=> et_get_page_link('dashboard'),
					'logoutURL'		=> wp_logout_url( home_url() ),
					'routerRootCompanies' 	=> et_get_page_link('companies'),
					'msg_login_ok'	=> sprintf(__('You have been logged in as %s!', ET_DOMAIN), '<%= company %>'),
					'msg_logout'	=> __('Logout', ET_DOMAIN),
					'err_field_required'		=> __('This field cannot be blank!', ET_DOMAIN),
					'err_invalid_email'			=> __('Invalid email address!', ET_DOMAIN),
					'err_invalid_username'		=> __('Invalid username!', ET_DOMAIN),
					'err_pass_not_matched'		=> __('Passwords does not match', ET_DOMAIN),
					'plupload_config'			=> array(
						'max_file_size' 		=> '3mb',
						'url' 					=> admin_url('admin-ajax.php'),
						'flash_swf_url' 		=> includes_url('js/plupload/plupload.flash.swf'),
						'silverlight_xap_url'	=> includes_url('js/plupload/plupload.silverlight.xap'),
					),
					'is_enable_feature'	=> et_is_enable_feature(),
					'loading' 		=> __('Loading', ET_DOMAIN),
					'txt_ok'		=> __('OK',ET_DOMAIN),
					'txt_cancel'	=> __('Cancel',ET_DOMAIN),
					'no_job_found'  => __('Oops! Sorry, no jobs found', ET_DOMAIN),
					'form_valid_msg'					=> __("Please fill out all required fields.", ET_DOMAIN),
					'anywhere' 		=> __('Anywhere', ET_DOMAIN),
					'view_map' 		=> __('View map', ET_DOMAIN),
					'page_template'	=> (is_page() ? get_page_template_slug() : ''),
					'is_single_job'	=> is_singular('job') ? 1 : null  

				),
			),
			'dashboard' => array(
				'object_name'	=> 'et_dashboard',
				'data'			=> array(
					'statuses' => array(
						'pending'	=> __('Pending', ET_DOMAIN),
						'archive'	=> __('Archived',ET_DOMAIN),
						'publish'	=> __('Active', ET_DOMAIN),
						'draft'		=> __('Draft', ET_DOMAIN),
						'reject'	=> __('Rejected', ET_DOMAIN)
					)
				)
			),
			'post_job' => array(
				'object_name'	=> 'et_post_job',
				'data'			=> array(
					'notice_step_not_allowed'	=> __('You need to finish the previous step first!', ET_DOMAIN),
					'button_submit'				=> __('SUBMIT',ET_DOMAIN),
					'button_continue'			=> __('CONTINUE',ET_DOMAIN),
					'reg_user_name'				=> __("Your username must not contain special characters", ET_DOMAIN),
					'error_msg'					=> __("Please fill out all required fields.", ET_DOMAIN),
					'log_seeker'				=> __("You need an employer account to post a job.", ET_DOMAIN)		
				)
			),
			'index' => array(
				'object_name' => 'et_index',
				'data' => array(
					'routerRootIndex' 	=> $root_url,
				)
			),
			'js-editor' => array(
				'object_name'	=>	'et_editor',
				'data' => array (
					'jsURL' 					=> TEMPLATEURL.'/js/', 
					'skin' 						=> 'silver',
					'onchange_callback'			=> 'tiny_job_desc_onchange_callback',
					'je_plugins'				=> apply_filters( 'je_editor_plugins', "spellchecker,paste,etHeading,etLink,autolink,inlinepopups,wordcount" ),
					'theme_advanced_buttons1'	=> apply_filters( 'je_editor_theme_advanced_buttons1', "bold,|,italic,|,et_heading,|,etlink,|,numlist,|, bullist,|,spellchecker" ),
					'theme_advanced_buttons2'	=> apply_filters( 'je_editor_theme_advanced_buttons2', "" ),
					'theme_advanced_buttons3'	=> apply_filters( 'je_editor_theme_advanced_buttons3', "" ),
					'theme_advanced_buttons3'	=> apply_filters( 'je_editor_theme_advanced_buttons4', "" )
				)
			),
			'single_job' => array(
				'object_name' => 'et_single_job',
				'data' => array(
					'upload_file_notice'	=>	__("You can only attach up to ",ET_DOMAIN),
					'info_job_statuses'	=> array(
						'pending'	=> __('THIS JOB IS PENDING. YOU CAN APPROVE OR REJECT IT.',ET_DOMAIN),
						'pending2'	=> __('THIS JOB IS PENDING.',ET_DOMAIN),
						'archive'	=> __('THIS JOB IS ARCHIVED.',ET_DOMAIN),
						'draft'		=> __('THIS IS A DRAFT.',ET_DOMAIN),
						'reject'	=> __('THIS JOB IS REJECTED.',ET_DOMAIN)
					),
				)
			)
		));
	}

	/**
	 * 
	 */
	public function custom_style(){
		$option = new ET_GeneralOptions();
		$style 	= $option->get_custom_style();
		echo "<style type='text/css'> \n". $style  . "\n</style>";
	}

	/**
	 * Initialize the theme
	 *
	 * @since 1.0
	 */
	public function init(){
		
		$this->create_roles();

		et_register_user_field('location',array(
			'title' 		=> __('Location' , ET_DOMAIN),
			'description' 	=> '',
			'type' 			=> 'text',
			'input_type' 	=> 'text',
			'roles' 			=> array('company'),
			'display_profile' => true,
			));
		et_register_user_field('user_logo',array(
			'title' 		=> __('Company logo' , ET_DOMAIN),
			'description' 	=> '',
			'type' 			=> 'array',
			'input_type' 	=> 'text',
			'roles' 			=> array('company'),
			'display_profile' => false,
			));
		et_register_user_field('recent_job_location',array(
			'title' 		=> __('Recent Job Location ' , ET_DOMAIN),
			'description' 	=> '',
			'type' 			=> 'array',
			'input_type' 	=> 'text',
			'roles' 		=> array('company'),
			'display_profile' => false,
			));

		et_register_user_field('apply_method',array(
			'title' 		=> __('Apply method provide ' , ET_DOMAIN),
			'description' 	=> '',
			'type' 			=> 'text',
			'input_type' 	=> 'text',
			'roles' 		=> array('company'),
			'display_profile' => false,
			));

		et_register_user_field('apply_email',array(
			'title' 		=> __('Email receive application' , ET_DOMAIN),
			'description' 	=> '',
			'type' 			=> 'text',
			'input_type' 	=> 'text',
			'roles' 		=> array('company'),
			'display_profile' => false,
			));
		et_register_user_field('applicant_detail',array(
			'title' 		=> __('Job applicant detail' , ET_DOMAIN),
			'description' 	=> '',
			'type' 			=> 'text',
			'input_type' 	=> 'text',
			'roles' 		=> array('company'),
			'display_profile' => false,
			));

		/** ==============================================
		 *  Payment plans fields
		 * 	============================================== */
		et_register_post_field('price', array(
				'title' => __('Price', ET_DOMAIN),
				'description' => '',
				'type' => 'decimal',
				'post_type' => array('payment_plan')
			));
		et_register_post_field('duration', array(
				'title' => __('Duration', ET_DOMAIN),
				'description' => '',
				'type' => 'int',
				'post_type' => array('payment_plan')
			));
		et_register_post_field('featured', array(
				'title' => __('Featured', ET_DOMAIN),
				'description' => '',
				'type' => 'int',
				'post_type' => array('payment_plan','job')
			));
		/** ==============================================
		 *  Job
		 * 	============================================== */
		et_register_post_field('location', array(
				'title' => __('Location', ET_DOMAIN),
				'description' => 'short adress',
				'type' => 'string',
				'post_type' => array('job')
			));
		et_register_post_field('full_location', array(
				'title' => __('Full Location', ET_DOMAIN),
				'description' => 'Job full address',
				'type' => 'string',
				'post_type' => array('job')
			));
		et_register_post_field('location_lat', array(
				'title' => __('Latitude', ET_DOMAIN),
				'description' => '',
				'type' => 'string',
				'post_type' => array('job')
			));
		et_register_post_field('location_lng', array(
				'title' => __('Longitude', ET_DOMAIN),
				'description' => '',
				'type' => 'string',
				'post_type' => array('job')
			));
		et_register_post_field('job_package', array(
				'title' => __('Payment Plan', ET_DOMAIN),
				'description' => '',
				'type' => 'int',
				'post_type' => array('job')
			));
		et_register_post_field('job_paid', array(
				'title' => __('Job Paid', ET_DOMAIN),
				'description' => '',
				'type' => 'bool',
				'post_type' => array('job')
			));
		et_register_post_field('job_order', array(
				'title' => __('Job Order', ET_DOMAIN),
				'description' => '',
				'type' => 'bool',
				'post_type' => array('job')
		));
		// job apply type : ishowtoapply or isapplywithprofile
		et_register_post_field('apply_method', array(
				'title' => __('Job Apply Method', ET_DOMAIN),
				'description' => '',
				'type' => 'string',
				'post_type' => array('job')
		));

		et_register_post_field('apply_email', array(
				'title' => __('Job Apply To Email', ET_DOMAIN),
				'description' => '',
				'type' => 'email',
				'post_type' => array('job')
		));
		// applicant details 
		et_register_post_field('applicant_detail', array(
				'title' => __('Job Apply Details', ET_DOMAIN),
				'description' => '',
				'type' => 'string',
				'post_type' => array('job')
		));

		// applocation post field
		et_register_post_field('emp_email', array(
				'title' => __('Employee email', ET_DOMAIN),
				'description' => '',
				'type' => 'email',
				'post_type' => array('application')
		));

		et_register_post_field('emp_name', array(
				'title' => __('Employee name', ET_DOMAIN),
				'description' => '',
				'type' => 'string',
				'post_type' => array('application')
		));

		et_register_post_field('company_id', array(
			'title' => __('Company id', ET_DOMAIN),
			'description' => 'ID of company who application send to',
			'type' => 'string',
			'post_type' => array('application')
		));


		// add custom scripts and style
		// add_action('template_redirect', array($this, 'custom_scripts'));

		// register a post status: Reject
		register_post_status( 'reject', array(
			'label' 	=> __('Reject', ET_DOMAIN),
			'private' 	=> true,
			'exclude_from_search' => false,
			'show_in_admin_all_list' => true,
			'show_in_admin_status_list' => true,
			'label_count' 	=> _n_noop( 'Reject <span class="count">(%s)</span>', 'Reject <span class="count">(%s)</span>' ),
			) );
		register_post_status( 'archive', array(
			'label' 	=> __('Archive', ET_DOMAIN),
			'private' 	=> true,
			'exclude_from_search' => false,
			'show_in_admin_all_list' => true,
			'show_in_admin_status_list' => true,
			'label_count' 	=> _n_noop( 'Archive <span class="count">(%s)</span>', 'Archive <span class="count">(%s)</span>' ),
			) );

		et_register_post_type_count_views (array('job'), array('anonym', 'subscriber'));

		// override wordpress rewrite rules
		$rules = get_option( 'rewrite_rules' );

		if ( ! isset( $rules['company/([^/]+)/?$'] ) ) {

			global $wp_rewrite;
			add_rewrite_rule($this->company_url.'/([^/]+)/?$','index.php?author_name=$matches[1]','top');
			add_rewrite_rule($this->company_url.'/([^/]+)/page/?([0-9]{1,})/?$','index.php?author_name=$matches[1]&paged=$matches[2]','top');

			// find 
			$page = et_get_page_template('page-post-a-job');
			if ( !empty($page) )
				add_rewrite_rule($page->post_name . '/([0-9]{1,})$', 'index.php?page_id=' . $page->ID . '&job_id=$matches[1]', 'top');
			// find 
			$page = et_get_page_template('page-process-payment');
			if ( empty($page) )  {
				et_get_page_link ('process-payment');
				$page = et_get_page_template('page-process-payment');
			}
				add_rewrite_rule( $page->post_name . '/([a-zA-Z_]{1,})$', 'index.php?page_id=' . $page->ID . '&paymentType=$matches[1]', 'top');
		
			
			$wp_rewrite->flush_rules();
		}
		

		/**
		 * Declare global menus
		 */
		global $et_admin_page;
		$et_admin_page = new JE_AdminMenu();
		$menus = array(
			'ET_MenuOverview',
			'ET_MenuSettings',
			'ET_MenuPayment',
			'ET_MenuCompanies',
			'ET_MenuWizard'
			);
		foreach ($menus as $menu) {
			$et_admin_page->register_sections($menu);
		}
		do_action('et_admin_menu');

		// $flush	=	get_option ('et_flush_rewrite', false );
		// if(!$flush) {
		// 	flush_rewrite_rules(  );
		// 	update_option('et_flush_rewrite', 1 );
		// }
	}

	/**
	 * 
	 */
	public function authorize_page(){
		if ( is_page_template('page-dashboard.php') || is_page_template('page-profile.php') || is_page_template('page-password.php')){
			if( is_user_logged_in() && !(current_user_can('company') || current_user_can('manage_options') || current_user_can('editor')) ) {
				wp_redirect( home_url( ) );
			}
			if ( !current_user_can('company') && !current_user_can('manage_options')) {
				include TEMPLATEPATH . '/require_login.php';
				exit;
			}
		}
	}

	/**
	 * create custom roles for Job Engine
	 * 
	 * @since 1.0
	 */
	private function create_roles(){		
		// add company role
		add_role('company', __( 'Company', ET_DOMAIN ), array(
			'read'			=> true,
			'delete_posts'	=> true,
			'edit_posts'	=> true,
			'upload_files'	=> true
			));
		$role = get_role('administrator');
		$role->add_cap('read_private_jobs');
		$role->add_cap('read_other_private_jobs');
		$role->add_cap('publish_jobs');
		$role->add_cap('edit_jobs');
		$role->add_cap('edit_others_jobs');
		$role->add_cap('delete_jobs');
		$role->add_cap('delete_other_jobs');
		$role->add_cap('read_private_jobs');
		$role->add_cap('edit_job');
		$role->add_cap('delete_job');
		$role->add_cap('read_job');

		$role = get_role('company');
		$role->add_cap( 'edit_job' );
		$role->add_cap( 'archive_job' );
		$role->add_cap( 'read_job' );
		$role->add_cap( 'read_private_jobs' );
	}

	/**
	 * Modify post state in tables list
	 * @since 1.0
	 */
	public function custom_post_state($states){
		global $post;
		if ( $post->post_status == 'reject' )
			$states[] = __('Reject', ET_DOMAIN);
		if ( $post->post_status == 'archive' )
			$states[] = __('Archive', ET_DOMAIN);
		return $states;
	}

	public function create_rewrite_rules($rewrite){		
		// customize rewrite rule 
		add_rewrite_rule('^'.$this->company_url.'/([^/]+)/page/?([0-9]{1,})/?$','index.php?author_name=$matches[1]&paged=$matches[2]','top');

		// 
		global $post;
		$posts = get_posts(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'page-post-a-job.php'
			));

		foreach ($posts as $post) {
			setup_postdata($post);
			add_rewrite_rule('^'.$post->post_name.'/([^/]+)/?$','index.php?page_id=' . $post->ID . '&job_id=$matches[2]','top');
		}
	}

	/**
	 * Trigger this method after theme has been set up
	 * @since 1.0
	 */
	public function after_setup_theme(){
		parent::after_setup_theme();
		add_theme_support('post-thumbnails');
		add_image_size( 'company-logo', 200, 9999 ); // default logo size in every place other than thumbnail
		add_image_size( 'small_thumb', 28, 28, true);
		// add the custom image sizes above into WP media uploader
		add_filter('image_size_names_choose', array($this, 'et_image_sizes') );
	}

	/**
	 * Trigger this method after running theme for the 1st time
	 * @since 1.0
	 */
	public function setup_theme(){
		// setting up color customization
		et_apply_customization(array());

		$option = new ET_GeneralOptions();
		$option->set_customization(array(
			'background' 		=> '#fff',
			'header' 			=> '#333',
			'text' 				=> '#333',
			'heading' 			=> '#333',
			'action' 			=> '#F28C79',
			'font-text' 		=> 'Arial, san-serif',
			'font-text-weight' 	=> 'normal',
			'font-text-style' 	=> 'normal',
			'font-text-size' 	=> '14px',
			'font-heading' 		=> 'Arial, san-serif',
			'font-heading-weight' => 'normal',
			'font-heading-size' => '12px',
			'font-links' 		=> 'Arial, san-serif',
			'font-links-weight' => 'normal',
			'font-links-style' 	=> 'normal',
			'font-links-size' 	=> '12px',
			));

		// remove sidebar
		$sidebars = get_option('sidebars_widgets');
		foreach ((array)$sidebars as $name => $widget) {
			if ( $name != 'wp_inactive_widgets' ){
				$sidebars[$name] = array();
			}
		}
		update_option('sidebars_widgets', $sidebars);
	}

	public function notice_after_installing_theme(){
		if ( isset($this->wizard_status ) && !$this->wizard_status ){
		?>
			<style type="text/css">
			.et-updated{
				background-color: lightYellow;
				border: 1px solid #E6DB55;
				border-radius: 3px;
				webkit-border-radius: 3px;
				moz-border-radius: 3px;
				margin: 20px 15px 0 0;
				padding: 0 10px;
			}
			</style>
			<div id="notice_wizard" class="et-updated">
				<p>
					<?php printf( __("You have just installed JobEngine, we recommend you follow through our <a href='%s'>setup wizard</a> to set up the basic configuration for your website!", ET_DOMAIN), admin_url('admin.php?page=et-wizard'))?>
				</p>
			</div>
		<?php
		}
	}

	// add the custom image sizes above into WP media uploader
	// apply this function into filter image_size_names_choose
	public function et_image_sizes($sizes) {
		$addsizes = array(
			"company-logo"	=> __( 'Company logo with default size', ET_DOMAIN),
			"small_thumb"	=> __('Small thumbnail for job list items',ET_DOMAIN)
		);
		$newsizes = array_merge($sizes, $addsizes);
		return $newsizes;
	}

	public function add_query_vars($query_vars){
		array_push($query_vars, 'location');
		array_push($query_vars, 'status');
		array_push($query_vars, 'job_id');
		array_push($query_vars, 'company');
		array_push($query_vars, 'paymentType');
		return $query_vars;
	}

	/**
	 * Map capabilities
	 *
	 * @since 1.0
	 */
	public function map_meta_cap($caps, $cap, $user_id, $args){
		global $current_user;

		if ( $cap == 'read_job' || $cap == 'read_post' ){
			$post 		= get_post( $args[0] );
			$post_type 	= get_post_type_object( $post->post_type );
			$caps 		= array();

			switch ($cap) {
				case 'read_job':
					if ( isset($post->post_status) && ($post->post_status == 'reject' || $post->post_status == 'archive') && ($post->post_author == $user_id ) )
						$caps[] = 'read_job';
					else {
						$caps[] = 'read_other_private_jobs';
					}
					break;

				default:
					break;
			}
		}
		return $caps;
	}

	/**
	 * 
	 */
	public function custom_author_link($link){
		global $wp_rewrite;
		if ( !$wp_rewrite->using_permalinks() ){
			//$link = preg_replace('/\?author=/','\?company=', $link);
		} else {
			$link = preg_replace('/\/author\/([^\/]+\/*)$/', '/'.$this->company_url.'/$1', $link);
		}
		return $link;
	}

	/**
	 *
	 */
	public function posts_where($where){
		global $et_after_time;

		if ( empty($et_after_time) && !is_numeric($et_after_time) ) return $where;

		$within 		= empty($et_after_time) ? 0 : $et_after_time;
		$et_after_time 	= 0;

		$now 	= strtotime('now');
		$range 	= date('Y-m-d H:i:s', $now - $within);

		// if within is set as 0, count all post in database
		$range_sql = $within == 0 ? "" : "AND post_date >= '{$range}'";

		$where .= $range_sql;
		return $where;
	}

	public function filter_orderby($order){
		global $wpdb;
		return "{$wpdb->postmeta}.meta_value DESC, {$wpdb->posts}.post_date DESC";
	}

	public function remove_filter_orderby(){
		remove_filter('posts_orderby', array(&$this, 'filter_orderby'));
	}

	/**
	 * Automatically set meta feature is 0 if job doesn't have
	 */
	public function save_post($post_id){
		if ( isset($_POST['post_type']) && 'job' != $_POST['post_type'] )
			return;

		$feature = get_post_meta($post_id, 'et_featured', true);

		if ( $feature === '' )
			update_post_meta($post_id,'et_featured', '0');
	}

	/**
	 * Customize the main query of wordpress to fix the need
	 *
	 * @since 1.0
	 */
	public function pre_get_posts($query){
		// modified query var 'location'
		global $et_global, $current_user;
		if ( !empty($query->query_vars['location'])){
			// if ( !empty($query->query_vars['meta_query']) && is_array($query->query_vars['meta_query']) ){
			// 	foreach ($query->query_vars as $key => $var) {
			// 		if ( isset($var['key']) && $var['key'] == $et_global['db_prefix'] . 'location' ){
			// 			unset( $query->query_vars[$key] );
			// 			break;
			// 		}
			// 	}
			// 	$query->query_vars['meta_query'][] = array(
			// 		'key' => $et_global['db_prefix'] . 'full_location',
			// 		'value' => $query->query_vars['location'],
			// 		'compare' => 'LIKE'
			// 		);
			// }else {
			// 	$query->query_vars['meta_query'] = array( array(
			// 		'key' => $et_global['db_prefix'] . 'full_location',
			// 		'value' => $query->query_vars['location'],
			// 		'compare' => 'LIKE'
			// 		) );
			// }
			set_query_var('location', $query->query_vars['location']);
			add_filter( 'posts_join', array($this, 'db_location_join') );
			add_filter( 'posts_where', array($this, 'db_location_where') );
		}else {
			remove_filter( 'posts_join', array($this, 'db_location_join') );
			remove_filter( 'posts_where', array($this, 'db_location_where') );
		}

		if ( !empty($query->query_vars['status']) && current_user_can('manage_options') ){
			$query->set('post_status', $query->query_vars['status']);
		}

		// these below code is for modifying main query
		if ( is_admin() ) return $query;
		if ( !$query->is_main_query() ) return $query;

		if ( is_feed()){
			// sorting by featured
			add_filter('posts_orderby', array(&$this, 'filter_orderby'));
			$query->set('meta_key', $et_global['db_prefix']. 'featured');
			$query->set('orderby', 'date');
			$query->set('order', 'DESC');
			// if post type isn't set, we set it job by default
			if (get_query_var('post_type') == '')
				$query->set('post_type', 'job');
		}

		// allow people view publish jobs in archive only
		if ( (is_home() || is_tax('job_type') || is_tax('job_category') || is_author() || is_post_type_archive('job') ) && ( empty($query->query_vars['post_status']) )){
			$query->set('post_status', array('publish'));
		}

		if(is_home() || is_tax('job_type') || is_tax('job_category') || is_author() || is_post_type_archive('job') ) {
			$query->set('post_type', 'job');
			//if ( et_is_enable_feature() ){
				add_filter('posts_orderby', array(&$this, 'filter_orderby'));
				$query->set('meta_key', $et_global['db_prefix']. 'featured');
				$query->set('orderby', 'date');
				$query->set('order', 'DESC');
			//}
			return $query;
		}

		if( is_tax('job_type') || is_tax('job_category') ) {

			if(is_tax('job_type')) {
				$query->query_vars['tax_query'] = array(
					array(
						'taxonomy' => 'job_type',
						'field' => 'slug',
						'terms' => get_queried_object()->slug
					)
				) ;    
				return $query;
			}
			if(is_tax('job_category')) {
				$query->query_vars['tax_query'] = array(
					array(
						'taxonomy' => 'job_category',
						'field' => 'slug',
						'terms' => get_queried_object()->slug
					)
				) ;    
				return $query;
			}
		}
		return $query;
	}

	public function db_location_join($join){
		global $wpdb, $wp_query;

		$join .= " INNER JOIN {$wpdb->postmeta} as etmeta ON {$wpdb->posts}.ID = etmeta.post_id AND etmeta.meta_key = 'et_location' ";
		$join .= " INNER JOIN {$wpdb->postmeta} as etmeta1 ON {$wpdb->posts}.ID = etmeta1.post_id AND etmeta1.meta_key = 'et_full_location' ";
		//echo $join;
		return $join;
	}

	public function db_location_where($where){
		global $wpdb, $wp_query;
		$loc = get_query_var('location');
		//if (empty($loc) || empty($wp_query->location)) return $where;

		//$loc = empty($loc) ? $wp_query['location'] : $loc;

		$where .= " AND (etmeta.meta_value LIKE '%{$loc}%' OR etmeta1.meta_value LIKE '%{$loc}%' OR etmeta.meta_value = '" . __('Anywhere', ET_DOMAIN) . "' ) ";
		return $where;
	}

	/**
	 *	customize adminbar
	*/
	public function customize_admin_bar_menu () {
		global $wp_admin_bar;

		$args = array(
		   "id" => 'job_engine_setting',
		   "title" => 'JobEngine Dashboard',
		   "href" => admin_url('admin.php?page=et-overview'),
		   "parent" => false,
		   "meta" => array('tabindex' => 20)
		);

		$wp_admin_bar->add_menu($args);		
		$childs	=	array (
			'overview' => 	array ('section' => 'et-overview', 'title' => __("Overview", ET_DOMAIN) ),
			'setting'  =>  	array ('section' => 'et-setting', 'title' => __("Settings", ET_DOMAIN) ),
			'payment'  => 	array ('section' => 'et-payments', 'title' => __("Payments", ET_DOMAIN) ),
			'company'  =>	array ('section' => 'et-companies', 'title' => __("Companies", ET_DOMAIN) )
		);
		$childs	=	apply_filters( 'et_admin_bar_menu', $childs );
		foreach ($childs as $key => $value) {

			$child	=	array(
			   "id" => 'job_engine_setting-'.$key,
			   "title" => $value['title'],
			   "href" => admin_url('admin.php?page='.$value['section'] ),
			   "parent" => 'job_engine_setting',
			   "meta" => array('tabindex' => 20)
			);

			$wp_admin_bar->add_menu($child);
		}	
	}
	/**
	 * count post views and store
	*/
	public function count_post_views () {
		et_count_post_views ();
	}

	/**
	 * Custom feed
	 */
	function custom_feed($for_comment){
		$rss_template = get_template_directory() . '/feed-rss2.php';
		if (get_query_var('post_type') == 'job' && file_exists($rss_template))
			load_template($rss_template);
		else 
			do_feed_rss2($for_comment);
	}

	/**
	 * Creates a nicely formatted and more specific title element text for output
	 * in head of document, based on current view.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function wp_title( $title, $sep ) {
		global $paged, $page;

		if ( is_feed() )
			return $title;

		// Add the site name.
		$title .= get_bloginfo( 'name' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title = "$title $sep $site_description";

		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 )
			$title = "$title $sep " . sprintf( __( 'Page %s', ET_DOMAIN ), max( $paged, $page ) );

		return $title;
	}

}
global $et_master;
$et_master = new ET_JobEngine();

/**
 * Return format text with a number
 * @since 1.0
 * @param $zero zero format
 * @param $single single format
 * @param $plural plural format
 * @param $number input number
 */
function et_number( $zero, $single, $plural, $number){
	if ( $number == 0 )
		return $zero;
	elseif($number == 1)
		return $single;
	else 
		return $plural;
}

/**
 * 
 */
function et_filter_orderby($order){
	global $wpdb;
	return "{$wpdb->postmeta}.meta_value DESC, {$wpdb->posts}.post_date DESC";
}

/**
 * Form template
 */
global $et_je_custom_fields;
$et_je_custom_fields = array();
function et_register_fields_template($name, $callback){
	global $et_je_custom_fields;
	$et_je_custom_fields['name'] = $callback;
}

function et_the_form($fields){
	foreach ( $fields as $name => $field ) {
		$field = wp_parse_args($field, array(
			'name' 		=> $name,
			'type' 		=> '',
			'title' 	=> '',
			'desc' 		=> '',
			'class' 	=> '',
			'id' 		=> '',
			'input_class' => '',
			'input_id' 	=> '',
			'options' 	=> '',
			'value' 	=> ''
			));
		switch ($field['type']) {
			case 'password':
			case 'text':
				echo 	'<div id="' . $field['id'] . '" class="form-item '. $field['class'] .'">
							<label>'  . $field['title'] . '</label>
							<div>
							<input type="'.$field['type'].'" name="' . $field['name'] . '" class="bg-default-input '.$field['input_class'].'" id="' . $field['input_id'] . '" value="'.$field['value'].'"/>
							</div>
						</div>';
				break;
			case 'textarea':
				echo 	'<div id="' . $field['id'] . '" class="form-item '. $field['class'] .'">
							<label>'  . $field['title'] . '</label>
							<div>
							<textarea type="'.$field['type'].'" name="' . $field['name'] . '" class="bg-default-input '.$field['input_class'].'" id="' . $field['input_id'] . '">'.$field['value'].'</textarea>
							</div>
						</div>';
				break;

			case 'editor':
				break;

			case 'image':
				if (empty($field['value'])) {
					$field['value']	= TEMPLATEURL . '/img/companies-profiles.jpg';
				}
				echo 	'<div id="' . $field['id'] . '" class="form-item field-' . $field['name'] . ' ' . $field['class'] . '">
							<label>' . $field['title'] . '</label>
							<span class="company-thumbs thumbnail" id="' . $field['name'] . '_thumbnail">
								<img src="' . $field['value'] . '">
							</span>
							<input type ="hidden" class="et_ajaxnonce" id="' . wp_create_nonce( $field['name'] . '_et_uploader') . '" />
							<div class="">
								<div class="input-file">
									<span class="btn-background border-radius button" id="' . $field['name'] . '_browse_button">
										' . __('Browse ...', ET_DOMAIN) . '
										<span class="icon" data-icon="o"></span>
									</span>
									<input id="' . $field['input_id'] . '" class="input-script ' . $field['input_class'] . '" name="' . $field['name'] . '" type="file" />
									<span class="filename"></span>
								</div>
							</div>
						</div>';
				break;

			case 'hidden' :
				echo ' <input type="hidden" id="'.$field['input_id'].'" name="'.$field['name'].'" value="'.$field['value'].'" /> ';

			default:
				global $et_je_custom_fields;
				if ( isset($et_je_custom_fields[$field['type']] ) && function_exists($et_je_custom_fields[$field['type']]) ) {
					call_user_func_array( $et_je_custom_fields[$field['type']] , array( $name, $field ) );
				}
				break;
		}
	}
}

/**
 * process uploaded image: save to upload_dir & create multiple sizes & generate metadata
 * @param  [type]  $file     [the $_FILES['data_name'] in request]
 * @param  [type]  $author   [ID of the author of this attachment]
 * @param  integer $parent=0 [ID of the parent post of this attachment]
 * @param  array [$mimes] [array of supported file extensions]
 * @return [int/WP_Error]	[attachment ID if successful, or WP_Error if upload failed]
 * @author anhcv
 */
function et_process_file_upload( $file, $author=0, $parent=0, $mimes=array() ){

	global $user_ID;
	$author = ( 0 == $author || !is_numeric($author) ) ? $user_ID : $author;

	if( isset($file['name']) && $file['size'] > 0){

		// setup the overrides
		$overrides['test_form']	= false;
		if( !empty($mimes) && is_array($mimes) ){
			$overrides['mimes']	= $mimes;
		}

		// this function also check the filetype & return errors if having any
		$uploaded_file	=	wp_handle_upload( $file, $overrides );

		//if there was an error quit early
		if ( isset( $uploaded_file['error'] )) {
			return new WP_Error( 'upload_error', $uploaded_file['error'] );
		}
		elseif(isset($uploaded_file['file'])) {

			// The wp_insert_attachment function needs the literal system path, which was passed back from wp_handle_upload
			$file_name_and_location = $uploaded_file['file'];

			// Generate a title for the image that'll be used in the media library
			$file_title_for_media_library = preg_replace('/\.[^.]+$/', '', basename($file['name']));

			$wp_upload_dir = wp_upload_dir();

			// Set up options array to add this file as an attachment
			$attachment = array(
				'guid'				=> $uploaded_file['url'],
				'post_mime_type'	=> $uploaded_file['type'],
				'post_title'		=> $file_title_for_media_library,
				'post_content'		=> '',
				'post_status'		=> 'inherit',
				'post_author'		=> $author
			);

			// Run the wp_insert_attachment function. This adds the file to the media library and generates the thumbnails. If you wanted to attch this image to a post, you could pass the post id as a third param and it'd magically happen.
			$attach_id = wp_insert_attachment( $attachment, $file_name_and_location, $parent );
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
			wp_update_attachment_metadata($attach_id,  $attach_data);
			return $attach_id;

		} else { // wp_handle_upload returned some kind of error. the return does contain error details, so you can use it here if you want.
			return new WP_Error( 'upload_error', __( 'There was a problem with your upload.', ET_DOMAIN ) );
		}
	}
	else { // No file was passed
		return new WP_Error( 'upload_error', __( 'Where is the file?', ET_DOMAIN ) );
	}
}
/**
 * handle file upload prefilter to tracking error
*/
//remove_filter( 'wp_handle_upload_prefilter','check_upload_size' );
add_filter ( 'wp_handle_upload_prefilter', 'et_handle_upload_prefilter', 9);
function et_handle_upload_prefilter ($file) {
	if(!is_multisite()) return $file;

	if ( get_site_option( 'upload_space_check_disabled' ) )
		return $file;

	if ( $file['error'] != '0' ) // there's already an error
		return $file;

	if ( defined( 'WP_IMPORTING' ) )
		return $file;

	$space_allowed = 1048576 * get_space_allowed();
	$space_used = get_dirsize( BLOGUPLOADDIR );
	$space_left = $space_allowed - $space_used;
	$file_size = filesize( $file['tmp_name'] );
	if ( $space_left < $file_size )
		$file['error'] = sprintf( __( 'Not enough space to upload. %1$s KB needed.', ET_DOMAIN ), number_format( ($file_size - $space_left) /1024 ) );
	if ( $file_size > ( 1024 * get_site_option( 'fileupload_maxk', 1500 ) ) )
		$file['error'] = sprintf(__('This file is too big. Files must be less than %1$s KB in size.', ET_DOMAIN), get_site_option( 'fileupload_maxk', 1500 ) );
	if ( upload_is_user_over_quota( false ) ) {
		$file['error'] = __( 'You have used your space quota. Please delete files before uploading.',ET_DOMAIN );
	}


	// if ( $file['error'] != '0' && !isset($_POST['html-upload']) )
	// 	wp_die( $file['error'] . ' <a href="javascript:history.go(-1)">' . __( 'Back' ) . '</a>' );
	return $file;
}

/**
 * Return all sizes of an attachment
 * @param 	$attachment_id
 * @return 	an array with [key] as the size name & [value] is an array of image data in that size
 *             e.g:
 *             array(
 *             	'thumbnail'	=> array(
 *             		'src'	=> [url],
 *             		'width'	=> [width],
 *             		'height'=> [height]
 *             	)
 *             )
 * @since 1.0
 */
function et_get_attachment_data($attach_id){

	// if invalid input, return false
	if (empty($attach_id) || !is_numeric($attach_id)) return false;

	$data		= array(
		'attach_id'	=> $attach_id
		);
	$all_sizes	= get_intermediate_image_sizes();

	foreach ($all_sizes as $size) {
		$data[$size]	= wp_get_attachment_image_src( $attach_id, $size );
	}
	return $data;
}

/**
 * Render job categories for desktop
 */
function et_template_front_category($categories = false, $parent = 0 , $args = array()){
	global $wp_query;
	$cat	=	get_query_var('job_category');
	$queried_cats	=	explode(',', $cat);

	$query_obj	= ($wp_query->is_tax) ? $wp_query->queried_object : false;

	if ( empty($categories) )
		$categories = et_get_job_categories_in_order();// et_get_job_categories();
	/**
	 * apply filter to filter parent cat expand or collapse
	*/

	$expand	=	apply_filters( 'je_is_expand_parent_categories_list', 1 );
	if ( !empty($categories) ){ ?>

		<ul data-tax="job_category" class="job-filter <?php echo $parent == 0 ? 'tax-filter category-lists filter-jobcat' : '' ?> filter-joblist" style="<?php echo ($parent != 0 && !$expand) ? 'display: none' :''   ?>">
		<?php foreach ($categories as $cat) { 
			if( $args['hide_empty'] && $cat->count <= 0 ) continue;
			if ( $cat->parent == $parent ) { ?>
				<li class="cat-item cat-<?php echo $cat->term_id ?> cat-<?php echo $cat->slug ?>">
					<a data="<?php echo $cat->slug ?>" href="<?php echo get_term_link($cat, 'job_category') ?>" 
						class="<?php if( ($query_obj && $query_obj->term_id == $cat->term_id) || in_array($cat->slug, $queried_cats) ) echo 'active'; ?>">
						<div class="name"><?php echo $cat->name ?> </div>
						<?php if(!$args['hide_jobcount']) { ?>
							<span class="count"><?php echo $cat->count ?></span>
						<?php } ?>
					</a>
					<!-- <span href="" class="sym-multi icon" data-icon="_"></span> -->
					<?php 
					// check if this category has children or not
					$has_children = false; 
					foreach ($categories as $child) {
						if ( $child->parent == $cat->term_id ){ 
							$has_children = true;
							break;
						} // end if
					} // end foreach
					if ( $has_children ){ ?>
						<div class="<?php if($expand) echo 'sym-multi arrow sym-multi-expand'; else echo 'sym-multi arrow'; ?>" ></div>
						<?php et_template_front_category($categories, $cat->term_id, $args); ?>
					<?php } // end if  ?>
				</li>

		<?php
			} // end if parent == cat->parent
		} // end foreach
		?>
		</ul>
		<?php
	} // end if

    ?>
    
    <?php
}

/**
 * Render job categories for mobile
 */
function et_template_front_category_mobile($categories = false, $parent = 0){
	if ( empty($categories) )
		$categories = et_get_job_categories();
	if ( !empty($categories) ){ 
		foreach ($categories as $cat) {
			if ( $cat->parent == $parent ) {
				echo '<li>';
				echo '<a data="'.$cat->slug.'" class="ui-list">'.$cat->name.'</a>';
				echo '</li>';
				$has_children = false;
				foreach ($categories as $child) {
					if ( $child->parent == $cat->term_id ){
						$has_children = true; break;
					}
				}		
				if ( $has_children ){
					echo '<li><ul>';
					et_template_front_category_mobile($categories, $cat->term_id);
					echo '</ul></li>';
				}
			}
		}
	} 
}

/**
 * Print the modal resgiter 
 * @since 1.0
 */
function et_template_modal_register(){
	?>
	<div class="modal-job modal-register" id="modal-register">
		<div class="edit-job-inner">
		<div class="title font-quicksand"><?php _e('Register a company account', ET_DOMAIN) ?></div>
		<form class="modal-form" id="register">
			<div class="content form-content">		  
				<div class="form-item">
					<div class="label">
						<h6><?php _e('Username', ET_DOMAIN) ?></h6>
					</div>
					<div class="">
						<input class="bg-default-input" type="text" name="reg_name" id="reg_name" />
					</div>
				</div>		
				<div class="form-item">
					<div class="label">
						<h6><?php _e('Email', ET_DOMAIN)?></h6>
					</div>
					<div class="">
						<input class="bg-default-input" type="text" name="reg_email" id="reg_email" />
					</div>
				</div>
				<div class="form-item">
					<div class="label">
						<h6><?php  _e("Password", ET_DOMAIN) ?></h6>
					</div>
					<div class="">
						<input class="bg-default-input" type="password" name="reg_pass" id="reg_pass" />
					</div>
				</div>
				<div class="form-item">
					<div class="label">
						<h6><?php _e('Retype Password', ET_DOMAIN) ?></h6>
					</div>
					<div class="">
						<input class="bg-default-input" type="password" name="reg_pass_again" id="reg_pass_again" />
					</div>
				</div>
			</div>
			<div class="footer">
				<div class="button">  
					<input type="submit" class="bg-btn-action border-radius" value="<?php _e('REGISTER', ET_DOMAIN) ?>" name="">
				</div>     
			</div>
		</form>
		</div>
		<div class="modal-close"></div>
	</div>
	<?php
}

/**
 * Print the forgot password's modal template
 * @since 1.0
 */
function et_template_modal_forgot_pass(){
	?>
	<div class="modal-job" id="modal-forgot-pass">
		<div class="edit-job-inner">
		<div class="title font-quicksand"><?php _e('Forgot your password?', ET_DOMAIN) ?></div>
		<form class="modal-form" id="forgot_pass" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post'); ?>">
			<div class="form-content content">		  
				<div class="form-item">
					<div class="label">
						<h6><?php _e('Enter your email address', ET_DOMAIN) ?></h6>
					</div>
					<div class="">
						<input class="bg-default-input" name="forgot_email" id="forgot_email" />
					</div>
				</div>						
			</div>
			<div class="footer">
				<div class="button">  
					<input type="submit" class="bg-btn-action border-radius" value="<?php _e('Get Password', ET_DOMAIN) ?>" name="">
				</div>     
			</div>
		</form>
		</div>
		<div class="modal-close"></div>
	</div>
	<?php
}

/**
 * Print the reject job modal template
 * @since 1.0
 */
function et_template_modal_reject(){
	?>
	<div class="modal-job" id="modal-reject-job">
		<div class="edit-job-inner">
			<div class="title-white">
				<h5 id="job_title"></h5>
				<span id="company_name"></span>
			</div>
			<form class="modal-form">
				<div class="content">
					<div class="toggle-content login clearfix">
						<div class="form">
							<div class="form-item no-padding">
								<div class="label">
									<div class="f-right"><strong><?php _e('Send a message to this company', ET_DOMAIN);?></strong></div>
									<h6><?php _e('Why do you reject this job?', ET_DOMAIN);?></h6>
								</div>
								<div class="">
									<textarea name="reason" class="bg-default-input reject-reason mini"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="footer font-quicksand">
					<div class="f-right cancel"><a class="cancel-modal" href="#"><?php _e('Cancel', ET_DOMAIN) ?> <span class="icon" data-icon="D"></span></a></div>
					<div class="modal-btn-reject">
						<input type="button" id="btn-reject" class="bg-btn-action border-radius" value="<?php _e('Reject', ET_DOMAIN);?>" name="reject" />
					</div>					
				</div>
			</form>
		</div>
		<div class="modal-close"></div>
	</div>
	<?php
}

function et_prevent_user_access_wp_admin ()  {
	if(!current_user_can('manage_options')) {
		wp_redirect(home_url());
		exit;
	}
}

/**
 * detect browser, if that is IE 7 or below, notice to visitor
 */
function et_block_ie_redirect(){
	et_block_ie('7.0', 'page-unsupported.php');
}
add_action('template_redirect', 'et_block_ie_redirect');

// add_filter ('cron_request' , 'je_cron_request');
// function je_cron_request ($cron_request) {
// 	$cron_request['sslverify']	= false;
// 	$cron_request['timeout']	=	0.1;
// 	return $cron_request;
// }

add_action('wp_head' , 'je_open_graph_social');
function je_open_graph_social () {
	if(is_single() ) {
		global $post;
	?>	
		<meta property="og:url" content="<?php echo get_permalink($post->ID); ?>"/>
		<meta property="og:title" content="<?php echo get_the_title($post->ID); ?>"/>
		<meta property="og:description" content="<?php echo strip_tags(apply_filters('the_excerpt', $post->post_content)); ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:image" content="<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>" />
	<?php 
	}
}


// add_filter('get_the_date', 'je_filter_date');
// function je_filter_date($date) {
// 	return strftime( '%V,%G,%Y' , strtotime($date) )	;
// }setlocale(LC_ALL, 'nl_NL');
