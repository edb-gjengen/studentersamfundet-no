<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="no" lang="no">
	<head>
		<meta charset="utf-8" />
		<meta name="robots" content="index,follow" />

		<?php neuf_doctitle(); ?>

		<link rel="icon" type="image/png" href="/favicon.png" />
		<?php // @todo Make sure our feeds work properly :) ?>
		<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('url'); ?>/feed/" title="Det Norske Studentersamfund (nyheter)" />
		<link rel="alternate" type="application/rss+xml" href="https://studentersamfundet.no/syndikering/kommende-program/" title="Det Norske Studentersamfund (kommende program)" />
		<link href="<?php bloginfo( 'stylesheet_url' ); ?>" rel="stylesheet" type="text/css" />
		<link href='//fonts.googleapis.com/css?family=Arvo:700,400italic' rel='stylesheet' type='text/css'>
		<meta property="fb:app_id" content="220213643760" />
		<?php wp_head(); ?>
		<!--[if lt IE 9]>
			<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
