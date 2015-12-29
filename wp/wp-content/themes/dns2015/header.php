<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="robots" content="index,follow"/>
    <?php neuf_doctitle(); ?>
    <link rel="icon" type="image/png" href="/favicon.ico"/>
    <link rel="alternate" type="application/rss+xml" href="https://studentersamfundet.no/syndikering/kommende-program/"
          title="Det Norske Studentersamfund (kommende program)"/>
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/dist/styles/app.css" rel="stylesheet" type="text/css"/>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,900,300,400italic,700italic' rel='stylesheet' type='text/css'>

    <meta property="fb:app_id" content="220213643760"/>
    <?php wp_head(); ?>

    <!-- Google Analytics -->
</head>

<body <?php neuf_body_class(); ?>>
<div id="fb-root"></div>
<!-- Facebook -->
<header id="site-header">
    <div class="site-header-inner">

    <div class="acc-jump"><a href="#content">Gå direkte til innholdet</a></div>
    <div class="site-brand">
        <a href="<?php bloginfo('url') ?>/" title="<?php bloginfo('name') ?>" rel="home">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/dns_logo_white.png" class="site-logo" title="<?php bloginfo('name') ?>">
        </a>
    </div>

    <nav id="static-menu">
        <?php wp_nav_menu( array( 'theme_location' => 'static-menu', 'container' => 'false' ) ); ?>
    </nav><!-- #static-menu -->

    </div>
</header>
<!--  #site-header -->

<?php get_template_part('menu'); ?>
