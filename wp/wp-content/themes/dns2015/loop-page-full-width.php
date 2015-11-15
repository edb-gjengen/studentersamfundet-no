<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
<div class="full-width-container">
	<article <?php neuf_post_class(); ?>>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->
			<?php display_social_sharing_buttons(); ?>
	</article> <!-- .hentry -->
</div>
<?php endwhile; endif; ?>
