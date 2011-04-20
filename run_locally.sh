#!/bin/bash
USERNAME=`whoami` #change this if local user  != dns-user.

# no wp-config.php, then scp it from dns.
if [ ! -f "wp-config.php" ]; then
        scp $USERNAME@tera.neuf.no:/var/www/nintendo.neuf.no/www/config/wp-config.php .
        # TODO: fikse her
fi
# if the local mysqld is responding:
#mysqlimport with username and password from wp-config.php

