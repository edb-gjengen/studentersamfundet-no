<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="robots" content="index,follow"/>
    <?php neuf_doctitle(); ?>
    <?php get_template_part('favicons'); ?>
    <link rel="alternate" type="application/rss+xml" href="https://studentersamfundet.no/syndikering/kommende-program/" title="Det Norske Studentersamfund (kommende program)"/>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,900,300,400italic,700italic' rel='stylesheet' type='text/css'>

    <meta property="fb:app_id" content="220213643760"/>
    <?php wp_head(); ?>

    <!-- Google Analytics -->
</head>

<body <?php neuf_body_class(); ?>>
<!-- Facebook -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/nb_NO/sdk.js#xfbml=1&version=v2.5&appId=220213643760";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<header id="site-header">
    <div class="site-header-inner">

    <div class="acc-jump"><a href="#content"><?php _e('Jump to content'); ?></a></div>
    <div class="site-brand">
        <a href="<?php bloginfo('url') ?>/" title="<?php bloginfo('name') ?>" rel="home">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/dns_logo_white.png" class="site-logo" title="<?php bloginfo('name') ?>">
        </a>
    </div>

    <nav id="static-menu">
        <?php wp_nav_menu( array( 'theme_location' => 'static-menu', 'container' => 'false' ) ); ?>
    </nav><!-- #static-menu -->

    </div>
</header><!-- #site-header -->

<?php get_template_part('menu'); ?>
