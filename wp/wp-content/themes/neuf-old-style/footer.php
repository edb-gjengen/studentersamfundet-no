
<footer id="site-footer">
	<div class="container_12">
        <div class="grid_12">

            <div id="footer-about" class="grid_4 alpha">
                <h2><?php bloginfo('name'); ?></h2>
                <p>
                <?php 
                $page=get_page_by_title('Forside'); // Note: Front page must be named 'Forside'
                echo get_post_meta( $page->ID, 'Footer About', true); ?>
                </p>
            </div> <!-- #footer-about -->

            <div id="fb-like-box" class="grid_4">
                <div class="fb-like-box" data-href="https://www.facebook.com/betongoslo" data-width="315" data-height="350" data-show-faces="true" data-stream="false" data-header="false" data-colorscheme="dark" data-border-color="#ffffff"></div>
            </div>
            <div id="flickr-box" class="grid_4 omega">
                <h2><a href='http://www.flickr.com/photos/studentenesfotoklubb/tags/betong' target="_blank">Bilder fra flickr</a></h2>
                <div id="flickr-images">
                    <ul id="flickr_feed"></ul>
                </div>
            </div> 
        </div>

	<div id="kolofon" class="grid_12">

            <span>Det Norske Studentersamfund | Slemdalsveien 15, 0369 Oslo | Webdesign av <a href="http://edb.neuf.no">EDB-gjengen</a> og Designerne i <a href="http://studentersamfundet.no/association/kommunikasjonsavdelingen/">KAK</a>.</span>

	</div> <!-- #kolofon -->

	<!-- Google Analytics -->
        <script type="text/javascript">
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-52914-1']);
          _gaq.push(['_setDomainName', 'studentersamfundet.no']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();
        </script>
	<!-- end Google Analytics -->
	</div>
</footer> <!-- #site-footer -->

<?php wp_footer(); ?>

</body>
</html>
