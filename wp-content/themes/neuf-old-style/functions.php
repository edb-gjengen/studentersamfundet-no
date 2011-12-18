<?php
add_theme_support( 'post-thumbnails');

/**
 * Register navigation menus.
 */
function neuf_register_nav_menus() {
	register_nav_menus(
		array( 'main-menu' => __( 'Hovedmeny' ) )
	);
}
add_action( 'init' , 'neuf_register_nav_menus' );

/**
 * Should return true if the file displaying the current page is defined as part of the given section.
 *
 * @todo Do we need this in a WordPress theme? misund 2012-12-18
 */
function is_in_section($section) {
	switch ($section) {
	case 'program':
		if ( in_array(get_requested_file(),array('prog','konsepter','konsept','booking','vis')) )
			return true;
		break;
	case 'foreninger':
		if ( in_array(get_requested_file(),array('foreninger')) )
			return true;
		break;
	case 'forum':
		if ( substr($_SERVER['REQUEST_URI'],1,5) == 'forum' )
			return true;
		break;
	case 'inside':
		if ( substr($_SERVER['REQUEST_URI'],1,6) == 'inside' || in_array(get_requested_file(),array('aktive')) )
			return true;
		break;
	case 'nyheter':
		if ( in_array(get_requested_file(),array('nyheter','nyhet')) )
			return true;
		break;
	case 'forside':
		if ( !( is_in_section('program') || is_in_section('foreninger') || is_in_section('medlem') || is_in_section('forum') || is_in_section('inside') || is_in_section('nyheter') ) )
			return true;
		break;
	default:
		return false;
	}
}

/**
 * WTF.
 *
 * Why do we do this in this way, and do we need it in WordPress? misund 2012-12-18
 */
if (!function_exists('prepareOutput')) {
	function prepareOutput( $tekst ){
		$tekst = StripSlashes($tekst);
		//    $tekst = nl2br($tekst);

		$tekst = preg_replace(array("/\r\n\r\n/", "/\r\n \r\n/", "/\n\n/", "/\n \n/"), "</p>\n\n<p>", $tekst);
		$tekst = preg_replace(array("/\r\n/", "/\n/"), "<br />\n", $tekst);
		$tekst = '<p>' . $tekst . '</p>';

		// Listebehandling
		$tekst = str_replace("[ul]", "</p><ul>", $tekst);
		$tekst = str_replace("[/ul]", "</ul><p>", $tekst);
		$tekst = str_replace("[li]", "<li>", $tekst);
		$tekst = str_replace("[/li]", "</li>", $tekst);

		$tekst = str_replace("[b]", "<strong>", $tekst);
		$tekst = str_replace("[/b]", "</strong>", $tekst);
		$tekst = str_replace("[i]", "<em>", $tekst);
		$tekst = str_replace("[/i]", "</em>", $tekst);

		return $tekst;
	}
}

/**
 * Display social sharing buttons
 */
function display_social_sharing_buttons() { ?>
		    <div id="social-content-top">
			<div id="facebook-share-content-top" class="facebook-share">
			    <fb:share-button class="url" href="<?php echo("".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']); ?>" type="box_count"></fb:share-button>
			</div> <!-- .facebook-share -->

			<div id="tweetmeme-content-top" class="tweetmeme">
	<script type="text/javascript">
	tweetmeme_source = 'DNS1813';
</script>
			    <script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>
			</div> <!-- .tweetmeme -->
		    </div> <!-- #social-content-top -->

<?php }

/**
 * Displays our wonderful wuick menu.
 *
 * @todo Should be replaced with a WordPress menu. misund 2012-12-18
 */
function display_quick_menu() { ?>
	<div id="quick-menu">
	    <a href="<?php bloginfo('url'); ?>/om-studentersamfundet.php">Om Studentersamfundet</a> | <a href="<?php bloginfo('url'); ?>/kart.php">Kart</a> | <a href="<?php bloginfo('url'); ?>/kontakt.php">Kontakt</a><a href="/english.php" style="margin-right:-30px;margin-left:5px;"><img src="/images/english.png" style="border:0px;" alt="english" title="english" /></a>
	</div>
<?php }


/**
 * Adds dynamic classes to the body element.
 *
 * @todo Should be replaced with the version of this function that is defined in the theme neuf-web. misund 2012-12-18
 */
function neuf_body_classes() {
	echo('dns');

	if (is_home())
		echo(' home');

	/*
	if (is_in_section('forside'))
		echo(' section-forside');
	if (is_in_section('program'))
		echo(' section-program');
	if (is_in_section('nyheter'))
		echo(' section-nyheter');
	if (is_in_section('foreninger'))
		echo(' section-foreninger');
	if (is_in_section('forum'))
		echo(' section-forum');
	if (is_in_section('inside'))
		echo(' section-inside');
	 */

	if (is_single())
		echo(' single');
	if (is_page()) {
		echo(' page');
		echo(' page-'.get_requested_file());
	}

	if (is_page('program')) {
		echo(' program');
	}
	if (is_archive()) {
		echo(' archive');
	}
}

/**
 * Encode text to utf-8, used in rss feeds.
 *
 * @todo find out if WordPress does this on it's own. misund 2012-12-18
 */
function make_utf8_encoding($text) {
	$current_encoding = mb_detect_encoding($text, 'auto');
	$text = iconv($current_encoding, 'UTF-8', $text);
	return $text;
}

?>
