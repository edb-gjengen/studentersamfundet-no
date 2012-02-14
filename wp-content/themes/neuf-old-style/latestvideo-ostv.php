		<div id="ostv-latest-video" class="grid_6 alpha">
			<?php
			$username = 'ostvn';
			$url = 'http://vimeo.com/api/v2/'.$username.'/videos.php';
			$v = unserialize(file_get_contents($url));
			$video_id = $v[0]['id'];
			?>
			<iframe 
				src="http://player.vimeo.com/video/<?php echo $video_id;?>?title=0&amp;byline=0&amp;portrait=0" 
				width="570" 
				height="321" 
				frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen>
			</iframe>
		</div>

		<div id="ostv-latest-description" class="grid_2 omega">
			<h2><a href="http://ostv.no/">OSTV</a></h2>

			<h3><?php echo $v[0]['title']; ?></h3>
			<p><?php echo $v[0]['description']; ?></p>
		</div>

