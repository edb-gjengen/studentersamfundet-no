<?php
/**
 * Overrides Thematic's doctype.
 *
 * Overrides Thematic's old html4 doctype, and replace it with a new and fancy html5 doctype.
 */
function neuf_doctype($html) {
	$html = "<!DOCTYPE html>\n";
	return $html;
}
add_filter('thematic_create_doctype','neuf_doctype');

/**
 * Constructs the head element.
 *
 * Overriding Thematic's head element, because it references a profile which is not relevant for us. More than this, the profile attribute is not defined for HTML5 either.
 */
function neuf_remove_head_profile($html) {
	$html = "<head>";
	return $html;
}
add_filter('thematic_head_profile','neuf_remove_head_profile');

/**
 * Adds viewport meta tag.
 *
 * Adds a viewport meta tag to the head element.
 */
function neuf_add_viewport() {
	$html = "\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" />\n";
	echo $html;
}
add_action('wp_head','neuf_add_viewport',0);

/**
 * Overrides default stylesheet include.
 *
 * Since we are using SASS, we put our stylesheets in a separate directory for ease of use. This function overrides Thematics default include of the stylesheet.
 */
function neuf_create_stylesheet($content) {
	$content = "\t";
	$content .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"";
	$content .= get_stylesheet_directory_uri();
	$content .= "/stylesheets/main.css";
	$content .= "\" />";
	$content .= "\n\n";

	return $content;
}
add_filter('thematic_create_stylesheet','neuf_create_stylesheet');

/**
 * Displays the body element.
 *
 * Overrides Thematic's function to display the body element. What is different between the two functions, is that this one adds an id "root" to the element. This id is needed for our CSS framework. An alternative would be to a div element with id root inside body, encompassing all other element. This would be cluttering.
 */
function childtheme_override_body() {
	$body_id = "root";

	echo '<body ';
	if ($body_id) {
		echo 'id="';
		echo $body_id;
		echo '" ';
	}
	if (!(THEMATIC_COMPATIBLE_BODY_CLASS)) {
		body_class();
	} else {
		echo 'class="';
		thematic_body_class();
		echo '"';
	}
	echo '>';
}

/**
 * Displays a HTML5 branding section element.
 *
 * Changes Thematic's div element to a section element. See this in icontext with childtheme_override_brandingclose().
 */
function childtheme_override_brandingopen() {
	$html = "\t<section id=\"branding\">";
	echo $html;
}
add_action('thematic_header','childtheme_override_brandingopen',1);

function childtheme_override_blogtitle() { ?>
	    		
			<h1 id="site-title"><a href="<?php bloginfo('url') ?>/" title="<?php bloginfo('name') ?>" rel="home"><?php bloginfo('name') ?></a></h1>
	    		
	    <?php }
add_action('thematic_header','childtheme_override_blogtitle',3);

/**
 * Removes the blog description field from the header.
 */
function neuf_remove_blogdescription() {
	remove_action('thematic_header','thematic_blogdescription',5);
}
add_action('init','neuf_remove_blogdescription');

/**
 * Displays the login form in the header.
 */
function neuf_display_login() { ?>
		<section id="meta">
			<form>
				<input name="username" type="text" placeholder="BRUKERNAVN" />
				<input name="password" type="password" placeholder="PASSORD" />

				<input name="search" type="text" placeholder="SØK" />
			</form>
		</section> <!-- #meta -->
<?php }
add_action('thematic_header','neuf_display_login',8);


/**
 * Displays a HTML5 branding section element.
 *
 * Changes Thematic's div element to a section element. See this in icontext with childtheme_override_brandingopen().
 */
function childtheme_override_brandingclose() {
	$html = "\t</section> <!-- #branding -->\n";
	echo $html;
}
add_action('thematic_header','childtheme_override_brandingclose',7);

/**
 * Configures which menu to display.
 *
 * Overrides Thematic to remove unneded div elements. Wraps the navigation menu in a nav instead of a div.
 */
function childtheme_override_access() { ?>
		<section id="skip-link">
			<a href="#content" title="<?php _e('Skip navigation to the content', 'thematic'); ?>"><?php _e('Skip to content', 'thematic'); ?></a>
		</section><!-- #skip-link -->
<?php
	wp_nav_menu(array(
		'theme_location'  => 'primary-menu',
		'container'       => 'nav'
	));
		
}
//add_action('thematic_header','childtheme_override_access',9);

/**
 * Renames primary menu for our purpose.
 *
 * Our users will probably be norwegian, therefore we translate the menu name from "Primary menu" to "Hovedmeny".
 */
function neuf_primary_menu_name($name) {
	$name = 'Hovedmeny';
	return $name;
}
add_filter('thematic_primary_menu_name','neuf_primary_menu_name');

?>
