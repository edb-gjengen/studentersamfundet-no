<?php
$post->neuf_event_venue = get_post_meta(get_the_ID(), '_neuf_events_venue',true);
$ticket = get_post_meta(get_the_id(), '_neuf_events_bs_url',true);
$starttime = get_post_meta(get_the_ID() , '_neuf_events_starttime' , true );

$date_format = 'l j. F';
if ( 
	0 > $starttime - strtotime('U - 1 week') // event is more than a week old
	|| date( 'Y' , $starttime ) != date( 'Y') // event is not this year
) {
	$date_format = 'l j. F Y';
} 
?>
					<header class="entry-meta">
						<?php // The class 'summary' is part of the hCalendar spec ?>
						<h1 class="entry-title summary"><?php the_title(); ?></h1>

						<div class="entry-meta-info">

							<div>
								<time class="event-date dtstart" datetime="<?php echo date('c', $starttime); ?>"><?php echo ucfirst( date_i18n( $date_format , $starttime ) ); ?></span>
							</div>
							<div>
								<span class="meta-prep meta-prep-event-time"><?php _e( 'Kl:' , 'neuf' ); ?></span>
								<time class="event-time dtstart" datetime="<?php echo date('G:i', $starttime); ?>"><?php echo date_i18n( 'G.i' , $starttime); ?></time> 
								<span class="meta-sep meta-sep-event-price"> - </span>
								<span class="meta-prep meta-prep-price">CC: </span>
								<span class="price"><?php echo ($price = neuf_get_price( $post )) ? $price : "Gratis"; ?></span>
								<span class="meta-prep meta-prep-price"><?php echo $ticket ? ' <a href="'.$ticket.'">Kjøp billett</a>' : ""; ?></span>
							</div>
							<div>
								<span class="venue location"><?php echo $post->neuf_event_venue; ?></span>
								<?php if(get_post_meta( get_the_ID() , '_neuf_events_fb_url', true )): ?><span class="meta-sep meta-sep-event-facebook"> - </span>
								<span class="event-facebook"><a href="<?php echo get_post_meta( get_the_ID() , '_neuf_events_fb_url', true ); ?>" title="Arrangementet på Facebook"><img src="<?php echo get_bloginfo('stylesheet_directory')."/img/facebook-icon.png";?>" /></a></span>
								<?php endif; ?>
							</div>
						</div>
					</header> <!-- .entry-meta-->

