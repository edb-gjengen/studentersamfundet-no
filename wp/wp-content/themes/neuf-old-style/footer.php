
<footer id="site-footer">
	<div class="container_12">
        <div class="grid_12">

            <div id="footer-about" class="grid_4 alpha">
                <h2>Det Norske Studentersamfund</h2>
                <p>
                    Studentene i Oslo har sitt naturlige tilholdssted p&aring; Det Norske Studentersamfund, i hyggelige lokaler p&aring; Chateau Neuf &oslash;verst p&aring; Majorstuen. Her er det &aring;pent alle dager unntatt s&oslash;ndag, og enten man &oslash;nsker en tur i baren, p&aring; kaf&eacute;, p&aring; debatt, p&aring; konsert, teater eller kino, har man muligheten p&aring; Det Norske Studentersamfund.
                </p>
                    <span class="links"><a href="<?php bloginfo('url'); ?>/kart/">Kart</a> | <a href="<?php bloginfo('url'); ?>/kontakt/">Kontakt</a></span>
                
            </div> <!-- #footer-about -->

            <div id="fb-like-box" class="grid_4">
                <div class="fb-like-box" data-href="https://www.facebook.com/studentersamfundet" data-width="315" data-height="285" data-show-faces="true" data-stream="false" data-header="false" data-border-color="#e89735"></div>
            </div>
            <div id="flickr-box" class="grid_4 omega">
                <h2><a href='http://www.flickr.com/groups/neuf/pool/'>Bilder fra flickr</a></h2>
                <div id="flickr-images">
                    <ul id="flickr_feed"></ul>
                </div>
            </div> 
        </div>

        <div class="grid_12">
            <div id="sponsors">

                <div id="sponsor-uio" class="sponsor">
                        <a href="http://www.uio.no/" rel="nofollow"><img alt="Universitetet i Oslo eier Chateau Neuf" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/logo_black_uio.png" /></a>
                </div>

                <div id="sposor-nrf" class="sponsor">
                <a href="http://www.norskrockforbund.no/" rel="nofollow"><img alt="Norsk Rockforbund sponser Studentersamfundet" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/logo_black_nrf.png" /></a>
                </div>

                <div id="sponsor-akademika" class="sponsor">
                        <a href="http://www.akademika.no/" rel="nofollow"><img alt="Akademika sponser Studentersamfundet" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/logo_black_akademika.png" /></a>
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
