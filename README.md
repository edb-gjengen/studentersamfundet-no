# Installation
    cd wp
    cp wp-config-sample.php wp-config.php
    vim wp-config.php  # Update database settings
    git submodule update --init
    
# Translations
    # Download wordpress i18n tools, see https://codex.wordpress.org/I18n_for_WordPress_Developers#Using_the_i18n_tools
    # Create a symlink from wp-content to TOOLS_DIR/src
    ln -s $REPO_ROOT/wp/ src
    # Create .pot-file
    php tools/i18n/makepot.php wp-theme src/wp-content/themes/differ2015/ src/wp-content/themes/differ2015/languages/differ2015.pot
    # Open .pot-file with POEdit and start translating

## See also

https://edb.neuf.no/wiki/Studentersamfundet.no-web#Quick_start