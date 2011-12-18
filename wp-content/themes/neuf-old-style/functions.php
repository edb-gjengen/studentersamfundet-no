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
 * Populates the $posts array.
 *
 * Use cases
 *      News archive (several news events)
 *      Single news article
 *      Single event
 *      Program view (several events)
 *      Sidebar news
 *      Sidebar events
 *
 *  Parameters should be given in a query string style, i.e. key=value&key=value
 *
 *  The defaults for overwriting are: *
 *  'type' Default is "news". Should be either "news" or "events".
 *  'limit' Default is 5. Indicates how many posts to load.
 *  'id' Default is 0. If not 0, will only load a single post with this ID.
 *  'page' Default is 1. Indicates which page we're on.
 *
 *  @param args Optional. Overwrite the defaults.
 *
 *  @param type news or events
 *  @param limit How many posts do you want?
 *  @param id Do you want a special post?
 *  @param page Offset
 *  @param hide_expired Hide expired posts
 *  @param sort Which column to order by
 *  @param sort2 ASC or DESC
 *  @param d Posts from a special day? Format: 01, requires month and year
 *  @param m Posts from a special month? Format: 01, requires year
 *  @param y Posts from a special year? Format: 2010
 *
 *  @author misund
 */
function load_posts( $args = '' ) {
	global $database_dns,$dns,$posts;

	$defaults = array(
		'type' => "news",
		'limit' => 5,
		'id' => 0,
		'page' => 1,
		'hide_expired' => true,
		'sort' => 'default',
		'sort2' => 'ASC',
		'd' => 0, // day
		'm' => 0, // month
		'y' => 0  // year
	);

	$r = wp_parse_args( $args, $defaults );
	escape($r);
	extract( $r, EXTR_SKIP );

	// All page numbers should be greater than naught
	if ( 1 > $page )
		$page = 1;

	// Limit to one if single
	if ( $id )
		$limit = 1;

	// Nothing ever goes offline
	if ( $id || $y )
		$hide_expired = false;

	$startRow = ($page-1) * $limit;

	//
	// Building the query
	//
	if ( "news" == $type )
		$query = "SELECT nyhet.*,firstname,lastname,username
		FROM nyhet,din_user
		WHERE nyhet.forfatter = din_user.username";
	else
		$query = "SELECT program.*, lokaler.navn AS place, din_division.name AS division, din_division.id AS division_id, din_division.nicename AS division_nicename
		FROM program, lokaler, din_division
		WHERE lokaler.id = program.sted
		AND din_division.id = program.arr";

	switch ( $type ) {
	case "news":
		break;
	case "events":
		break;
	case "upop":
	case "forfatteraften":
	case "foredrag":
	case "debatt":
		$types = "'debatt', 'upop', 'forfatteraften', 'foredrag'";
		break;
	case "quiz":
	case "annet":
		$types = "'annet', 'quiz'";
		break;
	case "konsert":
		$types = "'konsert'";
		break;
	case "film":
		$types = "'film'";
		break;
	case "klubb":
	case "fest":
		$types = "'fest', 'klubb'";
		break;
	case "teater":
		$types = "'teater'";
	}

	if ( isset($types) )
		$query .= " AND program.type in ($types)";

	// Single posts
	if ( $id != 0 )
		if ( "news" == $type )
			$query .= " AND nyhet.id = " . $id;
		else
			$query .= " AND program.id = " . $id;

	// Hide expired posts
	if ( $hide_expired && 0 == $id ) {
		if ( "news" == $type )
			$query .= " AND utgar >= CURDATE()";
		else
			$query .= " AND tid >= CURDATE()";
	}

	// Limit by date?

	if ( "news" == $type ) {

	} else {
		if ( $y && $m && $d ) {
			$query .= " AND program.tid >= '" . $y . "-" . $m . "-" . $d . " 00:00:00'" .
				" AND program.tid <= '" . $y . "-" . $m . "-" . $d . " 23:59:59'";
		} elseif ( $y && $m ) {
			$query .= " AND program.tid >= '" . $y . "-" . $m . "-01 00:00:00'" .
				" AND program.tid <= '" . $y . "-" . $m . "-31 23:59:59'";
		} elseif ( $y ) {
			$query .= " AND program.tid >= '" . $y . "-01-01 00:00:00'" .
				" AND program.tid <= '" . $y . "-12-31 23:59:59'";
		}
	}

	// Sort it
	switch($sort) {
	case 'default':
		if ( "news" == $type )
			$query .= " ORDER BY dato DESC";
		else
			$query .= " ORDER BY tid ASC";
		break;
	case 'random':
		$query .= " ORDER BY RAND()";
		break;
	default:
		$query .= " ORDER BY " . $sort . " " . $sort2;
		break;
	}

	// Limit it
	if ( $limit )
		$query = sprintf("%s LIMIT %d, %d", $query, $startRow, $limit);

	//
	// Done building the query
	//

	//echo('query: "'.$query);
	//echo('" page: '.$page);

	// Run the query
	$sql_posts = mysql_query($query, $dns) or die("You have to ask the right questions. " );

	// Unset any previously loaded posts
	if ( isset ($GLOBALS['posts']) ) {
		unset ( $GLOBALS['posts'] );
		global $posts;
	}

	// Load the posts array
	for ( $i=0 ; $i<$limit && $i < mysql_num_rows($sql_posts) ; $i++ ) {
		$posts[$i] = new Post(mysql_fetch_assoc($sql_posts));
	}

	mysql_free_result($sql_posts);
}

