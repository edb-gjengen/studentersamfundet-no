<footer id="site-footer">
    <div class="footer-wrap">
        <div class="footer--about">
            <p><?php echo get_theme_mod('footer_about', 'Studentene i Oslo har sitt naturlige tilholdssted p&aring; Det Norske Studentersamfund, i hyggelige lokaler p&aring; Chateau Neuf &oslash;verst p&aring; Majorstuen. Her er det &aring;pent alle dager unntatt s&oslash;ndag, og enten man &oslash;nsker en tur i baren, p&aring; kaf&eacute;, p&aring; debatt, p&aring; konsert, teater eller kino, har man muligheten p&aring; Det Norske Studentersamfund.'); ?></p>
        </div>
        <div class="footer--social-media">
            <ul>
                <li><a href="https://www.facebook.com/studentersamfundet" class="social-media-link"><?php include(get_stylesheet_directory()."/dist/images/icons/facebook.svg"); ?></a></li>
                <li><a href="https://twitter.com/dns1813" class="social-media-link"><?php include(get_stylesheet_directory()."/dist/images/icons/twitter.svg"); ?></a></li>
                <li><a href="https://instagram.com/studentersamfundet/" class="social-media-link"><?php include(get_stylesheet_directory()."/dist/images/icons/instagram.svg"); ?></a></li>
                <li><a href="https://www.flickr.com/groups/neuf/pool/" class="social-media-link"><?php include(get_stylesheet_directory()."/dist/images/icons/flickr.svg"); ?></a></li>
            </ul>
        </div>
    </div>
    <div class="footer--kolofon">
        <span><?php echo get_theme_mod('footer_kolofon', 'Chateau Neuf - Det Norske Studentersamfund | Slemdalsveien 15, 0369 Oslo | Ansvarlig redaktør: Rein Amundsen'); ?></span>
		<span><?php if( !is_user_logged_in() ): ?>
			<a href="<?php echo wp_login_url(); ?>" title="<?php _e('Log in'); ?>"><?php _e('Log in'); ?></a>
		<?php else: ?>
			<a href="<?php echo wp_logout_url(); ?>"><?php _e('Log out');?></a>
		<?php endif; ?></span>
    </div>
</footer> <!-- #site-footer -->

<?php wp_footer(); ?>
<!-- https://www.browser-update.org/ -->
<script>
    var $buoop = {vs:{i:9,f:25,o:12.1,s:7},c:2};
    function $buo_f(){
        var e = document.createElement("script");
        e.src = "//browser-update.org/update.min.js";
        document.body.appendChild(e);
    }
    try {document.addEventListener("DOMContentLoaded", $buo_f,false)}
    catch(e){window.attachEvent("onload", $buo_f)}
</script>
</body>
</html>
