$(document).ready(function(){if($("#flickr_feed").length){var e=12,t="https://secure.flickr.com/services/feeds/groups_pool.gne?format=json&id=1292860@N21&jsoncallback=?";$.getJSON(t,function(t){var i="",l="";$.each(t.items,function(t,a){var r=a.media.m.replace("_m.jpg","_q.jpg");return l=0===t?' class="first-item"':"",i+="<li"+l+'><a href="'+a.link+'" target="_blank">',i+='<img title="'+a.title+'" src="'+r+'" alt="'+a.title+'" />',i+='<span class="flickr-title">'+a.title+"</span</a></li>",parseInt(t,10)!=e-1}),$("#flickr_feed").html(i)})}});