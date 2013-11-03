<?php get_header(); ?>
<div id="content" class="container_12">

<?php while ( have_posts()) : the_post(); ?>

    <div class="grid_12"><h1><?php the_title(); ?></h1><?php the_content(); ?></div>
<?php endwhile; ?>

        <div id="flowplayer" class="grid_8" style="background-color:black; color:white;margin-bottom:20px;">
            <!-- Stream URL -->
            <div id="player" style="width:900px;height:576px;"></div> 
            <script src="<?php bloginfo('stylesheet_directory'); ?>/flowplayer-3.2.6.min.js"></script>
            <script type="text/javascript"> 
            $(function(){
                flowplayer(
                    "player",
                    "<?php bloginfo('stylesheet_directory');?>/js/flowplayer/flowplayer-3.2.15.swf",
                    {
                        clip: {
                            url: 'genfors_nov13',
                            live: true,
                            // configure clip to use neuf as our provider, it uses our rtmp plugin
                            provider: 'neuf'
                        },
                     plugins: {
                        // here is our rtmp plugin configuration
                        neuf: {
                            url: "<?php bloginfo('stylesheet_directory');?>/js/flowplayer/flowplayer.rtmp-3.2.12.swf",
                     
                            // netConnectionUrl defines where the streams are found
                            netConnectionUrl: 'rtmp://gemini.neuf.no/live'
                        }
                    }
                });
            });
            </script> 
        </div> 
        <div id="twitter-widget" class="grid_4">
		<!-- TODO -->
	</div>
</div>
<?php get_footer(); ?>
