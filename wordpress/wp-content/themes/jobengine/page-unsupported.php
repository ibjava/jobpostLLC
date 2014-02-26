<?php 
/**
 * Template Name: Unsupported Browsers
 */
?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<link rel="stylesheet" href="<?php echo TEMPLATEURL ?>/css/fonts/font-face.css">
	<link rel="stylesheet" href="<?php echo TEMPLATEURL ?>/css/unsupported.css">
</head>
<body <?php body_class('browser-unsupported') ?>>
	<?php
	the_post();
	echo get_the_content();
	?>
</body>
</html>