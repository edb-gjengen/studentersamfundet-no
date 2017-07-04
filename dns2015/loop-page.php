<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

	<article <?php neuf_post_class(); ?>>
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <span class="vcard author microformat-invisible"><span class="fn"><?php the_author_link(); ?></span></span>
        <time class="published microformat-invisible" datetime="<?php the_time('c'); ?>"><?php the_time('c'); ?></time>
        <time class="updated microformat-invisible" datetime="<?php the_modified_date('c'); ?>"><?php the_modified_date('c'); ?></time>
        <?php the_post_thumbnail( 'large' , array( 'style' => 'display:block;margin:auto;' ) ); ?>
        <p class="wp-caption-text gallery-caption"><?php echo get_post( get_post_thumbnail_id() )->post_excerpt; ?></p>

        <div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->

	</article> <!-- .hentry -->

<?php endwhile; endif; ?>
