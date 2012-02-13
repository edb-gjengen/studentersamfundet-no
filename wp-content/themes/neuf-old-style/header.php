<?php echo '<?xml version="1.0" encoding="utf-8">' . "\n"; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="no" lang="no">
	<head>
	<meta charset="utf-8" />
		<meta name="robots" content="index,follow" />

	<?php neuf_doctitle(); ?>

		<link rel="icon" type="image/png" href="favicon.png" />

<?php // @todo Make sure our feeds work properly :) ?>
		<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('url'); ?>/rss/nyheter_feed.php" title="Det Norske Studentersamfund (nyheter)" />
		<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('url'); ?>/rss/program_feed.php" title="Det Norske Studentersamfund (program)" />
		<link href="<?php bloginfo( 'stylesheet_url' ); ?>" rel="stylesheet" type="text/css" />
		<link href='http://fonts.googleapis.com/css?family=Arvo:700,400italic' rel='stylesheet' type='text/css'>

		<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/nb_NO" type="text/javascript"></script><script type="text/javascript">FB.init("9f0d4e4f84f00af0249f45aa48fb0efb");</script>

	<?php wp_head(); ?>
	</head>

	<body <?php neuf_body_class(); ?>>
		<div id="header" class="container_12">

				<div id="access"><a href="#content">Gå direkte til innholdet</a></div>

				<div class="site-title grid_6">
					<span><a href="<?php bloginfo('url') ?>/" title="<?php bloginfo('name') ?>" rel="home"><?php bloginfo('name') ?></a></span>
				</div>

<?php get_template_part( 'menu' ); ?>

		</div> <!--  header -->

