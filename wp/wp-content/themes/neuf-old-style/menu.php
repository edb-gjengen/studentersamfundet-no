<?php $opening_hours = get_theme_mod( 'header_opening_hours',""); ?>
<nav id="menu" class="grid_6">
    <?php if($opening_hours != false) { ?>
    <ul class="aapningstider">
        <li><a href="/aapningstider/">Åpningstider &#9662;</a>
            <ul>
                <li>
                    <!-- wp-admin/ -> Theme -> Customize -> Header opening hours -->
                    <?php echo $opening_hours; ?>
                </li>
            </ul>
        </li>
    </ul> <!-- .aapningstider -->
    <?php  } ?>

    <?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'container' => 'false' ) ); ?>

</nav> <!-- #menu -->

<nav id="secondary-menu"> 

    <?php wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'container' => 'false' ) ); ?>

</nav> <!-- #secondary-menu -->
