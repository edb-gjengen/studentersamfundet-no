<?php 
require( '../../../wp-load.php' );

$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => date( 'U' , strtotime( '-8 hours' ) ), 
	'compare' => '>',
	'type'    => 'numeric'
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => array( $meta_query ),
	'posts_per_page' => 4
);
$events = new WP_Query( $args );
/*  */
define('SITE_ROOT', 'http://studentersamfunnet.no/'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>Ukesprogram for Det Norske Studentersamfund</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <link href="<?php echo SITE_ROOT;?>css/weekprogrammail.css" rel="stylesheet" type="text/css" />
</head>
<body style="font-family: verdana, sans-serif; font-size: 11px; border: none; letter-spacing: 1px; border: none;">
    <div id="all" style="width: 600px; border: none; margin: 0 auto;">

<?php
/* TODO : add edit mode with js */
?>
        <p>
            <img src="<?php echo SITE_ROOT;?>bilder/bannermail.png" alt="Det Norske Studentersamfund" border="0" />
        </p>

<?php
/* three top stories */
?>
    <table class="top">
        <tr style="background: #fff;">
            <?php $counter = 0;
            while ($events->have_posts() && $counter < 3) : $events->the_post(); ?>

            <td id="<?php the_ID(); ?>" style="vertical-align: center;">
                <div>
                    <h2 style="margin: 0px 3px; font-size: 14px;"><?php //echo $gig->name; ?></h2>
                    <p style="margin: 0px 3px; font-style: italic;">
                        <?php //echo strftime("%A %d. %B, %H:%M", strtotime($gig->time)); ?>
                    </p>
                </div>
                <div style="margin-top: 5px; text-align: center; vertical-align: center; heigth: 250px;">
                    <?php //if ($gig->picture): ?>
                        <img src="<?php echo SITE_ROOT;?>imageResize.php?pic=bilder/program/DELETEME<?php //echo $gig->picture;?>&amp;maxwidth=170" alt="" />
                    <?php //endif; ?>
                </div>
                <div>
                    <p style="margin: 5px 3px 0;">
                        <?php //echo $gig->intro; ?>
                    </p>
                    <p style="margin: 5px 3px;">
                        <a href="<?php the_permalink(); ?>">Les mer&raquo;</a>
                    </p>
                </div>
            </td>
            <?php $counter++; ?>
            <?php endwhile; ?>
        </tr>
    </table>

    <div class="clear">&nbsp;</div>
    <div id="middle-section">
      <p id="middle-text">
        <?php //echo $prog->pretext; ?>
      </p>
      <br />
    </div>

    <div id="concert-list">
        <table>
        <tr>

<?php 
/* TODO snipped week table with
    - day as header
    - 3 columns:
        - event picture
        - title with description and read more link.
        - event type, venue, time, price
 */
?>
        </tr>
        </table>
    </div>
		<div id="end-section">
			<br />
			<p id="end-text"><?php //echo $prog->posttext; ?></p>
			<br />
		</div>

		<div id="sponsors" style="text-align: center; border-top: 1px solid black;">
			<p>Vår samarbeidspartner:</p>
			<a href="http://www.akademika.no"><img style="margin: 12px; border: none;" src="<?php echo SITE_ROOT; ?>bilder/sponsorer/akademika.png" alt="Akademika" /></a>
		</div>
		<div id="about" style="text-align: center; border-top: 1px solid black;">
			<p>Det Norske Studentersamfund</p>
			<p><a href="<?php echo SITE_ROOT; ?>">www.studentersamfundet.no</a></p>
			<p>Chateau Neuf, Slemdalsveien 15, 0369 Oslo, tlf: 22 84 45 11</p>  
		</div>
	</div>
</body>
</html>
