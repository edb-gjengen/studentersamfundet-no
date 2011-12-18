<?php
$forestillinger = array(
		1298574000=>"http://studentersamfundet.no/vis.php?ID=4481",
		1298660400=>"http://studentersamfundet.no/vis.php?ID=4482",
		1298746800=>"http://studentersamfundet.no/vis.php?ID=4483",
		1299178800 => "http://studentersamfundet.no/vis.php?ID=4484",
		1299265200 =>"http://studentersamfundet.no/vis.php?ID=4485"
			);

?>
<div style="margin-top:10px;">
<h2>Kr&oslash;plingen</h2>
<img style="float:left;" src="http://studentersamfundet.no/imageResize.php?pic=bilder/nyheter/665jpeg&maxwidth=400" />
<p>Velkommen til forestilling!</p>
<p><a href="http://studentersamfundet.no/foreninger.php?id=7">Teater Neuf</a> presenterer Krøprlngen, som er en svart, rørende komedie om drømmer, kjærlighet - og ikke minst det å stå på egne ben.
</p>
<h4>Forestillinger:</h4>
<ul>
<?php foreach($forestillinger as $tid=>$url): 
if($tid < time()) continue;
?>
	<li><a href="<?php echo $url ?>"><?php


$month = round(strftime("%m", $tid));
$day = round(strftime("%e", $tid));

$time = strftime("%H", $tid) - 1;

$ukedag = strftime("%w", $tid);
$ukedager = array('s&oslash;ndag','mandag','tirsdag','onsdag','torsdag','fredag','l&oslash;rdag');
$ukedag = $ukedager[$ukedag];

echo "$ukedag $day/$month, klokka $time";


 ?></a></li>
<?php endforeach ?>
</ul>
</div>
