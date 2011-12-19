    <div id="sponsors">

	<div id="sponsor-uio" class="sponsor">
	    <a href="http://www.uio.no/" rel="nofollow"><img alt="Universitetet i Oslo eier Chateau Neuf" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/logo_uio86.png" /></a>
	</div>

	<div id="sponsor-studio" class="sponsor">
	    <a href="http://studio.studentersamfundet.no/" rel="nofollow"><img alt="Studentfestivalen i Oslo har base på Chateau Neuf" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/studio_logo_gronn-122x86.png" /></a>
	</div>

	<div id="sponsor-studenthovedstaden" class="sponsor">
	    <a href="http://www.studenthovedstaden.no/" rel="nofollow"><img alt="Studenthovedstaden sponser Studentersamfundet" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/logo_sh86.png" /></a>
	</div>

	<div id="sponsor-toro" class="sponsor">
	    <a href="http://www.toro.no/" rel="nofollow"><img alt="Toro sponser Studentersamfundet" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/toro-59x86.png" /></a>
	</div>
	<div id="sponsor-akademika" class="sponsor">
	    <a href="http://www.akademika.no/" rel="nofollow"><img alt="Akademika sponser Studentersamfundet" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/akademika.png" /></a>
	</div>

	<div id="sposor-nrf" class="sponsor">
	<a href="http://www.norskrockforbund.no/" rel="nofollow"><img alt="Norsk Rockforbund sponser Studentersamfundet" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/nrf-86x86.png" /></a>
	</div>

    </div>

<div id="footer">

    <div id="kolofon">

	<div id="footer-about">
	    <h2>Det Norske Studentersamfund</h2>
	    <p>Studentene i Oslo har sitt naturlige tilholdssted p&aring; Det Norske Studentersamfund, i hyggelige lokaler p&aring; Chateau Neuf &oslash;verst p&aring; Majorstuen. Her er det &aring;pent alle dager unntatt s&oslash;ndag, og enten man &oslash;nsker en tur i baren, p&aring; kaf&eacute;, p&aring; debatt, p&aring; konsert, teater eller kino, har man muligheten p&aring; Det Norske Studentersamfund.</p>
	</div> <!-- #footer-about -->

	<div id="flickr-images">
<?php
function neuf_flickr_images( $args = '' ) {
	$defaults = array(
		'type' => 'tag', // 'tag' or 'group' or 'feed'
		'tag' => 'detnorskestudentersamfund',
		'groupid' => 'Teater Neuf',
		'feed' => 'http://api.flickr.com/services/feeds/groups_pool.gne?id=1130028@N25&format=atom',
		'limit' => 12 // Max 20
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	if ( 1 > $limit )
		$limit = 1;
	if ( 20 < $limit )
		$limit = 20;

	// Using WordPress-included SimplePie instead // include_once('includes/magpierss-0.72/rss_fetch.inc');

	switch ( $type ) {
	case 'tag':
		$url = "http://api.flickr.com/services/feeds/photos_public.gne?tags=" . $tag;
		break;
	case 'group':
		$url = "http://api.flickr.com/services/feeds/groups_pool.gne?format=atom&id=" . $groupid;
		break;
	case 'feed':
		$url = "http://api.flickr.com/services/feeds/groups_pool.gne?id=1292860@N21&lang=en-us&format=atom";
		break;
	}

	$rss = fetch_feed( $url );
	
	if (!is_wp_error($rss)) {
		$maxitems = $rss->get_item_quantity(12);
		$rss->items = $rss->get_items(0,$maxitems);
	}


	echo "<h2><a href='http://www.flickr.com/groups/neuf/pool/'>Bilder fra flickr</a></h2>";
	echo "<ul>";
	$image_count = 1;
	foreach ($rss->items as $item) {
		if(!preg_match('<img src="([^"]*)" [^/]*/>', $item->get_content(), $imgUrlMatches)) {
			continue;
		}
		$baseurl = str_replace("_m.jpg", "", $imgUrlMatches[1]);
		$thumbnails = array(
			'small' => $baseurl . "_m.jpg",
			'square' => $baseurl . "_s.jpg",
			'thumbnail' => $baseurl . "_t.jpg",
			'medium' => $baseurl . ".jpg",
			'large' => $baseurl . "_b.jpg"
		);
		$byline = '"' . $item->get_title() . '" av ' . $item->get_author() ;
		echo('<li><a href="' . $item->get_permalink() . '"><img src="'.$thumbnails['square'].'"'." alt='".$byline."' title='".$byline."' /></a></li>");
		$image_count++;
		if ($image_count > $limit)
			break;
	}
	echo "</ul>";
}
neuf_flickr_images('type=group&groupid=1292860@N21');
?>
	</div>

	<div id="facebook-followers">
	    <fb:fan profile_id="23664032291" stream="0" connections="10" width="300"></fb:fan>
	</div> <!-- #facebook-followers -->
    </div> <!-- #kolofon -->

    <span class="credits">Det Norske Studentersamfund | Slemdalsveien 15, 0369 Oslo | <a href="<?php bloginfo('url'); ?>/kart.php">Kart</a> | <a href="<?php bloginfo('url'); ?>/kontakt.php">Kontakt</a> | Webdesign av <a href="http://elefantzonen.com/butikk/">Binka</a> og <a href="http://hemmeligadresse.com/bloggdesign/">Thomas Misund</a></span>

    <!-- Google Analytics -->
    <script type="text/javascript">var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www."); document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));</script><script type="text/javascript">try {var pageTracker = _gat._getTracker("UA-52914-1");pageTracker._trackPageview();} catch(err) {}</script>
    <!-- end Google Analytics -->

    <!-- Get Satisfaction -->
<script type="text/javascript" charset="utf-8">
var is_ssl = ("https:" == document.location.protocol);
var asset_host = is_ssl ? "https://s3.amazonaws.com/getsatisfaction.com/" : "http://s3.amazonaws.com/getsatisfaction.com/";
document.write(unescape("%3Cscript src='" + asset_host + "javascripts/feedback-v2.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript" charset="utf-8">
var feedback_widget_options = {};

feedback_widget_options.display = "overlay";  
feedback_widget_options.company = "dns";
feedback_widget_options.placement = "right";
feedback_widget_options.color = "#9DCBDB";
feedback_widget_options.style = "idea";
var feedback_widget = new GSFN.feedback_widget(feedback_widget_options);
</script>
    <!-- end Get Satisfaction -->

</div> <!-- #footer -->

</body>
</html>
