<?php
/**
 * Template Name: Newsletter (Mailchimp)
 *
 * Params (GET):
 *  - disable_merge_tags: hide Mailchimp merge tags
 *  - articles: Number of top articles to show
 */
function maybe_color() {
    global $trcount;
    if( 1 == ++$trcount % 2 ) {
        echo ' style="background:#f9f9f9;"';
    }
}

$cell_style = 'border-top: 1px solid #ddd;padding:8px 4px;';

$articles = 1;
$list_url = "*|LIST:URL|*";
$list_address = "*|LIST:ADDRESS|*";
$list_description = "*|LIST:DESCRIPTION|*";
$unsubscribe_url = "*|UNSUB|*";
$archive_url = "*|ARCHIVE|*";
$merge_tags = true;

if(array_key_exists("articles", $_GET) && is_numeric($_GET['articles'])) {
    $articles = $_GET['articles'];
}

if(isset($_GET['disable_merge_tags'])) {
    $list_url = get_bloginfo('url');
    $list_address = "Chateau Neuf, Slemdalsveien 15, 0313 OSLO";
    $list_description = "Du får nyhetsbrev av oss fordi du har takket ja på nettsidene våre.";
    $unsubscribe_url = '';
    $archive_url = "$list_url/nyhetsbrev/?utm_source=newsletter&utm_medium=email&utm_campaign=newsletter&articles=$articles";
    $merge_tags = false;
}
/* Top events */
$querystr = get_top_events_query();
$top_events = $wpdb->get_results($querystr, OBJECT);

/* This weeks events */
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

/* Latest articles */
$news = new WP_Query( "type=post&posts_per_page=$articles&ignore_sticky_posts=true" );
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
        }
        th,td {
            <?php echo $cell_style; ?>

        }
        .header {
            font-weight: bold;
        }
        img, a img {
            border: none;
        }
    </style>
</head>
<body style="margin-bottom:10px;font-family:Arial,sans-serif;background:#fff;font-size:13px;">

<!-- Latest articles -->
<table width="640" cellspacing="0" cellpadding="0" style="margin:auto;margin-bottom:10px;background:#ffffff;">
    <tr style="background-color:#f58220; padding:5px;">
        <td colspan="4"><img src="<?php bloginfo('template_directory'); ?>/dist/images/dns_logo_white_newsletter.png" alt="Det Norske Studentersamfund" style="margin-left: 25px;"></td>
    </tr>
    <?php if( $merge_tags ): ?>*|IFNOT:ARCHIVE_PAGE|*<?php endif; ?>
    <tr>
        <td colspan="4" style="font-size:11px;font-style:italic;text-align:center;">
            Kan du ikke se dette nyhetsbrevet skikkelig? <a href="<?php echo $archive_url; ?>" style="color:#f58220;text-decoration:none;">Vis det i nettleseren i stedet.</a>
        </td>
    </tr>
    <?php if( $merge_tags ): ?>*|END:IF|*<?php endif;

    if ($news->have_posts()) : while ($news->have_posts()) : $news->the_post(); ?>
        <tr id="post-<?php the_ID(); ?>" style="vertical-align:bottom;">
            <td>
                <h2><a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>" style="color:#f58220;text-decoration:none;font-size:20px;"><?php the_title(); ?></a></h2>
                <a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>" style="color:#f58220;text-decoration:none;"><?php the_post_thumbnail( 'newsletter-third', array('style' => 'display: inline-block;float:right;', 'title' => get_the_title() )); ?></a>
                <div style="font-size:13px; color:#aaa;"><?php echo get_the_date(); ?></div>
                <?php the_excerpt(); ?>
            </td>
        </tr>
    <?php endwhile; endif; // $news->have_posts() ?>
</table>

<!-- Featured events -->
<table width="640" cellspacing="0" cellpadding="0" style="margin:auto;margin-bottom:10px;background:#ffffff;">
    <tr style="vertical-align:top;">
        <td colspan="2">
            <h2 style="color:#f58220;text-decoration:none;">Det skjer på Studentersamfundet</h2>
        </td>
    </tr>
    <tr style="vertical-align:top;">
        <?php
        $counter = 1;
        $current_day = "";
        if($top_events): global $post; foreach ($top_events as $post): setup_postdata($post);
            $timestamp = $post->neuf_events_starttime;
            $previous_day = $current_day;
            /* Set current day */
            $current_day = date_i18n( 'l' , $timestamp);
            ($price = neuf_format_price( $post )) !== _('Free') ? : $price = '-';
            $venue = $post->neuf_events_venue;
            $ticket_url = $post->neuf_events_ticket_url;
            $ticket_url = $ticket_url ? '<a href="'.$ticket_url.'" style="color:#f58220;text-decoration:none;">Kjøp billett</a>' : '';
            $starttime = date_i18n( 'j. F' , $timestamp);

            /* Event category */
            $event_array = get_the_terms( $post->ID , 'event_type' );
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
                    <a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>" style="color:#f58220;text-decoration:none;">
                        <?php the_post_thumbnail('newsletter-half', array('title' => get_the_title())); ?>
                    </a><br>
                    <p style="font-size:13px;color:#aaa;margin-top:4px;margin-bottom:0px;"><?php echo $event_type_real; ?></p>
                    <h2 style="margin-top:0px;font-size:20px;font-weight:bold;"><a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>" style="color:#f58220;text-decoration:none;"><?php the_title(); ?></a></h2>
                    <div style="margin-top:4px; margin-bottom:4px;"><?php the_excerpt(); ?></div>
                    <p style="margin-top:4px; margin-bottom:4px;font-weight:bold;"><?php echo "$starttime $price $venue"; ?></p>
                </td>
                    <?php if($counter % 2 == 0)  { ?>
                </tr>
            <?php } ?>
        <?php
        $counter += 1;
    endforeach; endif; // $top_events->have_posts()
    ?>
