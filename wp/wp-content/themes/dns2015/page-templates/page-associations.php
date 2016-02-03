<?php
/*
 * Template Name: Associations
 */

$args = array(
    'post_type'      => 'association',
    'posts_per_page' => 50,
);
$associations = new WP_Query( $args );
?>

<?php get_header(); ?>
<div id="content">
	<div class="associations">
        <article <?php neuf_post_class(); ?>>
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <ul class="associations--list">
            <?php the_content(); ?>
            <?php if( $associations->have_posts() ):  while( $associations->have_posts() ): $associations->the_post(); ?>
                <li class="associations--list--item">
                    <div class="associations--list--item--inner">
                        <h2 class="associations--list--item--title"><a href="<?php the_permalink(); ?>"> <?php echo $post->post_title; ?></a></h2>
                        <?php if( has_post_thumbnail() ): ?>
                            <a href="<?php the_permalink(); ?>"><?php echo get_the_post_thumbnail( $post->ID, 'association-thumb' ); ?></a>
                        <?php endif; ?>
                        <?php the_excerpt(); ?>
                    </div>
                </li>
            <?php endwhile; endif; ?>
            </ul>
        </article>
	</div>
</div> <!-- #content -->

<?php get_footer(); ?>
