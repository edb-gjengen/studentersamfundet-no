<?php
/**
 * Template Name: Ninjanewsletter
 */

$utfpage = file_get_contents("http://studentersamfundet.no/nyhetsbrev");
$utfpage = str_replace("charset=UTF-8","charset=ISO-8859-1", $utfpage);
$isopage = utf8_decode($utfpage);
header("Content-Type: text/plain; charset=ISO-8859-1");
echo $isopage;
?>
