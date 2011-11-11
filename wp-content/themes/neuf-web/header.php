<!doctype html>
<html lang="no">
<head>
	<title><?php bloginfo('blog_title'); ?></title>
	
	<meta charset="utf-8" />

	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri(); ?>/stylesheets/main.css" />

	<?php wp_head(); ?>
</head>

<body>

<header id="branding">
	<h1 id="site-title"><a href="<?php bloginfo('url') ?>/" title="<?php bloginfo('name') ?>" rel="home"><?php bloginfo('name') ?></a></h1>

	<section id="meta-header">
		<form>
			<input name="username" type="text" placeholder="BRUKERNAVN" />
			<input name="password" type="password" placeholder="PASSORD" />

		</form>
	</section> <!-- #meta-header -->

	<section id="skip-link">
		<a href="#content" title="<?php _e('Skip navigation to the content', 'thematic'); ?>"><?php _e('Skip to content', 'thematic'); ?></a>
	</section><!-- #skip-link -->
</header> <!-- #branding -->
