<?php
/**
 * Template Name: Ninjanewsletter
 */
$params = http_build_query($_GET);
$utfpage = file_get_contents(get_home_url()."/nyhetsbrev/?$params");
$utfpage = str_replace("charset=UTF-8","charset=ISO-8859-1", $utfpage);
$isopage = utf8_decode($utfpage);
header("Content-Type: text/plain; charset=ISO-8859-1");
echo $isopage;
?>
