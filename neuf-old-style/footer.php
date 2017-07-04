<?php $kolofon = get_theme_mod( 'footer_kolofon',""); ?>

<footer id="site-footer">
	<div class="container_12">

        <div class="grid_4">
            <div id="footer-about" class="">
                <h2>Det Norske Studentersamfund</h2>
                <p>
                    Studentene i Oslo har sitt naturlige tilholdssted p&aring; Det Norske Studentersamfund, i hyggelige lokaler p&aring; Chateau Neuf &oslash;verst p&aring; Majorstuen. Her er det &aring;pent alle dager unntatt s&oslash;ndag, og enten man &oslash;nsker en tur i baren, p&aring; kaf&eacute;, p&aring; debatt, p&aring; konsert, teater eller kino, har man muligheten p&aring; Det Norske Studentersamfund.
                </p>
                    <span class="links"><a href="<?php bloginfo('url'); ?>/kart/">Kart</a> | <a href="<?php bloginfo('url'); ?>/kontakt/">Kontakt</a></span>

            </div>


        </div>
        <div class="grid_8">
            <div id="sponsors">
                <div id="sponsor-uio" class="sponsor">
                    <a href="http://www.uio.no/" rel="nofollow"><img alt="Universitetet i Oslo eier Chateau Neuf" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/logo_black_uio.png" /></a>
                </div>
                <div id="sponsor-akademika" class="sponsor">
                    <a href="http://www.akademika.no/" rel="nofollow"><img alt="Akademika sponser Studentersamfundet" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/logo_black_akademika.png" /></a>
                </div>
                <div id="sposor-nrf" class="sponsor">
                    <a href="http://www.norskrockforbund.no/" rel="nofollow"><img alt="Norsk Rockforbund sponser Studentersamfundet" src="<?php bloginfo('stylesheet_directory'); ?>/img/sponsors/logo_black_nrf.png" /></a>
                </div>

            </div>
        </div>

	<div id="kolofon" class="grid_12">

        <span><?php echo $kolofon; ?></span>

	</div> <!-- #kolofon -->

	</div>
</footer> <!-- #site-footer -->

<?php wp_footer(); ?>

</body>
</html>
