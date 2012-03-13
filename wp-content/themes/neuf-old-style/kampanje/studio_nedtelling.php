<?php

$month = date("n");
$month_day = date("j");

if(
    ($month == 5 && $month_day >= 20) // starter 20.5
 || ($month > 5 && $month <= 8)       // kjører i intervalet mnd 6 - 8
)
{
	/* dato for studio */
	$startdate = "2011-08-15";
	$tid = strtotime($startdate) - time();

	if($tid > 0)
	{
		$studio_dager = intval($tid / (60*60*24));
		$tid %= 60 * 60 * 24;

		$studio_timer = intval($tid / (60 * 60));
		$tid %= 60 * 60;

		$studio_minutter = intval($tid / 60);
		$tid %= 60;

		$studio_sekunder = $tid;

		$lst = array();
		if($studio_dager > 0)
		{
			$str = $studio_dager . " dag";
			$str .= ($studio_dager != 1)?"er":"";
			$lst[] = $str;
		}
	
		if($studio_timer > 0)// || $studio_dager > 0)
		{
			$str = $studio_timer . " time";
			$str .= ($studio_timer != 1)?"r":"";
			$lst[] = $str;;
		}

		if($studio_minutter > 0)// || $studio_timer > 0 || $studio_dager > 0)
		{
			if($studio_minutter == 1)
			{
				$lst[] = "ett minutt";
			}
			else
			{
				$str = $studio_minutter . " minutt";
				$str .= ($studio_minutter != 1)?"er":"";
				$lst[] = $str;
			}
		}

		//if(studio_sekunder > 0)
		//{
			$lst[] =  $studio_sekunder . " sekunder" ;
		//}
	
		$str = "";
		if(count($lst) > 1)
		{
			$str .= array_shift($lst);

			while(count($lst) > 1)
				$str .= ', ' . array_shift($lst);

			$str .= ' og ';
		}
	
		$gjenstaaende = $str . array_shift($lst) . " igjen til ";
		$etterlogo = '';
	}
	else
	{
		$gjenstaaende = '';
		$etterlogo = ' har begynt!!';
	}
?>
<style type="text/css">
#studio_countdown
{
	float: left;
	border: 1px solid rgb(180, 180, 180);
	width: 950px;
	margin-top: 25px;
	margin-bottom: 0px;
	padding: 25px 25px;
	background: url('images/studio_2011_nedteller_bg.png') repeat scroll 0pt bottom #e5e1d2;
	-webkit-border-top-left-radius: 4px;
	-webkit-border-top-right-radius: 4px;
	-moz-border-radius-topleft: 4px;
	-moz-border-radius-topright: 4px;
	border-top-left-radius: 4px;
	border-top-right-radius: 4px;
	-moz-box-shadow: 0pt 1px 15px rgba(0, 0, 0, 0.25);
	-webkit-box-shadow: 0pt 1px 15px rgba(0,0,0,0.25);
	box-shadow: 0pt 1px 15px rgba(0,0,0,0.25);
	z-index:9999;
}
	

#content
{
	-moz-border-radius: 0px 0px 4px 4px !important;
	-webkit-border-radius:0px 0px 4px 4px !important;
	border-radius: 0px 0px 4px 4px !important;
	margin-top:0px !important;
	border-top:0px;
}

#studio_countdown p
{
	color:#646567;
	color:white;
	text-shadow:black 0 0 10px;
	font-size: 150%;
	text-align: center;
	margin:0px;
	position:relative;
	z-index:99999;
}

#studio_countdown img
{
	width: 189px;
	z-index: 9999;
	position: absolute;
	top:-79px;
	left:-51px;
	border:0px;
}

#studio_countdown span
{
	text-decoration:none;
}

/*#studio_countdown span:hover
{
	text-decoration:underline;
}*/

</style>
	<a href="http://studio.studentersamfundet.no" title="Studentfestihvalen i Oslo starter 17. august!">
<div id="studio_countdown">
		<p>
			<span id="tidtilstudio"><?php echo $gjenstaaende ?></span> STUDiO
			<span id="ettertidtilstudio"><?php echo $etterlogo ?></span>
			<img src="images/studio_2011_logo.png" 
alt="STUDiO" title="Studentfestihvalen i Oslo starter 17. august!" />
		</p>
</div>
	</a>
<?php if($tid > 0): ?>
<script type="text/javascript">
var studio_dager = <?php echo $studio_dager ?>;
var studio_timer = <?php echo $studio_timer ?>;
var studio_minutter = <?echo $studio_minutter ?>;
var studio_sekunder = <?echo $studio_sekunder ?>;

function studio_tick(){
	if(--studio_sekunder < 0)
	{
		studio_sekunder += 60;
		
		if(--studio_minutter < 0)
		{
			studio_minutter += 60;
			
			if(--studio_timer < 0)
			{
				studio_timer += 24;
				studio_dager --;
			}
		}
	}
	else if(studio_sekunder + studio_minutter + studio_timer + studio_dager <= 0)
	{
		clearInterval(studio_interval);
		
		document.getElementById("tidtilstudio").textContent = "";
		document.getElementById("ettertidtilstudio").textContent = "har begynt!!";
		
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	  	ga.src = 'http://www.cornify.com/js/cornify.js';
	    	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);

		setInterval("cornify_add()", 500);
		setTimeout("cornify_replace()", 100);
		document.getElementById("studio_countdown").style.position = "relative";
		return;
	}
	
	studio_update();
}

function studio_update()
{
	var lst = Array();
	if(studio_dager > 0)
	{
		str = studio_dager + " dag"
		str += (studio_dager != 1)?"er":"";
		lst.push(str);
	}
	
	if(studio_timer > 0)// || studio_dager > 0)
	{
		str = studio_timer + " time";
		str += (studio_timer != 1)?"r":"";
		lst.push(str);
	}
	
	if(studio_minutter > 0)// || studio_timer > 0 || studio_dager > 0)
	{
		if(studio_minutter == 1)
		{
			lst.push("ett minutt");
		}
		else
		{
			str = studio_minutter + " minutt";
			str += (studio_minutter != 1)?"er":"";
			lst.push(str);
		}
	}
	
	//if(studio_sekunder > 0)
	//{
		lst.push( studio_sekunder + " sekunder" );
	//}
	
	str = "";
	if(lst.length > 1)
	{
		str = lst.shift();
	
		while(lst.length > 1)
			str += ", " + lst.shift();
	
		str += " og " ;
	}
	str += lst.shift() + " igjen til ";
	
	document.getElementById("tidtilstudio").textContent = str;
}

var studio_interval = setInterval(studio_tick, 1000);
</script>
<?php endif; ?>

<?php

} /* intervalet */

?>
