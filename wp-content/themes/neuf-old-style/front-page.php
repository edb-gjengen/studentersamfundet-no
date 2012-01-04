<?php

get_header();

/* Include campaigns here */
?>
<div id="content">

	<div class="container_12">

<?php get_template_part( 'eventslider' ); ?>

		<div class="join">
			<a href="<?php bloginfo('url'); ?>/medlemmer.php">Bli medlem!</a>
		</div>

<?php
/**
 * Events from today.
 *
 * Ref: http://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters
 */
$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => date( 'U' , strtotime( '-8 hours' ) ), 
	'compare' => '>',
	'type'    => 'numeric'
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => array( $meta_query ),
	'posts_per_page' => 50,
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_neuf_events_starttime',
	'order'          => 'ASC'
);

$events = new WP_Query( $args );

if ( $events->have_posts() ) :
	$event_daycounter = 0;
?>

		<div id="weekly-program">

			<div class="day grid_2">

		<?php while ( $events->have_posts() ) : $events->the_post(); ?>

	<?php
		if ( isset( $event_current_day ) )
			$event_previous_day = $event_current_day;

		$event_current_day = date( 'Y-m-d' , get_post_meta( $post->ID , '_neuf_events_starttime' , true ) );

		if ( isset( $event_previous_day ) &&  $event_previous_day != $event_current_day ) {
			// New day
			if ( $event_daycounter >= 6 )
				break;
	?>
			</div> <!-- .day -->

			<div class="day grid_2">
		<?php } ?>

			<?php
				if ( !isset( $event_previous_day ) || $event_previous_day != $event_current_day ) {
					$event_daycounter++;
			?>

				<p><?php echo date( 'l j/n Y' , get_post_meta( $post->ID , '_neuf_events_starttime' , true ) ); ?></p>

			<?php } ?>

				<?php
				if( has_post_thumbnail() )
					the_post_thumbnail(); 
				?>
				<h2><a href="<?php the_permalink(); ?>" title="Permanent lenke til <?php the_title(); ?>"><?php echo the_title(); ?></a></h2>


		<?php endwhile; // $events->have_posts(); ?>

			</div> <!-- .day -->

		</div> <!-- #weekly_program -->

<?php endif; // $events->have_posts() ?>


		<div class="grid_5">

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
		<div class="grid_5">

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

	</div> <!-- .container_12 -->

</div> <!-- #content -->

<?php get_footer(); ?>
