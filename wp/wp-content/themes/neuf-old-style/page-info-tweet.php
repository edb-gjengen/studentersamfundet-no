<?php
/*
 * Template Name: Infoscreen tweets
 *
 * Dipslays last tweet. Want the second tweet? ?page=2 (or 3 or 4 and so on..)
 *
 * @author misund
 */

$tweet_index= get_query_var('page') ? get_query_var('page') -1 : 0;
$tweet_rpp = $tweet_index + 1;

$_webkit_scale = "1.5";
?>

<?php get_template_part( 'header' , 'infoscreen' ); ?>

	<style type="text/css">

		body {
			background:#e99835;
		}

		.spam {
			text-align:center;
			position:absolute;
			width:100%;
			bottom:1.5em;
		}

		#tweets {
			width:1000px;
			position:absolute;
			top:50%;
			left:50%;
			margin-left:-500px;
			margin-top:-200px;
			transform: scale(<?php echo $_webkit_scale; ?>);
			-ms-transform: scale(<?php echo $_webkit_scale; ?>); /* IE 9 */
			-webkit-transform: scale(<?php echo $_webkit_scale; ?>); /* Safari and Chrome */
			-o-transform: scale(<?php echo $_webkit_scale; ?>); /* Opera */
			-moz-transform: scale(<?php echo $_webkit_scale; ?>); /* Firefox */)))))
		}
		.profile_image,
		.tweet
		{
			float:left;
		}

		.profile_image {
			width:250px;
		}
		.from_user_name {
			font-family:Arvo;
			font-size: 40px;
			line-height:1.5em;
			padding:10px 0.5em 0 48px;
		}

		.tweet {
			padding: 24px 48px;
			font-size:24px;
			line-height:1.5em;
			color:#fff;
			width:650px;
		}

	</style>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
	<script type="text/javascript">
		function searchTwitter(query) {
			$.ajax({
					url: 'http://search.twitter.com/search.json?' + jQuery.param(query),
					dataType: 'jsonp',
					success: function(data) {
							var tweets = $('#tweets');
							tweets.html('');
							var tweet = data['results'][<?php echo $tweet_index; ?>];
							console.log(tweet);
							//tweets.append( '<div class="user">');
							tweets.append( '  <img class="profile_image" alt="" src="' + tweet['profile_image_url'].replace('_normal.png', '.png').replace('_normal.jpg','.jpg').replace('_normal.jpeg', '.jpeg') + '" />');
							tweets.append( '  <span class="from_user_name">' + tweet['from_user_name'] + '</span>' );
							tweets.append( '  <span class="from_user">@' + tweet['from_user'] + '</span>');
							//tweets.append( '</div><!-- .user -->');
							tweets.append( '<div class="tweet">' + tweet['text'] + '</div>');
					}
			});
		}

		$(document).ready(function() {
			var params = {
				q: 'dns1813',
				rpp: <?php echo $tweet_rpp; ?>
			};
			// console.log(jQuery.param(params));
			searchTwitter(params);
		});
	</script>
</head>
<body>
<p class="spam">Din tweet her? #dns1813</p>
<div id="tweets"></div>

</body>
</html>