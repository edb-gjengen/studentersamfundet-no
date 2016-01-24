<?php
$ticket = $post->neuf_events_ticket_url;
$price = neuf_format_price($post);
$time = date_i18n('G.i', $post->neuf_events_starttime);
$day = date_i18n('j');
$weekday = date_i18n('l');
$month = date_i18n('F');
$year = neuf_event_format_starttime_year($post);
$datetime = date_i18n('Y-m-d\TH:i:s', $post->neuf_events_starttime);

?>
<section class="event--meta">

    <span class="event--meta--start" title="<?php echo $datetime; ?>">
        <span class="event--meta--start--weekday"><?php echo $weekday; ?></span><br>
        <span class="event--meta--start--day"><?php echo $day; ?>.</span><br>
        <span class="event--meta--start--month"><?php echo $month; ?></span><br>
        <span class="event--meta--start--year"><?php echo $year; ?></span>
    </span>
    <span class="event--meta--start--time" title="<?php echo $datetime; ?>">
        <span class="time-at"><?php _e('at', 'neuf'); ?></span><br>
        <span class="time-inner"><?php echo $time; ?></span>
    </span>
    <span class="event--meta--venue"><?php require(get_stylesheet_directory().'/dist/images/icons/location.svg'); ?><?php echo $post->neuf_events_venue; ?></span>
    <?php if ( $post->neuf_events_fb_url ): ?>
        <a href="<?php echo $post->neuf_events_fb_url; ?>" title="Arrangementet på Facebook" class="event--meta--facebook"><?php require(get_stylesheet_directory()."/dist/images/icons/facebook.svg");?></a>
    <?php endif; ?>
    <?php if($ticket): ?>
        <a href="<?php echo $ticket; ?>" class="event--meta--ticket" title="<?php _e("Ticket", 'neuf'); ?>"><?php _e('Buy ticket', 'neuf'); ?> (<?php echo $price; ?>)</a>
    <?php else: ?>
        <span class="event--meta--price" title="<?php _e("Price", 'neuf'); ?>"><?php echo $price ?></span>
    <?php endif; ?>
</section> <!-- .entry--meta -->