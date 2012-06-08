<?php 
require( '../../../wp-load.php' );


$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => array(date( 'U' , strtotime( '-8 hours' )), date( 'U' , strtotime( '+1 week' ))),
	'type' => 'numeric',
	'compare' => 'BETWEEN'
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => array($meta_query),
	'posts_per_page' => 150,
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_neuf_events_starttime',
	'order'          => 'ASC'
);

$events = new WP_Query( $args );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://ww=
w.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <title>Det Norske Studentersamfund - Nyhetsbrev</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <style type="text/css">
        </style>
</head>
<body rightmargin="0" topmargin="0" bottommargin="0" style="margin:0;" bgcolor="#ffffff" leftmargin="0">
<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
    <tr>
        <td width="50%">
            <table width="640" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                <tr>
                    <td style="background-color:#e99835;"><img src="<?php bloginfo('template_directory'); ?>/img/logo-web.png" alt="Det Norske Studentersamfund" /></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
