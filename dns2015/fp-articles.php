<section class="front-page--articles hfeed">

	<h2><a href="<?php echo home_url('/aktuelt/'); ?>"><?php _e('Articles', 'neuf'); ?></a></h2>
    <?php
    $fp_articles = new WP_Query( array('posts_per_page' => 4, 'ignore_sticky_posts' => 1) );
    if ( $fp_articles->have_posts() ) : while ( $fp_articles->have_posts() ) : $fp_articles->the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
            <div class="article-image"><a class="permalink" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'six-column' ); ?></a></div>
            <div class="article-content">
                <h3 class="entry-title"><a class="permalink" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                <span class="vcard author microformat-invisible"><span class="fn"><?php the_author_link(); ?></span></span>
                <time class="entry-published-datetime published" datetime="<?php the_time('c'); ?>"><?php echo get_the_date(); ?> <?php the_time(); ?></time>
                <time class="updated microformat-invisible" datetime="<?php the_modified_date('c'); ?>"><?php the_modified_date(); ?></time>
                <span class="entry-summary"><?php echo linkify(trim_excerpt(get_the_excerpt(), 28), '/\[\.\.\.\]/', get_permalink()); ?></span>
            </div>
		</article>

	<?php endwhile; endif; ?>
</section>