/**
 * Deprecated,use load_posts() instead.
 *
 * Deprecated. Fetch news from database and load them into $posts. To be run before displaying news.
 *
 * @deprecated
 * $param int $limit How many posts do you intend to show on each page?
 * $param boolean $static If the $_GET['page'] variable does not apply to the query, this should be true
 */
function pre_news( $static = false , $limit = 5 ) {
	load_posts('limit='.$limit);
}

/**
 * Deprecated. Use load_posts() instead.
 *
 * Deprecated. Fetch program from database and load it into $posts. To be run before displaying news.
 *
 * @deprecated
 * $param int $limit Optional. How many posts do you intend to show on each page? Defaults to 5.
 * $param boolean $static Optional. If the $_GET['page'] variable does not apply to the query, this should be true. Defaults to false.
 * $param int $id If you want a single post, pls give the ID of the post
 */
function pre_program( $static = false , $limit = 5 , $id = false ) {
	if ($id)
		load_posts('type=events&limit='.$limit.'&id='.$id);
	else
		load_posts('type=events&limit='.$limit);
}

/**
 * Mimic WordPress by escaping content for insertion into the database using addslashes(), for security
 *
 * @since Wordress 0.71
 *
 * @param string|array $data
 * @return string query safe string
 */
if ( !function_exists( 'escape' ) ) {
	function escape($data) {
		if ( is_array($data) ) {
			foreach ( (array) $data as $k => $v ) {
				if ( is_array($v) )
					$data[$k] = escape( $v );
				else
					$data[$k] = _weak_escape( $v );
			}
		} else {
			$data = _weak_escape( $data );
		}
		return $data;
	}
}
/**
 * Mimic WordPress by escaping a string using addslashes
 */
function _weak_escape($string) {
	return addslashes($string);
}


/**
 * Will return the name of the file displaying the current page.
 */
function get_requested_file() {
	$subject = $_SERVER['REQUEST_URI'];
	$pattern = '/(.*)\/(.*\.php)/';

	preg_match($pattern,$subject,$matches);

	return substr($matches[2],0,-4);
}

/**
 * Should return true if the file displaying the current page is defined as part of the given section.
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
 */
