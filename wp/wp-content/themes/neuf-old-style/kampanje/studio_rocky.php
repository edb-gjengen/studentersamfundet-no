<?php
/*
 * Promotering av rocky skuespillet som har solgt litt lite billetter
 * Vises frem til 8 november, siste forestilling er den 7?
 *
 * laget av Øyvind Bakkeli (olivaq@studentersamfundet.no)
 */
list($month, $day) = explode('-', date('n-j'));

if(isset($_GET['showrocky']) && ($month == 10 || ($month == 11 && $day < 8)))
{
?>
<style type="text/css">
#rockytag
{
	padding:5px;
}
#rockytag, #rockytag a
{
	background-color:red;
	color:white;
	font-weight:bolder;
	text-decoration:none;
	text-align:center;
	font-family:arial;
}
#rockytag a:hover
{
	text-decoration:underline;
}
#rockytag img
{
	border:0px;
}
</style>
<div id="rockytag">
<a href="http://studentersamfundet.no/vis.php?ID=4004">
Rocky - The Big Payback - Teater p&aring; Betong 6. og 7. november!</a>
<a href="http://bit.ly/rockybilletter"> <img alt="Billettservice" src="http://studentersamfundet.no/bilder/logo_billettservice.gif"></a>
</div>
<?php
}