</table>

<!-- This weeks events -->
<table width="640" cellspacing="0" cellpadding="0" class="table-striped program" style="margin:auto;margin-bottom:10px;background:#ffffff;">
    <tr style="vertical-align:top;">
        <td colspan="6">
            <h2><a href="<?php bloginfo('url'); ?>/program/" title="Les hele programmet på studentersamfundet.no" style="color:#f58220;font-size:20px;text-decoration:none;">Program denne uken</a></h2>
        </td>
    </tr>
    <?php
    $current_day = "";
    $print_header = true;
    $trcount = 0;
    while ($events->have_posts()) : $events->the_post();
        $timestamp = $post->neuf_events_starttime;
        $previous_day = $current_day;
        /* set current day */
        $current_day = ucfirst( date_i18n( 'l j. F' , $timestamp) );
        $newday = $previous_day != $current_day;

        ($price = neuf_format_price( $post )) !== _('Free') ? : $price = '-';
        $venue = $post->neuf_events_venue;
        $ticket_url = $post->neuf_events_ticket_url;
        $ticket_url = $ticket_url ? '<a href="'.$ticket_url.'" style="color:#f58220;text-decoration:none;">Kjøp billett</a>' : '';
        $starttime = date_i18n( 'H.i' , $timestamp);

        /* event type class */
        $event_array = get_the_terms( $post->ID , 'event_type' );
        $event_types_real = array();
        foreach ( $event_array as $event_type ) {
            $event_types_real[] = $event_type->name;
        }
        $event_type_real = $event_types_real ? "".implode(", ", $event_types_real) : "";
        $facebook_url = $post->neuf_events_fb_url;
        $facebook_icon = $facebook_url ? ' <a href="'.$facebook_url.'" title="' . get_the_title() . ' på Facebook" style="color:#f58220;text-decoration:none;"><img src="'.get_bloginfo('stylesheet_directory').'/dist/images/icons/facebook.png" width="13px" height="13px" style="position:relative;top:2px;" alt="' . get_the_title() . ' på Facebook"></a>' : "";
        if($newday): ?>
            <tr<?php maybe_color(); ?>>
                <td colspan="6" style="<?php echo $cell_style; ?>border-top: 0;"><h3><?php echo $current_day; ?></h3></td>
            </tr>
        <?php endif;
        if($print_header): ?>
            <tr class="header" <?php maybe_color(); ?>>
                <td style="<?php echo $cell_style; ?>">Tid</td>
                <td style="<?php echo $cell_style; ?>">Konsept</td>
                <td style="<?php echo $cell_style; ?>width:250px">Arrangement</td>
                <td style="<?php echo $cell_style; ?>">Sted</td>
                <td style="<?php echo $cell_style; ?>">CC</td>
                <td style="<?php echo $cell_style; ?>">Billett</td>
            </tr>
            <?php
            $print_header = false;
        endif; ?>

        <tr id="post-<?php the_ID(); ?>" <?php maybe_color(); ?>>
            <td style="<?php echo $cell_style; ?>"><?php echo $starttime; ?></td>
            <td style="<?php echo $cell_style; ?>"><?php echo $event_type_real; ?></td>
            <td style="<?php echo $cell_style; ?>width:250px">
                <a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>" style="color:#f58220;text-decoration:none;"><?php the_title(); ?></a>
            </td>
            <td style="<?php echo $cell_style; ?>"><?php echo $venue . $facebook_icon; ?></td>
            <td style="<?php echo $cell_style; ?>"><?php echo $price; ?></td>
            <td style="<?php echo $cell_style; ?>"><?php echo $ticket_url; ?></td>
        </tr>
    <?php endwhile; // $events->have_posts() ?>
</table>

<!-- Footer -->
<table width="640" cellspacing="0" cellpadding="0" style="margin:auto;margin-top:20px;margin-bottom:10px;background:#ffffff;">
    <tr style="text-align:center;margin-top:5px;">
        <td style="padding:8px 4px;border:0;font-size:13px;">
            Det Norske Studentersamfund<br>
            <a href="<?php echo $list_url; ?>" target="_blank" style="color:#f58220;text-decoration:none;">studentersamfundet.no</a><br><br>
            <?php echo $list_address; ?><br><br>
            <?php echo $list_description; ?><br>
            <?php if( $merge_tags ): ?>
                <a href="<?php echo $unsubscribe_url; ?>">Meld meg av denne listen</a>.
            <?php endif; ?>
        </td>
    </tr>
</table>

</body>
</html>










