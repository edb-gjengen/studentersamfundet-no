<?php get_header(); ?>
<?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); ?>

		<div id="content" class="container_12">

			<header class="grid_12">
				<?php neuf_page_title(); ?>
				<p class="description"><?php echo( $term->description ); ?></p>
			</header>

<?php
// Set up future posts
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

// set up past posts
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

$past = new WP_Query( $args )
?>

			<section id="future-events" class="grid_6">
				<header>
					<h2><?php echo $term->name ?> i framtida</h2>
				</header>

				<?php $wp_query = $future; ?>
				<?php get_template_part( 'loop', 'taxonomy-event_type' ); ?>

			</section> <!-- #future-events -->

			<section id="past-events" class="grid_6">
				<header>
					<h2><?php echo $term->name ?> i fortida</h2>
				</header>

				<?php $wp_query = $past; ?>
				<?php get_template_part( 'loop', 'taxonomy-event_type' ); ?>

			</section> <!-- #future-events -->

</div> <!-- #content -->

<script type="text/javascript">

	var nextPastPage   = 2;
	var nextFuturePage = 2;
	var pastLoading   = false;
	var futureLoading = false;
	var pastTotal   = <?php echo   $past->max_num_pages; ?>;
	var futureTotal = <?php echo $future->max_num_pages; ?>;
	var pastLoader   =   "<img id='past-loader' alt='' src='<?php bloginfo('template_directory'); ?>/img/ajax-loader.gif' align='center' />";
	var futureLoader = "<img id='future-loader' alt='' src='<?php bloginfo('template_directory'); ?>/img/ajax-loader.gif' align='center' />";

	$(window).scroll(function() {
		// If we reach the bottom of the #past-events section
		if ( $(window).scrollTop() + $(window).height() > $('#site-header').height() + $('#past-events').height() - $('#site-footer').height() ) {
			// And we're not already loading or have reached the end
			if ( pastLoading || nextPastPage > pastTotal ) {
				return false;
			} else {
				// load more posts
				pastLoading = true;
				loadMorePastEvents(nextPastPage);
				nextPastPage++;
				console.log('loading more past events...');
			}
		}

		// If we reach the bottom of the #future-events section
		if ( $(window).scrollTop() + $(window).height() > $('#site-header').height() + $('#future-events').height() - $('#site-footer').height() ) {
			// And we're not already loading or have reached the end
			if ( futureLoading || nextFuturePage > futureTotal ) {
				return false;
			} else {
				// load more posts
				futureLoading = true;
				loadMoreFutureEvents(nextFuturePage);
				nextFuturePage++;
				console.log('loading more future events...');
			}
		}
	});

	function loadMorePastEvents(pageNumber) {
		if ( 0 == $('#past-loader').length ) {
			$('#past-events').append(pastLoader);
		}
		$.ajax({
			url:"<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php",
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
			url:"<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php",
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
