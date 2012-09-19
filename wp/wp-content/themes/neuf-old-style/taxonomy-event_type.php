<?php
/**
 * Event type presentation.
 *
 * By default, WordPress will fetch everything tagged with the event type. This
 * can be events and posts, sorted by when they were published. For now, all we
 * really want is the events, and we'd like to sort them by start time.
 *
 * First we set up queries to get future and past events.
 * Then we present them.
 * At last we set up tabs and endless browsing with some jQuery.
 */ 

// Set up query to fetch future posts
$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => date( 'U' , strtotime( '-8 hours' ) ), 
	'compare' => '>',
	'type'    => 'numeric'
);

$tax_query = array (
	'taxonomy' => 'event_type',
	'field' => 'slug',
	'terms' => get_query_var( 'term' )
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => array( $meta_query ),
	'tax_query'      => array( $tax_query ),
	'posts_per_page' => get_option('posts_per_page'),
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_neuf_events_starttime',
	'order'          => 'ASC'
);

$future = new WP_Query( $args );

// Set up a slightly different query to fetch past events
$meta_query['compare'] = '<=';
$args = array(
	'post_type'      => 'event',
	'meta_query'     => array( $meta_query ),
	'tax_query'      => array( $tax_query ),
	'posts_per_page' => 10,
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_neuf_events_starttime',
	'order'          => 'DESC'
);

$past = new WP_Query( $args );

$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

// OK, we got our WP_Queries how we need them.
// Let's start theming.
?>

<?php get_header(); ?>

		<div id="content" class="container_12">

			<header>
				<div class="grid_6 suffix_6">
<?php if ( $term->parent ) {
	$term_parent = get_term( $term->parent, $term->taxonomy );
?>
					<span class="parent"><a href="<?php echo get_term_link( $term_parent->slug, 'event_type' ); ?>"><?php echo $term_parent->name; ?></a></span>
<?php } ?>
					<?php neuf_page_title(); ?>
					<p class="description"><?php echo( $term->description ); ?></p>
				</div>
				<nav id="tab-control" class="grid_12">
					<ul>
						<li><a href="#future-events">Kommende arrangementer</a></li>
						<li><a href="#past-events"><?php echo $term->name ?> i fortida</a></li>
					</ul>
				</nav>
			</header>


			<section id="future-events" class="grid_12">
				<?php $wp_query = $future; // Use the future posts query as the main Loop query ?>
				<?php get_template_part( 'loop', 'taxonomy-event_type' ); ?>
			</section> <!-- #future-events -->

			<section id="past-events" class="grid_12">
				<?php $wp_query = $past; // Use the past query as the main Loop query ?>
				<?php get_template_part( 'loop', 'taxonomy-event_type' ); ?>
			</section> <!-- #future-events -->

		</div> <!-- #content -->

<?php // That's it. Before we grab the footer, we're gonna fix tabs and endless scrolling: ?>

<script type="text/javascript">

// The first part should be pretty straightforward,
// creating a giant tab box:

$(document).ready(function(){
	$('#content section').hide();
	$('#content section:first').show().addClass('current');
	$('#content nav ul li:first').addClass('current');

	$('#content nav ul li a').click(function(){
		$('#content nav ul li').removeClass('current');
		$('#content section').hide().removeClass('current');
		$(this).parent().addClass('current');
		var tab = $(this).attr('href');
		$(tab).show().addClass('current');

		return false;
	});
});

// Now to endless browsing. I've never written anything
// like this before, so beware a little mess
	var nextPastPage   = 2;
	var nextFuturePage = 2;
	var pastLoading   = false;
	var futureLoading = false;
	var pastTotal   = <?php echo   $past->max_num_pages; ?>;
	var futureTotal = <?php echo $future->max_num_pages; ?>;
	var pastLoader   =   "<img id='past-loader' alt='' src='<?php bloginfo('template_directory'); ?>/img/ajax-loader.gif' align='center' />";
	var futureLoader = "<img id='future-loader' alt='' src='<?php bloginfo('template_directory'); ?>/img/ajax-loader.gif' align='center' />";

	$(window).scroll(function() {
		if (
			// If we are viewing the past tab
			$('#past-events').hasClass('current')
			// and we reach the bottom of the #past-events section
			&& $(window).scrollTop() + $(window).height() > $('#site-header').height() + $('#content > header').height() + $('#past-events').height() + 150
			// and we're not already loading past posts 
			&& ! pastLoading
			// and we have not yet reached the end of the past posts
			&& nextPastPage < pastTotal ) {

				// load more past posts
				pastLoading = true;
				loadMorePastEvents(nextPastPage);
				nextPastPage++;
				console.log('loading more past events...');
		}

		if (
			// If we are viewing the future tab
			$('#future-events').hasClass('current')
			// and we reach the bottom of the #future-events section
			&& $(window).scrollTop() + $(window).height() > $('#site-header').height() + $('#content > header').height() + $('#future-events').height() + 150
			// and we're not already loading future posts 
			&& ! futureLoading
			// and we have not yet reached the end of the future posts
			&& nextFuturePage < futureTotal ) {

				// load more posts
				futureLoading = true;
				loadMoreFutureEvents(nextFuturePage);
				nextFuturePage++;
				console.log('loading more future events...');
		}
	});

	function loadMorePastEvents(pageNumber) {
		if ( 0 == $('#past-loader').length ) {
			$('#past-events').append(pastLoader);
		}
		$.ajax({
			url:"<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php", // grep for 'infinite scroll' in functions.php
			type: 'POST',
			data: "action=infinite_scroll&page=" + pageNumber + '&time_scope=past&term=<?php echo get_query_var('term'); ?>&template=loop-taxonomy-event_type',
			success: function(html){
				$('#past-loader').hide(2000);
				$('#past-loader').remove();
				$('#past-events').append(html);
				pastLoading = false;
			}
		});
		return false;
	}

	function loadMoreFutureEvents(pageNumber) {
		if ( 0 == $('#future-loader').length ) {
			$('#future-events').append(futureLoader);
		}
		$.ajax({
			url:"<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php", // grep for 'infinite scroll' in functions.php
			type: 'POST',
			data: "action=infinite_scroll&page=" + pageNumber + '&time_scope=future&term=<?php echo get_query_var('term'); ?>&template=loop-taxonomy-event_type',
			success: function(html){
				$('#future-loader').hide(2000);
				$('#future-loader').remove();
				$('#future-events').append(html);
				futureLoading = false;
			}
		});
		return false;
	}
</script>

<?php get_footer(); ?>
