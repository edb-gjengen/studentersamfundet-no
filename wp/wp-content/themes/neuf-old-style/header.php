<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="no" lang="no">
	<head>
		<meta charset="utf-8" />
		<meta name="robots" content="index,follow" />

		<?php neuf_doctitle(); ?>

		<link rel="icon" type="image/png" href="/favicon.ico" />
		<link rel="alternate" type="application/rss+xml" href="https://studentersamfundet.no/syndikering/kommende-program/" title="Det Norske Studentersamfund (kommende program)" />
        <link rel="alternate" hreflang="nb" href="<?php bloginfo('url'); ?>">
		<link href="<?php bloginfo( 'stylesheet_url' ); ?>" rel="stylesheet" type="text/css" />
		<link href='//fonts.googleapis.com/css?family=Arvo:700,400italic' rel='stylesheet' type='text/css'>
		<link href='//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css' rel='stylesheet' type='text/css'>

		<meta property="fb:app_id" content="220213643760" />
		<?php wp_head(); ?>
		<!--[if lt IE 9]>
			<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

        <!-- Google Analytics -->
            <script type="text/javascript">
              var _gaq = _gaq || [];
              _gaq.push(['_setAccount', 'UA-52914-1']);
              _gaq.push(['_setDomainName', 'studentersamfundet.no']);
              _gaq.push(['_trackPageview']);

              (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
              })();
            </script>
        <!-- end Google Analytics -->
        <!-- Facebook Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','//connect.facebook.net/en_US/fbevents.js');

        fbq('init', '1639917126248523');
        fbq('track', "PageView");
        </script>
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1639917126248523&ev=PageView&noscript=1"/></noscript>
        <!-- End Facebook Pixel Code -->
	</head>

	<body <?php neuf_body_class(); ?>>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/nb_NO/all.js#xfbml=1&appId=220213643760";
			fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
		<header id="site-header">
			<div class="container_12" style="margin-left:auto;margin-right:auto">

				<div id="access"><a href="#content">Gå direkte til innholdet</a></div>

					<div class="site-title grid_6">
						<span><a href="<?php bloginfo('url') ?>/" title="<?php bloginfo('name') ?>" rel="home"><?php bloginfo('name') ?></a></span>
					</div>

<?php get_template_part( 'menu' ); ?>

			</div> 
		</header><!--  #site-header -->

