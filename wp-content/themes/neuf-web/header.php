<html>
<head>
<title><?php wp_title(); ?> <?php bloginfo('name');?></title>

<?php
wp_head();
?>
<link rel="stylesheet" href="<?= bloginfo('stylesheet_directory'); ?>/stylesheets/main.css" />

</head>
<body <?php body_class(); ?>>

<div id="root">
	<header>
		<h1>
			<a href="<?php bloginfo('url'); ?>">&nbsp;</a>
		</h1>
		
		<section id="meta">
			<form>
				<input name="username" type="text" placeholder="BRUKERNAVN" />
				<input name="password" type="password" placeholder="PASSORD" />
				
				<input name="search" type="text" placeholder="SØK" />
			</form>
		</section>

		<nav id="menu"><?php wp_nav_menu(); ?></nav>
	</header>

