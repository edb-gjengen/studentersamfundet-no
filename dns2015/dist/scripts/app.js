function supports3d(){if(!window.getComputedStyle)return!1;var e,t=document.createElement("p"),r={webkitTransform:"-webkit-transform",OTransform:"-o-transform",msTransform:"-ms-transform",MozTransform:"-moz-transform",transform:"transform"};document.body.insertBefore(t,null);for(var n in r)void 0!==t.style[n]&&(t.style[n]="translate3d(1px,1px,1px)",e=window.getComputedStyle(t).getPropertyValue(r[n]));return document.body.removeChild(t),void 0!==e&&e.length>0&&"none"!==e}$(document).ready(function(){var e=$(".menu-toggle"),t=$("#main-menu");if(supports3d()?$("html").addClass("csstransforms3d"):$("html").addClass("no-csstransforms3d"),e.on("click",function(r){r.preventDefault(),t.toggleClass("visible"),e.toggleClass("inverted")}),$(document).on("click",function(r){$(r.target).closest("#main-menu, .menu-toggle").length||(t.removeClass("visible"),e.removeClass("inverted"))}),$("#wpadminbar").length&&(e.css("top","64px"),t.css("top","32px")),$("#flickr-feed").length){$.getJSON("https://secure.flickr.com/services/feeds/groups_pool.gne?format=json&id=1292860@N21&jsoncallback=?",function(e){var t="",r="";$.each(e.items,function(e,n){var o=n.media.m.replace("_m.jpg","_q.jpg");return r=0===e?' class="first-item"':"",t+="<li"+r+'><a href="'+n.link+'" target="_blank">',t+='<img title="'+n.title+'" src="'+o+'" alt="'+n.title+'" />',t+='<span class="flickr-title">'+n.title+"</span</a></li>",11!=parseInt(e,10)}),$(".flickr-feed").html(t)})}if($(".events--load-more").on("click",function(e){e.preventDefault();var t="action=infinite_scroll&page="+pageNumber+'&time_scope=past&term=<?php echo get_query_var("term"); ?>&template=loop-taxonomy-event_type';$.ajax({url:"<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php",type:"POST",data:t,success:function(e){$(".events-active").append(e)},error:function(){console.log("snufs :'(")}})}),$(".page-template-page-program").length){var r=$(".program--filter--reset-btn"),n=$(".event-row");$(".program--filter--form").on("change",function(e){var t=$(e.target).val();n.addClass("hidden"),$('[data-root-term-id="'+t+'"').parent().parent().removeClass("hidden"),r.prop("disabled",!1)}).on("reset",function(e){n.removeClass("hidden"),r.prop("disabled",!0)})}});