function display_quick_menu() { ?>
	<div id="quick-menu">
	    <a href="<?php bloginfo('url'); ?>/om-studentersamfundet.php">Om Studentersamfundet</a> | <a href="<?php bloginfo('url'); ?>/kart.php">Kart</a> | <a href="<?php bloginfo('url'); ?>/kontakt.php">Kontakt</a><a href="/english.php" style="margin-right:-30px;margin-left:5px;"><img src="/images/english.png" style="border:0px;" alt="english" title="english" /></a>
	</div>
<?php }

/**
 * Displays our wonderful navigation menu. If (when) changing something here, remember to update is_in_section() as well.
 */
function display_menu() { ?>
	<div id="menu">
	    <ul>
		<li id="forside-meny"<?php if ( is_in_section('forside') ) echo(' class="current"'); ?>><a href="<?php bloginfo('url'); ?>/">Forside</a>
		    <div class="sub-menu">
			<ul>
			    <li><a href="<?php bloginfo('url'); ?>/lokaler.php">Lokaler</a></li>
			    <li><a href="<?php bloginfo('url'); ?>/historie.php">Historie</a></li>
			    <li><a href="<?php bloginfo('url'); ?>/billetter.php">Billetter</a></li>
			    <li><a href="<?php bloginfo('url'); ?>/blimed.php">Bli medlem</a></li>
			    <li><a href="<?php bloginfo('url'); ?>/medlem/index.php">Registrere medlemskort</a></li>
			</ul>
		    </div>
		</li>
		<li id="program-meny"<?php if ( is_in_section('program') ) echo(' class="current"'); ?>><a href="<?php bloginfo('url'); ?>/prog.php">Program</a>
		    <div class="sub-menu">
			<ul>
			    <li><a href="<?php bloginfo('url'); ?>/prog.php">Alle</a></li>
			    <li><a href="<?php bloginfo('url'); ?>/prog.php?type=konsert">Konsert</a></li>
			    <li><a href="<?php bloginfo('url'); ?>/prog.php?type=debatt">Debatt</a></li>
			    <li><a href="<?php bloginfo('url'); ?>/prog.php?type=film">Film</a></li>
			    <li><a href="<?php bloginfo('url'); ?>/prog.php?type=fest">Fest</a></li>
			    <li><a href="<?php bloginfo('url'); ?>/prog.php?type=teater">Teater</a></li>
			    <li><a href="<?php bloginfo('url'); ?>/prog.php?type=annet">Annet</a></li>
			    <li><a href="<?php bloginfo('url'); ?>/konsepter.php">Konsepter</a></li>
			    <li><a href="<?php bloginfo('url'); ?>/booking.php">Booking</a></li>
			</ul>
		    </div>
		</li>
		<li id="foreninger-meny"<?php if ( is_in_section('foreninger') ) echo(' class="current"'); ?>><a href="<?php bloginfo('url'); ?>/foreninger.php">Foreninger</a>
		    <div class="sub-menu">
			<ul>
			    <li><a href="<?php bloginfo('url'); ?>/foreninger.php">Foreninger</a></li>
			</ul>
		    </div>
		</li>
		<li id="forum-meny"<?php if ( is_in_section('forum') ) echo(' class="current"'); ?>><a href="<?php bloginfo('url'); ?>/forum/index.php">Forum</a>
		    <div class="sub-menu">
			<ul>
			    <li><a href="<?php bloginfo('url'); ?>/forum/viewforum.php?f=68">Musikk</a></li>
			    <li><a href="<?php bloginfo('url'); ?>/forum/viewforum.php?f=69">Debatt</a></li>
			    <li><a href="<?php bloginfo('url'); ?>/forum/viewforum.php?f=70">Fritt forum</a></li>
			</ul>
		    </div>
		</li>
		<li id="inside-meny"<?php if ( is_in_section('inside') ) echo(' class="current"'); ?>><a href="<?php bloginfo('url'); ?>/inside">For medlemmer</a>
		    <div class="sub-menu">
			<ul>
			    <li><a href="<?php bloginfo('url'); ?>/inside/">Inside</a></li>
			    <li><a href="http://viteboka.studentersamfundet.no">Viteboka</a></li>
			    <li><a href="https://www.studentersamfundet.no/inside/index.php?page=display-barshifts-calendar">Tappet&aring;rnets vaktliste</a>
			    <li><a href="<?php bloginfo('url'); ?>/aktive.php">Andre ressurser</a></li>
			</ul>
		    </div>
		</li>
		<li id="nyheter-meny"<?php if ( is_in_section('nyheter') ) echo(' class="current"'); ?>><a href="<?php bloginfo('url'); ?>/nyheter.php">Nyheter</a>
		    <div class="sub-menu">
			<ul>
			</ul>
		    </div>
		</li>
	    </ul>
	</div> <!-- #menu -->
<?php
}

