## Install
    # Get WP-CLI from http://wp-cli.org/
    wp core download --path=wp
    cd wp
    wp core config  # create db first
    wp core install  # needs initial credentials
    cd wp-content/themes
    ln -s ../../../dns2015

    cd ../../../dns2015
    npm install jshint
    npm install
    bower install
    gulp watch

    # Install public plugins listed below
    cd ../wp
    wp plugin install ...
    
    # symlink in own plugins
    cd wp/wp-content/plugins
    ln -s ../../plugins/neuf-associations
    ln -s ../../plugins/neuf-events

    wp core language install nb_NO
    wp core language activate nb_NO
    wp core language update

## Plugins
    debug-bar
    disable-comments
    duplicate-post
    json-api
    redis-cache  # needs to install object-cache.php
    user-role-editor
    wordpress-seo
    wpdirauth

    # own
    neuf-associations
    neuf-events

## Development tasks
    fab build
    fab watch
    fab i18n

## Deployment
    fab deploy