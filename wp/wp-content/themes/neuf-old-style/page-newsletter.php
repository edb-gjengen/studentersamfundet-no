<?php
/**
 * Template Name: Newsletter
 */

$meta_query = array(
	'relation' => 'AND',
	array(
		'key'     => '_neuf_events_starttime',
		'value'   => date( 'U' , strtotime( '-8 hours' ) ), 
		'compare' => '>',
		'type'    => 'numeric'
	), 
	array(
		'key'     => '_neuf_events_promo_period',
		'value'   => array( 'Month' , 'Måned' , 'Semester' ),
		'compare' => 'IN',
	)
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => $meta_query,
	'posts_per_page' => 4
);

$top_events = new WP_Query( $args );

$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => array(date( 'U' , strtotime( '-8 hours' )), date( 'U' , strtotime( '+1 week' ))),
	'type' => 'numeric',
	'compare' => 'BETWEEN'
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => array($meta_query),
	'posts_per_page' => 150,
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_neuf_events_starttime',
	'order'          => 'ASC'
);

$events = new WP_Query( $args );

list($events_start, $events_end) = $events->query_vars['meta_query'][0]['value'];

$news = new WP_Query( 'type=post&posts_per_page=2' );


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>Det Norske Studentersamfund - Nyhetsbrev</title>
	<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
	<style type="text/css">

	a:hover {
	    text-decoration: underline;

	}
	h1,h2,h3 {
	    margin:0;
	}
	p {
	    -webkit-margin-before: 0px;
	    -webkit-margin-after: 0px;
		 /*font-size:13px;*/
	}
	th,td {
	    border-top: 1px solid #DDD;padding:8px 4px;
	}
	.header {
	    font-weight: bold;
	}
/*
	.table-striped tr:nth-child(even) {
	    background-color: #F9F9F9;
	}
*/
	img, a img {
	    border: none;
	}
	</style>
</head>
<body style="margin:0;background:#ffffff">
<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:10px;font-family:Arial,sans-serif;background:#fff;font-size:13px;">
    <tr>
	<td>
	    <table width="640" cellspacing="0" cellpadding="0" style="margin:auto;margin-bottom:10px;background:#ffffff;">
		<tr style="background-color:#e99835; padding:5px;">
		    <td colspan="4"><img src="<?php bloginfo('template_directory'); ?>/img/logo-web.png" alt="Det Norske Studentersamfund"></td>
		</tr>
		<tr>
			<td colspan="4" style="font-size:11px;font-style:italic;text-align:center;">
				Kan du ikke se dette nyhetsbrevet skikkelig? <a href="http://studentersamfundet.no/nyhetsbrev/" style="color:#FF9E29;text-decoration:none;">Vis det i nettleseren i stedet.</a>
			</td>
		</tr>
		<?php if ($news->have_posts()) : while ($news->have_posts()) : $news->the_post(); ?>
		<tr id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?> style="vertical-align:bottom;">
		    <td>
			<h2><a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>" style="color:#FF9E29;text-decoration:none;font-size:20px;"><?php the_title(); ?></a></h2>
			<a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>" style="color:#FF9E29;text-decoration:none;"><?php the_post_thumbnail( 'newsletter-third', array('style' => 'display: inline-block;float:right;', 'title' => get_the_title() )); ?></a>
			<div style="font-size:13px; color:#aaa;"><?php the_date(); ?></div>
			<?php the_excerpt(); ?>
		    </td>
		</tr>
		<?php endwhile; endif; // $news->have_posts() ?>
	    </table>
	    <table width="640" cellspacing="0" cellpadding="0" style="margin:auto;margin-bottom:10px;background:#ffffff;">
		<tr style="vertical-align:top;">
			<td colspan="2">
				<h2 style="color:#FF9E29;text-decoration:none;">Det skjer på Studentersamfundet</h2>
			</td>
		</tr>
		<tr style="vertical-align:top;">
		<?php $counter = 1; ?>
		<?php while ($top_events->have_posts()) : $top_events->the_post(); ?>
<?php $date = get_post_meta( $post->ID , '_neuf_events_starttime' , true );
$previous_day = $current_day;
/* set current day */
$current_day = date_i18n( 'l' , $date);
($price = neuf_get_price( $post )) ? : $price = '-';
$venue = get_post_meta( $post->ID , '_neuf_events_venue' , true );
$ticket = get_post_meta( $post->ID , '_neuf_events_bs_url' , true );
$ticket = $ticket ? '<a href="'.$ticket.'" style="color:#FF9E29;text-decoration:none;">Kjøp billett</a>' : '';
$starttime = date_i18n( 'j. F' , $date);

/* event type class */
$event_array = get_the_terms( $post->ID , 'event_type' );
$event_types = array();
$event_types_real = array();
foreach ( $event_array as $event_type ) {
	$event_types_real[] = $event_type->name;
}
$event_type_real = $event_types_real ? "".implode(", ", $event_types_real) : "";
?>
		    <?php if($counter % 3 == 0)  { ?>
			<tr style="vertical-align:top;">
		    <?php } ?>
		    <td>
			    <a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>" style="color:#FF9E29;text-decoration:none;">
				<?php the_post_thumbnail('newsletter-half', array('title' => get_the_title())); ?>
	</a><br>
				<p style="font-size:13px;color:#aaa;margin-top:4px;margin-bottom:0px;"><?php echo $event_type_real; ?></p>
				<h2 style="margin-top:0px;font-size:20px;font-weight:bold;"><a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>" style="color:#FF9E29;text-decoration:none;"><?php the_title(); ?></a></h2>
				<div style="margin-top:4px; margin-bottom:4px;"><?php the_excerpt(); ?></div>
				<p style="margin-top:4px; margin-bottom:4px;font-weight:bold;"><?php echo "$starttime $price $venue"; ?></p>
		    </td>
		    <?php if($counter % 2 == 0)  { ?>
			</tr>
		    <?php } ?>