function neuf_body_classes() {
	echo('dns');

	if (is_home())
		echo(' home');

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

	if (is_single())
		echo(' single');
	if (is_page()) {
		echo(' page');
		echo(' page-'.get_requested_file());
	}
	if (is_program()) {
		echo(' program');
	}
	if (is_archive()) {
		echo(' archive');
	}
}

/**
 * Mimic WordPress.
 *
 * Sometimes the world seems like a rugged place, and you just wish you were 
 * working with WordPress. Fear not, 'cause this file fools you into believing
 * that's exactly what you're doing. In some cases, anyway.
 */
if ( !function_exists('bloginfo') ) :
	function bloginfo( $show ) {
		echo ( get_bloginfo( $show ) );
	}
endif;

/**
 * Mimic WordPress.
 *
 * Sometimes the world seems like a rugged place, and you just wish you were 
 * working with WordPress. Fear not, 'cause this file fools you into believing
 * that's exactly what you're doing. In some cases, anyway.
 */
if ( !function_exists('is_home') ) :
	function is_home() {
		return $_SERVER['SCRIPT_NAME'] == '/index.php';
	}
endif;

/**
 * Mimic WordPress.
 *
 * Sometimes the world seems like a rugged place, and you just wish you were 
 * working with WordPress. Fear not, 'cause this file fools you into believing
 * that's exactly what you're doing. In some cases, anyway.
 */
if ( !function_exists('is_single') ) :
	function is_single() {
		if ( is_home() || is_program() || is_archive() )
			return false;
		return true;
	}
endif;

/**
 * Mimic WordPress.
 *
 * Sometimes the world seems like a rugged place, and you just wish you were 
 * working with WordPress. Fear not, 'cause this file fools you into believing
 * that's exactly what you're doing. In some cases, anyway.
 */
if ( !function_exists('is_post') ) :
	function is_post() {
		if ( get_requested_file() == "nyhet" )
			return true;
		return false;
	}
endif;

/**
 * Mimic WordPress.
 *
 * Sometimes the world seems like a rugged place, and you just wish you were 
 * working with WordPress. Fear not, 'cause this file fools you into believing
 * that's exactly what you're doing. In some cases, anyway.
 */
if ( !function_exists('is_page') ) :
	function is_page() {
		if ( is_single() && !is_post() )
			return true;
		return false;
	}
endif;

/**
 * Mimic WordPress.
 *
 * Sometimes the world seems like a rugged place, and you just wish you were 
 * working with WordPress. Fear not, 'cause this file fools you into believing
 * that's exactly what you're doing. In some cases, anyway.
 */
if ( !function_exists('is_program') ) :
	function is_program() {
		if ( get_requested_file() == "prog" )
			return true;
		return false;
	}
endif;

/**
 * Mimic WordPress.
 *
 * Sometimes the world seems like a rugged place, and you just wish you were 
 * working with WordPress. Fear not, 'cause this file fools you into believing
 * that's exactly what you're doing. In some cases, anyway.
 */
if ( !function_exists('is_archive') ) :
	function is_archive() {
		if ( get_requested_file() == "nyheter" || get_requested_file() == "konsepter" )
			return true;
		return false;
	}
