<?php
$date_format = 'l j. F';
if (
    0 > $post->neuf_events_starttime - strtotime('U - 1 week') // event is more than a week old
    || date( 'Y' , $post->neuf_events_starttime ) != date( 'Y') // event is not this year
) {
    $date_format = 'l j. F Y';
}
?>
<section>
    <!-- TODO:
    - remove hcalendar
    - combine price and ticket into button
    - label style on event type
    - date and time style like ticket
    -->
    <h1 class="entry-title summary"><?php the_title(); ?></h1>

    <div class="entry-meta">
        <div>
            <span class="event-date dtstart"><?php echo ucfirst( date_i18n( $date_format , $post->neuf_events_starttime ) ); ?></span>
        </div>
        <div>
            <span class="meta-prep meta-prep-event-time"><?php _e( 'Kl:' , 'neuf' ); ?></span>
            <time class="event-time dtstart" datetime="<?php echo date_i18n('Y-m-d\TH:i:sP', $post->neuf_events_starttime); ?>"><?php echo date_i18n( 'G.i' , $post->neuf_events_starttime); ?></time>
            <span class="meta-sep meta-sep-event-price"> - </span>
            <span class="meta-prep meta-prep-price">CC: </span>
            <span class="price"><?php echo neuf_format_price($post); ?></span>
            <span class="meta-prep meta-prep-price"><?php echo $post->neuf_events_ticket_url ? ' <a href="'.$post->neuf_events_ticket_url.'">Kjøp billett</a>' : ""; ?></span>
        </div>
        <div>
            <span class="venue location"><?php echo $post->neuf_events_venue; ?></span>
            <?php if ( $post->neuf_events_fb_url ): ?><span class="meta-sep meta-sep-event-facebook"> - </span>
                <span class="event-facebook"><a href="<?php echo $post->neuf_events_fb_url; ?>" title="Arrangementet på Facebook"><?php require(get_stylesheet_directory()."/dist/images/icons/facebook.svg");?></a></span>
            <?php endif; ?>
        </div>
    </div>
</section> <!-- .entry-meta-->