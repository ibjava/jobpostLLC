<?php

add_filter('et_registered_styles', 'je_child_register_styles', 20);
function je_child_register_styles($styles){
	$styles['child_style'] = array(
		'src'	=> get_bloginfo('stylesheet_directory') . '/css/style.css',
		'deps'	=> array('stylesheet','custom','customization')
		);
	return $styles;
}