endif;

if ( !function_exists('wp_parse_args') ) :
	/**
	 * Merge user defined arguments into defaults array.
	 *
	 * This function is used throughout WordPress to allow for both string or array
	 * to be merged into another array.
	 *
	 * @since WordPress 2.2.0
	 *
	 * @param string|array $args Value to merge with $defaults
	 * @param array $defaults Array that serves as the defaults.
	 * @return array Merged user defined values with defaults.
	 */
	function wp_parse_args( $args, $defaults = '' ) {
		if ( is_object( $args ) )
			$r = get_object_vars( $args );
		elseif ( is_array( $args ) )
			$r =& $args;
		else
			wp_parse_str( $args, $r );

		if ( is_array( $defaults ) )
			return array_merge( $defaults, $r );
		return $r;
	}
endif;

if ( !function_exists('wp_parse_str') ) :
	/**
	 * Parses a string into variables to be stored in an array.
	 *
	 * Uses {@link http://www.php.net/parse_str parse_str()} and stripslashes if
	 * {@link http://www.php.net/magic_quotes magic_quotes_gpc} is on.
	 *
	 * @since WordPress 2.2.1
	 * @uses apply_filters() for the 'wp_parse_str' filter.
	 *
	 * @param string $string The string to be parsed.
	 * @param array $array Variables will be stored in this array.
	 */
	function wp_parse_str( $string, &$array ) {
		parse_str( $string, $array );
		if ( get_magic_quotes_gpc() )
			$array = stripslashes_deep( $array );
		//$array = apply_filters( 'wp_parse_str', $array );
	}
endif;

/**
 * Navigates through an array and removes slashes from the values.
 *
 * If an array is passed, the array_map() function causes a callback to pass the
 * value back to the function. The slashes from this value will removed.
 *
 * @since WordPress 2.0.0
 *
 * @param array|string $value The array or string to be striped.
 * @return array|string Stripped array (or string in the callback).
 */
if ( !function_exists( 'stripslashes_deep' ) ) {
	function stripslashes_deep($value) {
		$value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
		return $value;
	}
}
/**
 * Holds post instances to display on screen.
 *
 * @todo Move renaming of variables to query and utilize wp_parse_args() instead of the crazy if tests.
 */
class Post {
	// Variables needed for news
	var $id = 0;
	var $title = "";
	var $intro = "";
	var $text = "";
	var $attachment = ""; // deprecated
	var $attachments = array();
	var $date = 0;
	var $author = "";
	var $author_nicename = "";

	// Extra variables needed for program events
	var $type = "";
	var $division = "";
	var $division_id = 0;
	var $division_nicename = "";
	var $comment = "";
	var $place = "";
	var $priority = 0;
	var $regular_price = 0;
	var $member_price = 0;
	var $ticket_url = "";
	var $responsible_name = "";
	var $responsible_phone = "";
	var $external_responsible_name = "";
	var $external_responsible_phone = "";
	var $external_role = "";
	var $external_email = "";
	var $needs_poster = false;
	var $status = "";
	var $links = "";
	var $facebook_event_url = "";
	var $display_in_weekly_program = false;
	var $english_title = "";
	var $english_intro = "";
	var $english_text = "";

