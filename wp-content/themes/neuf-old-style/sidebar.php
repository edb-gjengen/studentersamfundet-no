            <div id="primary" class="sidebar">

                <ul class="xoxo">

		<?php // if is a single event, then display event info box ?>
		<?php if ( is_single() && 'event' == get_query_var( 'post_type' ) ) : ?>

                    <?php $counter = 1; ?>

                    <li id="info-<?php echo($post->id) ?>" class="widget info">
                        <h3>Info</h3>
                        <ul>
                        <?php if ( isset($post->date) ) {?>
                            <li class="<?php if( $counter++%2 == 0 ) echo(" alt"); ?>"><div class="term">Tid:</div><div class="definition"><?php echo(date("j. M Y G:i",strtotime($post->date))) ?></div></li>
                        <?php } ?>
                        <?php if ( isset($post->place) ) { ?>
                            <li class="<?php if( $counter++%2 == 0 ) echo(" alt"); ?>"><div class="term">Sted:</div><div class="definition"><a href="lokaler.php?lokale=<?php echo($post->place); ?>"><?php echo($post->place); ?></a> (<a href="http://maps.google.com/maps?q=studentersamfundet+i+oslo&amp;hl=no">Kart</a>)</div></li>
                        <?php } ?>
                        <?php if ( isset($post->regular_price) ) { ?>
                            <li class="<?php if( $counter++%2 == 0 ) echo(" alt"); ?>"><div class="term">Pris:</div><div class="definition"><?php echo($post->regular_price); ?>,-</div></li>
                        <?php } ?>
                        <?php if ( isset($post->member_price) ) { ?>
                            <li class="<?php if( $counter++%2 == 0 ) echo(" alt"); ?>"><div class="term">Medlem:</div><div class="definition"><?php echo($post->member_price); ?>,-</div></li>
                        <?php } ?>
                        <?php if ( isset($post->ticket_url) && strcmp($post->ticket_url,"") != 0 && strcmp($post->ticket_url,"http://") != 0 ) { ?>
                            <li class="<?php if( $counter++%2 == 0 ) echo(" alt"); ?>"><div class="term">Billetter</div><div class="definition"><a href="<?php echo($post->ticket_url); ?>"><img class="ticket-link" src="<?php bloginfo('image-root'); ?>/bilder/logo_billettservice2.gif" /></a></div></li>
                        <?php } ?>
    
                        <?php if ( isset($post->facebook_url) && strcmp($post->facebook_url,"") != 0 ) { ?>
                                <li class="<?php if( $counter++%2 == 0 ) echo(" alt"); ?>"><div class="term">Facebook</div><div class="definition"><a href="<?php echo($post->facebook); ?>"><img class="facebook-link" src="<?phpbloginfo('image-root'); ?>/bilder/facebook_prog.jpg" /></a></div></li>
                        <?php } ?>
                            <li class="<?php if( $counter++%2 == 0 ) echo(" alt"); ?>"><div class="term"><img id="calendar-link" src="<?php bloginfo('image-root'); ?>/bilder/calendar.gif" alt="Kalender" /></div><div class="definition"><a href="vcal.php?id=<?php echo $_GET['ID'];?>">Importer til min kalender (vcal-fil)</a></div></li>
                        <?php if ( isset($post->division) ) { ?>
                            <li class="<?php if( $counter++%2 == 0 ) echo(" alt"); ?>"><div class="term">Arrang&oslash;r:</div><div class="definition"><a href="foreninger.php?id=<?php echo($post->division_id); ?>"><?php echo($post->division); ?></a></div></li>
                        <?php } ?>
                        </ul>
                    </li>

                     <li id="internal-ad-sidebar-insert" class="widget internal-ad">
                        <ul>
                            <li>
                                <a class="fancybox" href="<?php bloginfo('url'); ?>/stillinger.php?forening=<?php echo($post->division_nicename); ?>">Bli aktiv i <br /><?php echo($post->division); ?></a>
                            </li>
                        </ul>
                    </li>
                <?php endif; // endif is single event ?>

<?php /*
                    <li class="widget">
                        <?php random_gig(); ?>
                    </li>
*/ ?>

<?php // Let's desplay some events ?>
                    <li class="widget events">
                        <h3>Arrangementer</h3>
                        <ul>
                            <?php $sidebar_posts = get_posts('post_type=event&numberposts=4&sort=random'); foreach( $sidebar_posts as $post) : setup_postdata( $post ); ?>
                            <li class="event">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail(); ?>
                                    <?php else : ?>
                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/img/placeholder.png" alt="" /> 
                                    <?php endif; ?>
                                </a>
                                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>

<?php // Let's display some news ?>

<?php // TODO upgrade this widget to WordPress
/*
                    <li class="widget news">
                        <h3>Nyheter</h3>
                        <ul>
                            <?php load_posts('posts_per_page=4'); foreach($posts as $post) : ?>
                            <li class="article">
                                <a href="<?php bloginfo('url'); ?>/nyhet.php?ID=<?php echo($post->id); ?>">
                                    <?php if ( $post->attachments && strcmp($post->attachments[0]['picture'],"") != 0 ) : ?>
                                    <img src="<?php bloginfo('image-root'); ?>/imageResize.php?pic=bilder/nyheter/<?php echo($post->attachments[0]['picture']); ?>&amp;maxwidth=282&amp;maxheight=143&amp;crop=1" alt="" />
                                    <?php else : ?>
                                    <img src="<?php bloginfo('image-root'); ?>/imageResize.php?pic=bilder/placeholder.png&amp;maxwidth=282&amp;maxheight=143&amp;crop=1" alt="" /> 
                                    <?php endif; ?>
                                </a>
                                <h4><a href="<?php bloginfo('url'); ?>/nyhet.php?ID=<?php echo($post->id); ?>"><?php echo($post->title) ?></a></h4>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
 */ ?>



                    <!--li class="widget">
                        <h3>Sidebar element</h3>
                        <ul>
                            <li><a href="#">Link</a></li>
                            <li><a href="#">Link</a></li>
                            <li><a href="#">Link</a></li>
                        </ul>
                    </li>
                    <li class="widget">
                        <h3>Sidebar element</h3>
                        <ul>
                            <li><a href="#">Link</a></li>
                            <li><a href="#">Link</a></li>
                            <li><a href="#">Link</a></li>
                        </ul>
                    </li-->


                </ul>


            </div> <!-- #primary.sidebar -->

