<?php

get_header();

get_template_part( 'eventslider' );

/* Include campaigns here */
?>
<div id="content">

    <div class="join">
        <a href="<?php bloginfo('url'); ?>/medlemmer.php">Bli medlem!</a>
    </div>

    <div class="first column">

        <div id="lead-text">
            <img src="http://studentersamfundet.no/bilder/neuf.jpg" alt="Fasaden av Chateau Neuf" />
            <h1>Det Norske Studentersamfund</h1>
            <div style="font-style:italic;font-size:smaller;">mange steder p&aring; et sted</div>
            <p>Studentene i Oslo har sitt naturlige tilholdssted p&aring; Det Norske
            Studentersamfund, i hyggelige lokaler p&aring; Chateau Neuf &oslash;verst p&aring; Majorstuen.
            Her er det &aring;pent alle dager unntatt s&oslash;ndag, og enten man &oslash;nsker en tur i 
            baren, p&aring; kaf&eacute;, p&aring; debatt, p&aring; konsert, teater eller kino, har man muligheten
            p&aring; Det Norske Studentersamfund.</p>
        </div>

<!--
        <div id="campaign1" class="campaign">
            <a href="<?php bloginfo('url'); ?>/vis.php?ID=3960" title="Alle Festers Mor">Alle Festers Mor</a>
        </div>

        <div id="campaign2" class="campaign">
            <a href="<?php bloginfo('url'); ?>/vis.php?ID=3705" title="Klubb Kadanza">Klubb Kadanza</a>
        </div>

        <div id="campaign3" class="campaign">
            <a href="<?php bloginfo('url'); ?>/vis.php?ID=3693" title="Superfamily">Superfamily</a>
        </div>
-->
	<?php include 'kampanje/kampanje.php' ?>
    </div> <!-- .first.column -->
    <div class="last column">

        <div id="calendar" class="info">
        <h3><a href="<?php bloginfo('url'); ?>/prog.php">Program</a></h3>
        <ul>
            <?php //load_posts('type=events&limit=6'); // @TODO load query with events

            $event_counter = 0;

            if ( $posts ) : foreach ( $posts as $post ) :
                $event_counter++;
            ?>
            <li id="calendar-event-<?php echo($event_counter); ?>" class="calendar-event<?php if ($event_counter % 2 == 0) echo(" alt"); ?>">
			<div class="term hour"><?php echo(date("d/m", strtotime($post->date))); ?></div>
			<div class="definition title">
				<a href="<?php bloginfo('url'); ?>/vis.php?ID=<?php echo($post->id); ?>"><?php echo($post->title); ?></a>
			</div>
		</li>
            <?php endforeach; endif; ?>
        </ul>
        </div>

        <div id="articles" class="hfeed">
        <h3><a href="<?php bloginfo('url'); ?>/nyheter.php">Nyheter</a></h3>

<?php // The LOOP
            if ( have_posts() ) : while ( have_posts() ) : the_post();
?>

            <div id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
                <?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" title="Permalenke til <?php the_title(); ?>"><?php the_post_thumbnail(); ?></a>
                <?php else : ?>
                <a href="<?php the_permalink(); ?>" title="Permalenke til <?php the_title(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/placeholder.png" alt="" /></a>
                <?php endif; ?>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="Permalenke til <?php the_title(); ?>"><?php the_title(); ?></a></h2>
                <div class="entry-summary"><?php the_excerpt(); ?></div>
            </div>
            <?php endwhile; ?>
            <div id="more" class="post"><a href="<?php bloginfo('url'); ?>/nyheter.php">Les flere nyheter</a></div>
            <?php endif; ?>

        </div>
        <!--div id="frontpage-ads" class="ads">
            <h3>Reklame</h3>
            <div class="ads-container">
                <div class="ad">
                    <a rel="nofollow" href="http://www.oslopuls.no"><img src="<?php bloginfo('image-root'); ?>/bilder/bannere/banner150_Studentersamfundet_aug09.gif" alt="Oslopuls" /></a>
                </div>
                <div class="ad">
                    <a rel="nofollow" href="http://kundeservice.aftenposten.no/services/subscriptionOrder/showSubscriptionOrder.htm?id=201"><img src="<?php bloginfo('image-root'); ?>/bilder/150x150_student.jpg" alt="Aftenposten" /></a>
                </div>
            </div>
        </div-->

    </div> <!-- .last.column -->

</div> <!-- #content -->

<?php get_footer(); ?>
