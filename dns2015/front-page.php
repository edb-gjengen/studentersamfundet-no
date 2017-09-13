<?php get_header(); ?>

<div id="content">
    <?php get_template_part('fp-featured'); ?>
    <?php //get_template_part('program', '6days'); ?><!-- TODO: Featured events? -->
    <div class="front-page--wrapper">
        <div class="front-page--right">
            <?php get_template_part('fp-events'); ?>
        </div>
        <div class="front-page--left">
            <section class="front-page--newsletter"><?php get_template_part('part-newsletter-signup-form'); ?></section>
            <?php get_template_part('fp-articles'); ?>
        </div>
    </div>
</div> <!-- #content -->
<?php get_footer(); ?>
