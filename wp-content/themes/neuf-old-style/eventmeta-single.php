					<header class="entry-meta">
<?php
$post->neuf_event_venue = get_post_meta(get_the_ID(), '_neuf_events_venue',true);
if ( 'Annet' != $post->neuf_event_venue ) {
?>
<?php } ?>
						<h1 class="entry-title"><?php the_title(); ?></h1>


						<span class="event-date"><?php echo ucfirst( date_i18n( 'l j. F' , get_post_meta(get_the_ID() , '_neuf_events_starttime' , true ) ) ); ?></span>
						<br />
						<span class="meta-prep meta-prep-event-time"><?php _e( 'Kl:' , 'neuf' ); ?></span>
						<span class="event-time"><?php echo date_i18n( 'G.i' , get_post_meta( get_the_ID() , '_neuf_events_starttime' , true ) ); ?></span> 
						<span class="meta-sep meta-sep-event-price"> - </span>
						<span class="meta-prep meta-prep-price">CC: </span>
						<span class="price"><?php echo ($price = neuf_get_price( $post )) ? $price : "Gratis"; ?></span>
						<br />
						<span class="venue"><?php echo $post->neuf_event_venue; ?></span>
					</header> <!-- .entry-meta-->

