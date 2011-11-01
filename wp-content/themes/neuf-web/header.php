<!DOCTYPE html>
<html>
<head>
	<title><?php wp_title(); ?> <?php bloginfo('name');?></title>
	<?php wp_head();?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" media="all" href="<?= bloginfo('stylesheet_directory'); ?>/stylesheets/main.css" />
</head>
<body <?php body_class(); ?>>

<div id="root">
	<header>
		<h<?php echo is_home() ? "1" : "2"; ?>>
			<a href="<?php bloginfo('url'); ?>">&nbsp;</a>
		</h<?php echo is_home() ? "1" : "2"; ?>>
		
		<section id="meta">
			<form>
				<input name="username" type="text" placeholder="BRUKERNAVN" />
				<input name="password" type="password" placeholder="PASSORD" />
				
				<input name="search" type="text" placeholder="SØK" />
			</form>
		</section>

		<nav id="menu"><?php wp_nav_menu(); ?></nav>
	</header>

