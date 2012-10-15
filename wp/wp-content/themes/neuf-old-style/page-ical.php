<?php
/**
 * Template Name: Ical
 */
header("Content-type: text/calendar; charset=utf-8");
echo get_bloginfo('stylesheet_directory')."/cal/studentersamfundet.ics";


