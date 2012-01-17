<?php get_header(); ?>

		<div id="content" class="single">

			<div class="container_12">

		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

				<div class="hentry">

					<?php the_post_thumbnail( 'large' , array( 'style' => 'display:block;margin:auto;' ) ); ?>

					<h1 class="entry-title"><?php the_title(); ?></h1>
					<div class="entry-meta byline"><span class="meta-prep meta-prep-author">Av </span><span class="author vcard"><?php the_author_link(); ?></span><span class="meta-sep meta-sep-entry-date"> | </span><span class="meta-prep meta-prep-entry-date">Publisert: </span><span class="entry-date"><?php the_time('Y-m-d G:i'); ?></span></div>

					<div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->

					<?php display_social_sharing_buttons(); ?>

					<?php neuf_maybe_display_gallery(); ?>

					<div id="facebook-comments">
						<fb:comments> </fb:comments>
					</div> <!-- #facebook-comments -->

				</div> <!-- .hentry -->

		<?php endwhile; endif; ?>

<?php get_sidebar(); ?>

			</div> <!-- .container_12 -->

		</div> <!-- #content -->

<?php include("footer.php"); ?>