	function __construct($post) {
		// Variables needed for news
		if ( isset($post['id']) )
			$this->id = $post['id'];
		if ( isset($post['tittel']) )
			$this->title = stripslashes( $post['tittel'] );
		if ( isset($post['ingress']) )
			$this->intro = $post['ingress'];
		if ( isset($post['tekst']) )
			$this->text = $post['tekst'];
		if ( isset($post['vedlegg']) )
			$this->attachment = $post['vedlegg']; // deprecated
		if ( isset($post['vedlegg']) )
			$this->attachments[0]['picture'] = $post['vedlegg']; 
		if ( isset($post['vedlegg2']) )
			$this->attachments[1]['picture'] = $post['vedlegg2'];
		if ( isset($post['caption1']) )
			$this->attachments[0]['caption'] = $post['caption1'];
		if ( isset($post['caption2']) )
			$this->attachments[1]['caption'] = $post['caption2'];
		if ( isset($post['dato']) ) {
			$this->date = $post['dato'];
		} else if ( isset($post['tid']) ) {
			$this->date = $post['tid'];
		}
		if ( isset($post['firstname']) && isset($post['lastname']) )
			$this->author = $post['firstname'] . ' ' . $post['lastname'];
		if ( isset($post['username']) )
			$this->author_nicename = $post['username'];

		// Extra variables needed for program events
		if ( isset($post['type']) )
			$this->type = $post['type'];
		if ( isset($post['division']) )
			$this->division = $post['division']; // SELECT name AS division FROM din_division WHERE din_division.id = $post['arr']
		if ( isset($post['division_id']) )
			$this->division_id = $post['division_id'];
		if ( isset($post['division_nicename']) )
			$this->division_nicename = $post['division_nicename'];
		if ( isset($post['kommentar']) )
			$this->comment = $post['kommentar'];
		if ( isset($post['place']) ) // SELECT navn AS place from lokaler WHERE id = $post['sted']
			$this->place = $post['place']; // SELECT AS place from lokaler WHERE id = $post['sted']
		if ( isset($post['prioritet']) )
			$this->priority = $post['prioritet'];
		if ( isset($post['vpris']) )
			$this->regular_price = $post['vpris'];
		if ( isset($post['mpris']) )
			$this->member_price = $post['mpris'];
		else
			$this->member_price = $this->regular_price;
		if ( isset($post['billett']) ) {
			$this->ticket_url = $post['billett'];

			//Sjekker om link starter med "http://", viktig for bakoverkompabilitet
			if(strpos($this->ticket_url, "http://") === false) 
				$this->ticket_url = "http://".$this->ticket_url;
		}
		if ( isset($post['ansv']) )
			$this->responsible_name = $post['ansv'];
		if ( isset($post['ansv_tlf']) )
			$this->responsible_phone = $post['ansv_tlf'];
		if ( isset($post['ekst_navn']) )
			$this->external_responsible_name = $post['ekst_navn'];
		if ( isset($post['ekst_tlf']) )
			$this->external_responsible_phone = $post['ekst_tlf'];
		if ( isset($post['ekst_rolle']) )
			$this->external_role = $post['ekst_rolle'];
		if ( isset($post['ekst_epost']) )
			$this->external_email = $post['ekst_epost'];
		if ( isset($post['plakatbehov']) && $post['plakatbehov'] == 'Ja' )
			$this->needs_poster = true;
		if ( isset($post['status']) )
			$this->status = $post['status'];
		if ( isset($post['linker']) )
			$this->links = $post['linker'];
		if ( isset($post['facebook']) ) {
			$this->facebook_event_url = $post['facebook'];

			//Sjekker om link starter med "http://", viktig for bakoverkompabilitet
			if(strpos($this->facebook_event_url, "http://") === false) 
				$this->facebook_event_url = "http://".$this->facebook_event_url;
		}
		if ( isset($post['visUkeprogran']) )
			if ( $post['visUkeprogram'] == 1 )
				$this->display_in_weekly_program = true;
		if ( isset($post['tittel_en']) )
			$this->english_title = $post['tittel_en'];
		if ( isset($post['ingress_en']) )
			$this->english_intro = $post['ingress_en'];
		if ( isset($post['tekst_en']) )
			$this->english_text = $post['tekst_en'];
	}
}

/*
 * Encode text to utf-8, used in rss feeds
 */
function make_utf8_encoding($text) {
	$current_encoding = mb_detect_encoding($text, 'auto');
	$text = iconv($current_encoding, 'UTF-8', $text);
	return $text;
}

?>
