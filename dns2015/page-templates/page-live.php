<?php
/*
 * Template Name: Live (streaming)
 */
?>
<?php get_header(); ?>
<div id="content" class="container_12">

<?php while ( have_posts()) : the_post(); ?>

    <div class="grid_12"><h1><?php the_title(); ?></h1><?php the_content(); ?></div>
<?php endwhile; ?>

        <div id="flowplayer" class="grid_8" style="background-color:black; color:white;margin-bottom:20px;">
            <!-- Stream URL -->
            <a href="http://porter.streaming.neuf.no:8800/live/" style="width:792px;height:432px;display:block;" id="player"></a>
            <script src="<?php bloginfo('stylesheet_directory'); ?>/flowplayer-3.2.6.min.js"></script>
            <script type="text/javascript"> 
            $(function(){
                flowplayer(
                    "player",
                    "<?php bloginfo('stylesheet_directory');?>/flowplayer-3.2.7.swf"
                );
            });
            </script> 
        </div> 
        <div id="twitter-widget" class="grid_4">
                <!-- TODO -->
        </div>
</div>
<?php get_footer(); ?>
