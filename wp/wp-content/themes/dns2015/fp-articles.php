<section class="front-page--articles hfeed">

	<h2><a href="/nyheter/"><?php _e('News'); ?></a></h2>
    <?php
    $fp_articles = new WP_Query( 'posts_per_page=4' );
    if ( $fp_articles->have_posts() ) : while ( $fp_articles->have_posts() ) : $fp_articles->the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
            <div class="article-image"><a class="permalink" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'six-column-promo' ); ?></a></div>
            <div class="article-content">
                <h3 class="entry-title"><a class="permalink" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                <span class="entry-published-datetime"><?php echo get_the_date(); ?> <?php the_time(); ?></span>
                <span class="entry-summary"><?php echo linkify(trim_excerpt(get_the_excerpt(), 28), '/\[\.\.\.\]/', get_permalink()); ?></span>
            </div>
		</article>

	<?php endwhile; endif; ?>
</section>
