<nav id="menu" class="grid_6">

    <?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'container' => 'false' ) ); ?>

</nav> <!-- #menu -->

<nav id="secondary-menu"> 
    <?php wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'container' => 'false' ) ); ?>
    <form>
    <input type="text" name="s" placeholder="Søk">
    </form>

</nav> <!-- #secondary-menu -->
