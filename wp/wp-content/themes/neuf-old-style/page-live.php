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
                        flowplayer("player", "<?php bloginfo('stylesheet_directory');?>/flowplayer-3.2.7.swf");
                    });
            </script> 
        </div> 
        <div id="twitter-widget" class="grid_4">
            <script src="http://widgets.twimg.com/j/2/widget.js"></script>
            <script>
                new TWTR.Widget({
                  version: 2,
                  type: 'search',
                  search: '#dnsgf',
                  interval: 30000,
                  title: 'HashTag #dnsgf',
                  subject: 'Generalforsamling våren 2012',
                  width: 370,
                  height: 400,
                  theme: {
                    shell: {
                      background: '#8ec1da',
                      color: '#ffffff'
                    },
                    tweets: {
                      background: '#ffffff',
                      color: '#444444',
                      links: '#1985b5'
                    }
                  },
                  features: {
                    scrollbar: false,
                    loop: false,
                    live: true,
                    behavior: 'default'
                  }
                }).render().start();
                </script>
		</div> <!-- #twitter-widget -->
	</div>
</div>
<?php get_footer(); ?>
