					<form id="newsletter-signup-form" name="newletter-signup-form" method="post" action="http://www.mailit.no/box.php">
					<h3>Hold deg oppdatert</h3>
					    <p>Nyhetsbrevet vårt sendes ut én gang i uka, og inneholder program og eksklusive konkurranser.</p>
					    <p>Meld deg på her:
						<input type="text"   name="email" id="email"      value="Din e-post" size="16" onblur="if('' == this.value) this.value='Din e-post';" onfocus="if('Din e-post' == this.value) this.value = '';"><?php /*
						<input type="radio"  name="funcml"                value="add" checked="checked">Abonnér<br />
						<input type="radio"  name="funcml"                value="unsub2">Meld deg av<br>
						 */ ?>
						<input type="hidden" name="funcml"                value="add" checked="checked" />
						<input type="hidden" name="p" id="p"              value="26" />
						<input type="hidden" name="nlbox[1]"              value="7" />
						<input type="submit" name="Ok" id="newsletter-ok" value="Abbonér!"  class="btn" />
					    </p>
					</form>
