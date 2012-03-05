
<div id="footer">
        <div id="footer-row1" class="container_12">

            <div id="footer-about" class="grid_4">
                <h2>Det Norske Studentersamfund</h2>
                <p>
                    Studentene i Oslo har sitt naturlige tilholdssted p&aring; Det Norske Studentersamfund, i hyggelige lokaler p&aring; Chateau Neuf &oslash;verst p&aring; Majorstuen. Her er det &aring;pent alle dager unntatt s&oslash;ndag, og enten man &oslash;nsker en tur i baren, p&aring; kaf&eacute;, p&aring; debatt, p&aring; konsert, teater eller kino, har man muligheten p&aring; Det Norske Studentersamfund.
                </p>
                    <span class="links"><a href="<?php bloginfo('url'); ?>/kart/">Kart</a> | <a href="<?php bloginfo('url'); ?>/kontakt/">Kontakt</a></span>
                
            </div> <!-- #footer-about -->

            <div id="fb-like-box" class="grid_4">
                <div class="fb-like-box" data-href="https://www.facebook.com/studentersamfundet" data-width="315" data-height="285" data-show-faces="true" data-stream="false" data-header="false"></div>
            </div>
            <div class="grid_4">
                <h2><a href="https://twitter.com/dns1813">Fra Twitter</a></h2>
                <div id="twitter_feed"></div> <!-- #twitter_feed -->
                <h2><a href='http://www.flickr.com/groups/neuf/pool/'>Bilder fra flickr</a></h2>
                <div id="flickr-images">
                    <?php neuf_flickr_images('limit=10&type=group&groupid=1292860@N21'); ?>
                </div>
            </div> 

        </div>

        <div id="footer-row2" class="container_12">
            <div id="sponsors">

                <div id="sponsor-uio" class="sponsor">
                        <a href="http://www.uio.no/" rel="nofollow"><img alt="Universitetet i Oslo eier Chateau Neuf" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/logo_uio86.png" /></a>
                </div>

                <div id="sponsor-studio" class="sponsor">
                        <a href="http://studio.studentersamfundet.no/" rel="nofollow"><img alt="Studentfestivalen i Oslo har base på Chateau Neuf" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/studio_logo_gronn-122x86.png" /></a>
                </div>

                <div id="sponsor-studenthovedstaden" class="sponsor">
                        <a href="http://www.studenthovedstaden.no/" rel="nofollow"><img alt="Studenthovedstaden sponser Studentersamfundet" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/logo_sh86.png" /></a>
                </div>

                <div id="sposor-nrf" class="sponsor">
                <a href="http://www.norskrockforbund.no/" rel="nofollow"><img alt="Norsk Rockforbund sponser Studentersamfundet" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/nrf-86x86.png" /></a>
                </div>

                <div id="sponsor-akademika" class="sponsor">
                        <a href="http://www.akademika.no/" rel="nofollow"><img alt="Akademika sponser Studentersamfundet" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/akademika.png" /></a>
                </div>

            </div>
        </div>

	<div id="kolofon" class="container_12">

            <span class="credits grid_12">Det Norske Studentersamfund | Slemdalsveien 15, 0369 Oslo | Webdesign av <a href="http://edb.neuf.no">EDB-gjengen</a> og <a href="#">Designerne</a> i KAK</span>

	</div> <!-- #kolofon -->

	<!-- Google Analytics -->
	<script type="text/javascript">var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www."); document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));</script><script type="text/javascript">try {var pageTracker = _gat._getTracker("UA-52914-1");pageTracker._trackPageview();} catch(err) {}</script>
	<!-- end Google Analytics -->

</div> <!-- #footer -->

<?php wp_footer(); ?>

</body>
</html>
