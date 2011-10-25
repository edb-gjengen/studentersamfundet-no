<html>
<head>
<title><?php wp_title(); ?> <?php bloginfo('name');?></title>

<?php
wp_head();
?>

</head>
<body <?php body_class(); ?>>

<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>

<div id="menu"><?php wp_nav_menu(); ?></div>

