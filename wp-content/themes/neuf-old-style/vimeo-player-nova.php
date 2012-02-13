<?php
/*
Template name: Vimeo player nova
*/
$username = 'user3461524';
$url = 'http://vimeo.com/api/v2/'.$username.'/videos.php';
$v = unserialize(file_get_contents($url));
$video_id = $v[0]['id'];
?>
<iframe 
	src="http://player.vimeo.com/video/<?php echo $video_id;?>?title=0&amp;byline=0&amp;portrait=0" 
	width="400" 
	height="225" 
	frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen>
</iframe>
