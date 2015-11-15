<div class="entry-meta">
<?php if ( 'Annetsteds' != $post->neuf_events_venue ): ?>
	<span class="venue"><?php echo $post->neuf_events_venue; ?></span>
<?php endif; ?>

	<div class="time-price">
		<span class="event-date"><?php echo ucfirst( date_i18n( 'l j. F' , $post->neuf_events_starttime ) ); ?></span>
		<span class="meta-prep meta-prep-event-time"><br /><?php _e( 'Kl:' , 'neuf' ); ?></span>
		<span class="event-time"><?php echo date_i18n( 'G.i' , $post->neuf_events_starttime ); ?></span>
		<span class="meta-sep meta-sep-event-price"> - </span>
		<span class="meta-prep meta-prep-price">CC: </span>
									<span class="meta-prep meta-prep-ticket"><?php echo $ticket ? ' <a href="'.$ticket.'">Kjøp billett</a>' : ""; ?></span>
		<span class="price"><?php echo ($price = neuf_format_price( $post )) ? $price : "Gratis"; ?></span>
	</div> <!-- .time-price -->
</div> <!-- .entry-meta-->

