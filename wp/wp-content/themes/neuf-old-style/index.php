<?php get_header(); ?>

		<div id="content" class="container_12">

			<h1 class="page-title grid_12"><?php neuf_page_title(); ?></h1>

		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
			
			<article <?php neuf_post_class(); ?>>

				<div class="grid_6">

					<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                          <div class="entry-meta byline"><span class="meta-prep meta-p rep-author">av </span><span class="author vcard"><?php the_author_link(); ?></span>, <span class="entry-date"><?php the_date('l d. M Y'); ?> kl <?php the_time ('H.i'); ?></span></div>


					<div class="entry-content"><?php the_excerpt(); ?></div> <!-- .entry-content -->

				</div> <!-- .grid_6 -->

				<div class="grid_6">

					<?php the_post_thumbnail( 'six-column-slim'); ?>

				</div> <!-- .grid_6 -->

			</article> <!-- .post -->

		<?php endwhile; endif; ?>


<?php posts_nav_link(); ?>

</div> <!-- #content -->

<?php get_footer(); ?>
