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

        <script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/nb_NO" type="text/javascript"></script><script type="text/javascript">FB.init("9f0d4e4f84f00af0249f45aa48fb0efb");</script>

	<?php wp_head(); ?>
    </head>

    <body <?php neuf_body_class(); ?>>
        <div id="header">

            <div id="access"><a href="#content">Gå direkte til innholdet</a></div>

            <div class="site-title">
                <span><a href="<?php bloginfo('url') ?>/" title="<?php bloginfo('name') ?>" rel="home"><?php bloginfo('name') ?></a></span>
            </div>

            <?php display_quick_menu(); // defined in functions.php ?>

            <div class="aapningstider">
		<table>
			<tr>
				<th colspan="2" style="text-align:right;">Åpningstider</th>
			</tr>
			<tr>
				<td>Mandag - tirsdag</td>
				<td style="text-align:right;">13.00 - 01.00</td>
			</tr>
			<tr>
				<td>Onsdag</td>
				<td style="text-align:right;">- 01.30</td>
			</tr>
			<tr>
				<td>Torsdag - fredag</td>
				<td style="text-align:right;">- 03.00</td>
			</tr>
			<tr>
				<td>Lørdag</td>
				<td style="text-align:right;">15.00 - 03.00</td>
			</tr>
			<tr>
				<td>Kjøkkenet</td>
				<td style="text-align:right;">- 19.00</td>
			</tr>
			<tr id="bokkafeen_tider">
				<td><a href="http://studentersamfundet.no/foreninger.php?id=3">BokCaféen</a> <span style="color:#888888; font-style:italic;">i dag</span></td>
				<td style="text-align:right;"><?php

$tider = array('Stengt',
		'19.00 - 00.00',
		'19.00 - 00.00',
		'19.00 - 00.00',
		'19.00 - 03.00',
		'19.00 - 03.00',
		'20.00 - 03.00');

$day = date('w');
echo '<a href="http://studentersamfundet.no/foreninger.php?id=3">';
echo $tider[$day];
echo '</a>';
?>
				</td>
			</tr>
                </table>
            </div>

			<?php /* Search field commented out
        <div class="search">
            <form action="http://www.studentersamfundet.no/sok.php" id="cse-search-box">
                <input type="hidden" name="cx" value="002357753470547974117:5olmwomtvom" />
                <input type="hidden" name="cof" value="FORID:11" />
                <input type="hidden" name="ie" value="UTF-8" />
                <input type="text" name="q" size="20" />
                <input type="submit" name="sa" value="Søk" />
            </form>
            <script type="text/javascript" src="http://www.google.com/jsapi"></script>
            <script type="text/javascript">google.load("elements", "1", {packages: "transliteration"});</script>
            <script type="text/javascript" src="http://www.google.com/coop/cse/t13n?form=cse-search-box&amp;t13n_langs=no"></script>
            <script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&amp;lang=no"></script>
            <a href="http://translate.google.com/translate?js=n&amp;prev=_t&amp;hl=no&amp;ie=UTF-8&amp;u=<?php echo $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']; ?>&amp;sl=no&amp;tl=en&amp;history_state0="><img src="bilder/uk.png" alt="studentersamfundet.no in English" /></a>  
        </div> <!-- .search -->
			*/ ?>
	
	<?php // display_menu(); // defined in functions.php ?>

        </div> <!-- #header -->

	<?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'container_id' => 'menu' ) ); ?>