<?php 
$counter += 1;
endwhile; // $top_events->have_posts() ?>
	    </table>
	    <table width="640" cellspacing="0" cellpadding="0" class="table-striped program" style="margin:auto;margin-bottom:10px;background:#ffffff;">
		<tr style="vertical-align:top;">
			<td colspan="6">
			    <h2><a href="<?php bloginfo('url'); ?>/program/" title="Les hele programmet på studentersamfundet.no" style="color:#FF9E29;font-size:20px;text-decoration:none;">Program denne uken</a></h2>
			</td>
		</tr>
<?php

function maybe_color() {
	global $trcount;
	if ( 1 == ++$trcount % 2 )
		echo ' style="background:#f9f9f9;"';
}

$current_day = "";
$first = true;       
$trcount = 0;
while ($events->have_posts()) : $events->the_post();
$date = get_post_meta( $post->ID , '_neuf_events_starttime' , true );
$previous_day = $current_day;
/* set current day */
$current_day = ucfirst( date_i18n( 'l j. F' , $date) );
$newday = $previous_day != $current_day;
($price = neuf_get_price( $post )) ? : $price = '-';
$venue = get_post_meta( $post->ID , '_neuf_events_venue' , true );
$ticket = get_post_meta( $post->ID , '_neuf_events_bs_url' , true );
$ticket = $ticket ? '<a href="'.$ticket.'" style="color:#FF9E29;text-decoration:none;">Kjøp billett</a>' : '';
$starttime = date_i18n( 'H.i' , $date);

/* event type class */
$event_array = get_the_terms( $post->ID , 'event_type' );
$event_types = array();
$event_types_real = array();
foreach ( $event_array as $event_type ) {
	$event_types_real[] = $event_type->name;
}
$event_type_real = $event_types_real ? "".implode(", ", $event_types_real) : "";
$facebook = get_post_meta( get_the_ID() , '_neuf_events_fb_url', true );
$facebook_icon = $facebook ? ' <a href="'.$facebook.'" title="' . get_the_title() . ' på Facebook" style="color:#FF9E29;text-decoration:none;"><img src="'.get_bloginfo('stylesheet_directory').'/img/facebook-icon.png" width="13px" height="13px" style="position:relative;top:2px;" alt="' . get_the_title() . ' på Facebook"></a>' : "";
if($newday) { ?>
			<tr<?php maybe_color(); ?>>
			    <td colspan="6" style="border-top: 1px solid #DDD;padding:8px 4px;border-top: 0px;"><h3><?php echo $current_day; ?></h3></td>
			</tr>

<?php }
if($first) { ?>
			<tr class="header"<?php maybe_color(); ?>>
			    <td style="border-top: 1px solid #DDD;padding:8px 4px;">Tid</td>
			    <td style="border-top: 1px solid #DDD;padding:8px 4px;">Konsept</td>
			    <td style="border-top: 1px solid #DDD;padding:8px 4px;width:250px">Arrangement</td>
			    <td style="border-top: 1px solid #DDD;padding:8px 4px;">Sted</td>
			    <td style="border-top: 1px solid #DDD;padding:8px 4px;">CC</td>
			    <td style="border-top: 1px solid #DDD;padding:8px 4px;">Billett</td>
			</tr>
<?php
	$first = false;
}?>

		    <tr id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?><?php maybe_color(); ?>>
			<td style="border-top: 1px solid #DDD;padding:8px 4px;"><?php echo $starttime; ?></td>
			<td style="border-top: 1px solid #DDD;padding:8px 4px;"><?php echo $event_type_real; ?></td>
			<td style="border-top: 1px solid #DDD;padding:8px 4px;width:250px">
			    <a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>" style="color:#FF9E29;text-decoration:none;"><?php the_title(); ?></a>
			</td>
			<td style="border-top: 1px solid #DDD;padding:8px 4px;"><?php echo $venue . $facebook_icon; ?></td>
			<td style="border-top: 1px solid #DDD;padding:8px 4px;"><?php echo $price; ?></td>
			<td style="border-top: 1px solid #DDD;padding:8px 4px;"><?php echo $ticket; ?></td>
		    </tr>
		<?php endwhile; // $events->have_posts() ?>
	    </table>
	    <table width="640" cellspacing="0" cellpadding="0" style="margin:auto;margin-top:20px;margin-bottom:10px;background:#ffffff;">
		<tr style="text-align:center;">
		    <td style="border:0px;"><img src="<?php bloginfo('template_directory'); ?>/img/sponsors/logo_black_akademika.png" alt="Akademika"></td>
		</tr>
		<tr style="text-align:center;margin-top:5px;">
			<td style="padding:8px 4px;border:0px;font-size:13px;">
				Det Norske Studentersamfund<br>
				<a href="http://studentersamfundet.no" style="color:#FF9E29;text-decoration:none;">studentersamfundet.no</a><br><br>
				Chateau Neuf, Slemdalsveien 15, 0369 Oslo, tlf: 22 84 45 11<br><br>

				Du får nyhetsbrev av oss fordi du har takket ja til det på nettsidene våre.
		</tr>
	    </table>
	</td>
    </tr>
</table>
</body>
</html>