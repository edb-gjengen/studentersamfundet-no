<?php 
require( '../../../wp-load.php' );

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
        'value'   => array( 'Month' , 'semester' ),
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

$news = new WP_Query( 'type=post' );


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://ww=
w.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <title>Det Norske Studentersamfund - Nyhetsbrev</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <style type="text/css">
        body {
            font-family: Arial, sans-serif;
        }
        a,a:visited,h1,h2,h3 {
            color: #FF9E29;
            text-decoration: none;
        }
        a:hover{
            text-decoration: underline;

        }
        h1,h2,h3 {
            margin:0;
        }
        p {
            display:inline;
        }
        table {
            font-size: 0.9em;
        }
        th,td {
            border-top: 1px solid #DDD;
            padding: 4px;
        }
        .header {
            font-weight: bold;
        }
        </style>
</head>
<body rightmargin="0" topmargin="0" bottommargin="0" style="margin:0;" bgcolor="#ffffff" leftmargin="0">
<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
    <tr>
        <td width="50%">
            <table width="640" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                <tr>
                    <td colspan="4" style="background-color:#e99835;"><img src="<?php bloginfo('template_directory'); ?>/img/logo-web.png" alt="Det Norske Studentersamfund" /></td>
                </tr>
                <?php if ($news->have_posts()) : $news->the_post(); ?>
                <tr id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?> style="vertical-align:bottom;">
                    <td>
                        <a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><h2><?php the_title(); ?></h2></a>
                        <a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'newsletter-thumb', array('style' => 'display: inline-block;float:right;' ) ); ?></a>
                        <div style="font-size:0.9em; color:#222;"><?php the_date(); ?></div>
                        <?php the_excerpt(); ?>
                    </td>
                <?php endif; // $news->have_posts() ?>
                </tr>
            </table>
            <table width="640" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                <tr style="vertical-align:top;">
                <?php while ($top_events->have_posts()) : $top_events->the_post(); ?>
                    <td width="33%" id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
                            <a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
                                <?php the_post_thumbnail('newsletter-thumb'); ?><br />
                                <b><?php the_title(); ?></b>
                            </a>
                    </td>
                <?php endwhile; // $top_events->have_posts() ?>
                </tr>
            </table>
            <h2><a href="<?php bloginfo('url'); ?>/program/">Program denne uken</a></h2>
            <table width="640" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                <?php
                $current_day = "";
                $first = true;       
                while ($events->have_posts()) : $events->the_post();
                    $date = get_post_meta( $post->ID , '_neuf_events_starttime' , true );
                    $previous_day = $current_day;
                    /* set current day */
                    $current_day = date_i18n( 'l' , $date);
                    $newday = $previous_day != $current_day;
                    ($price = neuf_get_price( $post )) ? : $price = '-';
                    $venue = get_post_meta( $post->ID , '_neuf_events_venue' , true );
                    $ticket = get_post_meta( $post->ID , '_neuf_events_bs_url' , true );
                    $ticket = $ticket ? '<a href="'.$ticket.'">Kjøp billett</a>' : '';
                    $starttime = date_i18n( 'H:i' , $date);

                    /* event type class */
                    $event_array = get_the_terms( $post->ID , 'event_type' );
                    $event_types = array();
                    $event_types_real = array();
                    foreach ( $event_array as $event_type ) {
                        $event_types_real[] = $event_type->name;
                    }
                    $event_type_real = $event_types_real ? "".implode(", ", $event_types_real) : "";

                    if($newday) { ?>
                        <tr>
                            <td colspan="6" style="border-top: 0px;"><h3><?php echo $current_day; ?></h3></td>
                        </tr>

                    <?php }
                    if($first) { ?>
                        <tr class="header">
                            <td>Dato</td>
                            <td>Arrangement</td>
                            <td>CC</td>
                            <td>Type</td>
                            <td>Sted</td>
                            <td>Billett</td>
                        </tr>
                    <?php
                        $first = false;
                    }?>

                    <tr id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
                        <td><?php echo $starttime; ?></td>
                        <td>
                            <a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                        </td>
                        <td><?php echo $price; ?></td>
                        <td><?php echo $event_type_real; ?></td>
                        <td><?php echo $venue; ?></td>
                        <td><?php echo $ticket; ?></td>
                    </tr>
                <?php endwhile; // $events->have_posts() ?>
            </table>
            <table width="640" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                <tr style="text-align:center;">
                    <td><img src="<?php bloginfo('template_directory'); ?>/img/sponsors/logo_black_akademika.png" alt="Akademika" /></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
