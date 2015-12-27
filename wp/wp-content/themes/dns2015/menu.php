
<nav id="static-menu">
    <?php wp_nav_menu( array( 'theme_location' => 'static-menu', 'container' => 'false' ) ); ?>
</nav><!-- #static-menu -->

<a href="#" class="menu-toggle"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="12" viewBox="0 0 18 12"><g fill="#ffffff"><path d="M0 0h18v2H0zM0 5h18v2H0zM0 10h18v2H0z"></path></g></svg></a>
<nav id="main-menu" role="navigation">
    <?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'container' => 'false' ) ); ?>
</nav><!-- #main-menu -->
