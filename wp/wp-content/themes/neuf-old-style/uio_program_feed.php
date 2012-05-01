<?php 

/**
 * This feed is syndicated to http://www.uio.no/livet-rundt-studiene/.
 *
 * What is important for this syndication, is that the start time of the events is included as pubDate elements.
 */
header('Content-type: application/rss+xml');

require_once('../functions.php');

/*
 *   DEFAULTS FROM "load_posts()" in functions.php
 *
 *    $defaults = array(
 *        'type' => "news", 'limit' => 5,
 *        'id' => 0, 'page' => 1, 'hide_expired' => true,
 *        'sort' => 'default', 'sort2' => 'ASC',
 *        'd' => 0, // day
 *        'm' => 0, // month
 *        'y' => 0  // year
 *    );
 * 
 */

load_posts('type=events&limit=100&sort=tid&sted=false');

print('<?xml version="1.0" encoding="ISO-8859-1" ?>');
?> 
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom">

    <channel>

        <title>Program - Det Norske Studentersamfund</title>
        <description><![CDATA[Program på Det Norske Studentersamfund]]></description>
        <link>http://studentersamfundet.no/program/</link>
        <atom:link href="http://studentersamfundet.no/rss/uio_program_feed.php" rel="self" type="application/rss+xml" />

        <?php 
        if( $posts ) { 
            /* natt og dag viser frem postene i feil rekkefølge,
             * derfor reverseres rekkefølgen -- maw
	     *
	     * Det har ingen ting med rekkefølgen vi sorterer i
	     * det har med at de sorterer med tanke på pubDate hvor nyeste
	     * selvfølgelig vises først (hvem vil lese gamle nyheter?)
	     * - sjurher
             */
            foreach ( $posts as $post ) {
	?>
        <item>
            <title><?php echo( htmlspecialchars( $post->title ) ); ?></title>
            <description><?php echo("<![CDATA[" . $post->intro . "]]>"); ?></description>
            <link>http://studentersamfundet.no/vis.php?ID=<?php echo($post->id); ?></link>
            <guid>http://studentersamfundet.no/vis.php?ID=<?php echo($post->id); ?></guid>
            <content:encoded><?php echo("<![CDATA[" . $post->text . "]]>"); ?></content:encoded>
	    <pubDate><?php echo(date("D, d M Y H:i:s O", strtotime($post->date))); ?></pubDate> 
	             
	</item>
        <?php
            }
        }
        ?>

    </channel>

</rss>

