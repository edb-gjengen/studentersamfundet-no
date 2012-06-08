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
        </style>
</head>
<body rightmargin="0" topmargin="0" bottommargin="0" style="margin:0;" bgcolor="#ffffff" leftmargin="0">
<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
    <tr>
        <td width="50%">
            <table width="640" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                <tr>
                    <td style="background-color:#e99835;"><img src="<?php bloginfo('template_directory'); ?>/img/logo-web.png" alt="Det Norske Studentersamfund" /></td>
                </tr>
                <tr>
                <?php if ($news->have_posts()) : $news->the_post(); ?>
                    <td id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
                            <a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
                                <b><?php the_title(); ?></b><br />
                                <?php the_date(); ?>
                                <?php the_excerpt(); ?>
                                <?php the_post_thumbnail( 'newsletter-wide' ); ?>
                            </a>
                    </td>
                <?php endif; // $news->have_posts() ?>
                </tr>
            </table>
            <table width="640" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                <tr>
                <?php while ($top_events->have_posts()) : $top_events->the_post(); ?>
                    <td width="33%" id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
                            <a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
                                <?php the_post_thumbnail('two-column-thumb'); ?><br />
                                <b><?php the_title(); ?></b>
                            </a>
                    </td>
                <?php endwhile; // $top_events->have_posts() ?>
                </tr>
            </table>
            <table width="640" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                <tr>
                    <td colspan="6"><h2>Program denne uken</h2></td>
                </tr>
                <tr>
                    <?php // TODO dynamic <b>mandag</b>, <b>tirsdag</b> ?>
                    <td colspan="6"><h3>mandag</h3></td>
                </tr>
                <?php while ($events->have_posts()) : $events->the_post(); ?>
                <tr id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
                    <td>20.00</td>
                    <td>
                        <a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                    </td>
                    <td>60/60</td>
                    <td>Konsert</td>
                    <td>Betong</td>
                    <td><a href="#">Kjøp billett</a></td>
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
